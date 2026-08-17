<?php

namespace App\Services\Identity;

use App\Models\Organisation\Company;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class EntraOidcService
{
    public function discovery(Company $company): array
    {
        $tenant = $this->identifier($company->entra_tenant_id);
        return Cache::remember("entra.discovery.{$tenant}", 3600, function () use ($tenant) {
            return Http::acceptJson()->timeout(10)->get(
                "https://login.microsoftonline.com/{$tenant}/v2.0/.well-known/openid-configuration"
            )->throw()->json();
        });
    }

    public function authorizationUrl(Company $company, string $state, string $nonce, string $challenge, ?string $loginHint): string
    {
        $query = array_filter([
            'client_id' => $company->entra_client_id,
            'response_type' => 'code',
            'redirect_uri' => route('entra.callback'),
            'response_mode' => 'query',
            'scope' => 'openid profile email',
            'state' => $state,
            'nonce' => $nonce,
            'code_challenge' => $challenge,
            'code_challenge_method' => 'S256',
            'login_hint' => $loginHint,
        ]);

        return $this->discovery($company)['authorization_endpoint'].'?'.http_build_query($query);
    }

    public function exchange(Company $company, string $code, string $verifier): array
    {
        $response = Http::asForm()->acceptJson()->timeout(15)->post($this->discovery($company)['token_endpoint'], [
            'client_id' => $company->entra_client_id,
            'client_secret' => $company->entra_client_secret,
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => route('entra.callback'),
            'code_verifier' => $verifier,
        ])->throw()->json();

        if (!isset($response['id_token'])) {
            throw new RuntimeException('Microsoft Entra ID returned no ID token.');
        }

        return $response;
    }

    public function verifyIdToken(Company $company, string $jwt, string $nonce): array
    {
        [$encodedHeader, $encodedPayload, $encodedSignature] = array_pad(explode('.', $jwt), 3, null);
        if (!$encodedSignature) {
            throw new RuntimeException('Invalid ID token format.');
        }
        $header = $this->decodeJson($encodedHeader);
        $claims = $this->decodeJson($encodedPayload);
        if (($header['alg'] ?? null) !== 'RS256' || empty($header['kid'])) {
            throw new RuntimeException('Unsupported ID token signature.');
        }

        $discovery = $this->discovery($company);
        $keys = Cache::remember('entra.jwks.'.hash('sha256', $discovery['jwks_uri']), 3600,
            fn () => Http::acceptJson()->timeout(10)->get($discovery['jwks_uri'])->throw()->json('keys', []));
        $jwk = collect($keys)->firstWhere('kid', $header['kid']);
        if (!$jwk || openssl_verify("{$encodedHeader}.{$encodedPayload}", $this->decode($encodedSignature), $this->jwkToPem($jwk), OPENSSL_ALGO_SHA256) !== 1) {
            throw new RuntimeException('Invalid ID token signature.');
        }

        $now = time();
        $audiences = (array) ($claims['aud'] ?? []);
        $expectedIssuer = rtrim((string) $discovery['issuer'], '/');
        if (!in_array($company->entra_client_id, $audiences, true)
            || rtrim((string) ($claims['iss'] ?? ''), '/') !== $expectedIssuer
            || !hash_equals($nonce, (string) ($claims['nonce'] ?? ''))
            || ($claims['exp'] ?? 0) < $now - 60
            || ($claims['nbf'] ?? 0) > $now + 60
            || !hash_equals((string) $company->entra_tenant_id, (string) ($claims['tid'] ?? ''))) {
            throw new RuntimeException('ID token claims could not be validated.');
        }
        return $claims;
    }

    private function identifier(?string $value): string
    {
        if (!$value || !preg_match('/^[0-9a-f-]{36}$/i', $value)) {
            throw new RuntimeException('Invalid Entra tenant identifier.');
        }
        return $value;
    }

    private function decodeJson(string $value): array
    {
        return json_decode($this->decode($value), true, 32, JSON_THROW_ON_ERROR);
    }

    private function decode(string $value): string
    {
        return base64_decode(strtr($value, '-_', '+/').str_repeat('=', (4 - strlen($value) % 4) % 4), true) ?: '';
    }

    private function jwkToPem(array $jwk): string
    {
        $n = $this->decode($jwk['n']); $e = $this->decode($jwk['e']);
        $integer = fn (string $v) => "\x02".$this->asn1Length(strlen($v) + ((ord($v[0]) & 0x80) ? 1 : 0)).((ord($v[0]) & 0x80) ? "\0" : '').$v;
        $rsa = "\x30".$this->asn1Length(strlen($integer($n).$integer($e))).$integer($n).$integer($e);
        $oid = hex2bin('300d06092a864886f70d0101010500');
        $bit = "\x03".$this->asn1Length(strlen($rsa) + 1)."\0".$rsa;
        $der = "\x30".$this->asn1Length(strlen($oid.$bit)).$oid.$bit;
        return "-----BEGIN PUBLIC KEY-----\n".chunk_split(base64_encode($der), 64, "\n")."-----END PUBLIC KEY-----\n";
    }

    private function asn1Length(int $length): string
    {
        if ($length < 128) return chr($length);
        $bytes = ltrim(pack('N', $length), "\0");
        return chr(0x80 | strlen($bytes)).$bytes;
    }
}
