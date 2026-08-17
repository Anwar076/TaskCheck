<?php

namespace App\Http\Middleware;

use App\Models\Organisation\Company;
use Closure;
use Illuminate\Http\Request;

class AuthenticateScim
{
    public function handle(Request $request, Closure $next)
    {
        $company = Company::query()->where('scim_endpoint_key', $request->route('endpointKey'))->first();
        $token = $request->bearerToken();
        if (!$company || !$company->scim_token_hash || !$token
            || !hash_equals($company->scim_token_hash, hash('sha256', $token))) {
            return response()->json([
                'schemas' => ['urn:ietf:params:scim:api:messages:2.0:Error'],
                'detail' => 'Invalid SCIM bearer token.', 'status' => '401',
            ], 401, ['Content-Type' => 'application/scim+json']);
        }
        $request->attributes->set('scim_company', $company);
        return $next($request);
    }
}
