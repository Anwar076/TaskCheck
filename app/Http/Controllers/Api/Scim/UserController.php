<?php

namespace App\Http\Controllers\Api\Scim;

use App\Http\Controllers\Controller;
use App\Models\Organisation\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    private const SCHEMA = 'urn:ietf:params:scim:schemas:core:2.0:User';

    public function index(Request $request)
    {
        $query = User::withoutGlobalScopes()->where('company_id', $request->attributes->get('scim_company')->id);
        if ($filter = $request->query('filter')) {
            if (!preg_match('/^(userName|externalId)\s+eq\s+"([^"]+)"$/i', $filter, $match)) return $this->error('Unsupported filter.', 400, 'invalidFilter');
            $column = strcasecmp($match[1], 'externalId') === 0 ? 'scim_external_id' : 'email';
            $query->whereRaw('LOWER('.$column.') = ?', [Str::lower($match[2])]);
        }
        $start = max(1, (int) $request->query('startIndex', 1));
        $count = min(100, max(1, (int) $request->query('count', 100)));
        $total = (clone $query)->count();
        $users = $query->orderBy('id')->offset($start - 1)->limit($count)->get()->map(fn ($user) => $this->resource($user));
        return $this->scim(['schemas' => ['urn:ietf:params:scim:api:messages:2.0:ListResponse'], 'totalResults' => $total,
            'startIndex' => $start, 'itemsPerPage' => $users->count(), 'Resources' => $users]);
    }

    public function show(Request $request, string $endpointKey, User $user)
    {
        $this->tenant($request, $user);
        return $this->scim($this->resource($user));
    }

    public function store(Request $request)
    {
        $data = $request->validate(['userName' => ['required', 'email', 'max:255'], 'externalId' => ['nullable', 'string', 'max:255'],
            'active' => ['nullable', 'boolean'], 'displayName' => ['nullable', 'string', 'max:255'], 'name.givenName' => ['nullable', 'string', 'max:120'],
            'name.familyName' => ['nullable', 'string', 'max:120'], 'phoneNumbers.0.value' => ['nullable', 'string', 'max:20']]);
        $company = $request->attributes->get('scim_company');
        if (User::withoutGlobalScopes()->whereRaw('LOWER(email) = ?', [Str::lower($data['userName'])])->exists()) return $this->error('userName already exists.', 409, 'uniqueness');
        $name = $data['displayName'] ?? trim(data_get($data, 'name.givenName').' '.data_get($data, 'name.familyName')) ?: $data['userName'];
        $user = User::withoutGlobalScopes()->create(['company_id' => $company->id, 'name' => $name, 'email' => Str::lower($data['userName']),
            'password' => Hash::make(Str::random(64)), 'role' => 'employee', 'is_active' => $data['active'] ?? true,
            'email_verified_at' => now(), 'scim_external_id' => $data['externalId'] ?? null, 'phone' => data_get($data, 'phoneNumbers.0.value')]);
        return $this->scim($this->resource($user), 201);
    }

    public function replace(Request $request, string $endpointKey, User $user)
    {
        $this->tenant($request, $user);
        $data = $request->validate(['userName' => ['required', 'email', 'max:255'], 'externalId' => ['nullable', 'string', 'max:255'],
            'active' => ['nullable', 'boolean'], 'displayName' => ['nullable', 'string', 'max:255']]);
        $user->forceFill(['email' => Str::lower($data['userName']), 'name' => $data['displayName'] ?? $user->name,
            'scim_external_id' => $data['externalId'] ?? $user->scim_external_id, 'is_active' => $data['active'] ?? true])->save();
        if (!$user->is_active) $user->tokens()->delete();
        return $this->scim($this->resource($user));
    }

    public function patch(Request $request, string $endpointKey, User $user)
    {
        $this->tenant($request, $user);
        $operations = $request->validate(['Operations' => ['required', 'array'], 'Operations.*.op' => ['required', 'string'],
            'Operations.*.path' => ['nullable', 'string'], 'Operations.*.value' => ['nullable']])['Operations'];
        foreach ($operations as $operation) {
            if (strtolower($operation['op']) !== 'replace') continue;
            $path = strtolower((string) ($operation['path'] ?? ''));
            $value = $operation['value'] ?? null;
            if ($path === 'active') $user->is_active = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            elseif ($path === 'username') $user->email = Str::lower((string) $value);
            elseif ($path === 'displayname') $user->name = (string) $value;
            elseif (is_array($value)) {
                if (array_key_exists('active', $value)) $user->is_active = (bool) $value['active'];
                if (isset($value['userName'])) $user->email = Str::lower($value['userName']);
                if (isset($value['displayName'])) $user->name = $value['displayName'];
            }
        }
        $user->save();
        if (!$user->is_active) $user->tokens()->delete();
        return $this->scim($this->resource($user));
    }

    public function destroy(Request $request, string $endpointKey, User $user)
    {
        $this->tenant($request, $user); $user->forceFill(['is_active' => false])->save(); $user->tokens()->delete();
        return response('', 204);
    }

    private function tenant(Request $request, User $user): void { abort_unless($user->company_id === $request->attributes->get('scim_company')->id, 404); }
    private function resource(User $user): array { return ['schemas' => [self::SCHEMA], 'id' => (string) $user->id,
        'externalId' => $user->scim_external_id, 'userName' => $user->email, 'displayName' => $user->name, 'active' => (bool) $user->is_active,
        'emails' => [['value' => $user->email, 'type' => 'work', 'primary' => true]],
        'meta' => ['resourceType' => 'User', 'created' => $user->created_at?->toIso8601String(), 'lastModified' => $user->updated_at?->toIso8601String(),
            'location' => url("/api/scim/v2/Users/{$user->id}")]]; }
    private function scim(array $data, int $status = 200) { return response()->json($data, $status, ['Content-Type' => 'application/scim+json']); }
    private function error(string $detail, int $status, ?string $type = null) { return $this->scim(array_filter(['schemas' => ['urn:ietf:params:scim:api:messages:2.0:Error'], 'detail' => $detail, 'status' => (string) $status, 'scimType' => $type]), $status); }
}
