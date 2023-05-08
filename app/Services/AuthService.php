<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;

class AuthService
{
    public static function base64UrlDecode(string $data): string|false
    {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }

    public static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function signAndEncode(string $encodedHeaderAndPayload): string
    {
        $signature = hash_hmac('sha256', $encodedHeaderAndPayload, env('APP_KEY'), true);
        $encodedSignature = self::base64UrlEncode($signature);
        return $encodedSignature;
    }

    public function createToken(): string
    {
        $durationSeconds = 60 * 90;
        if (env('APP_DEBUG', false)) {
            $durationSeconds = 60 * 60 * 8;
        }

        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode(['iat' => time(), 'nbf' => time(), 'exp' => time() + $durationSeconds]);

        $encodedHeaderAndPayload = self::base64UrlEncode($header) . '.' . self::base64UrlEncode($payload);

        $encodedSignature = self::signAndEncode($encodedHeaderAndPayload);

        return $encodedHeaderAndPayload . '.' . $encodedSignature;
    }

    public function login(array $inputData): array
    {
        $validator = Validator::make($inputData, [
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
        ]);
        $validData = $validator->validate();

        if (!($validData['username'] === env('APP_USERNAME') && $validData['password'] === env('APP_PASSWORD'))) {
            throw new AuthenticationException('Invalid credentials.');
        }

        $jwt = $this->createToken();

        return ['token' => $jwt];
    }

    public function validateToken(string $token): void
    {
        if (substr_count($token, '.') !== 2) {
            throw new AuthorizationException('A valid token must be provided.');
        }

        list($encodedHeaderUser, $encodedPayloadUser, $encodedSignatureUser) = explode('.', $token);

        $headerJsonString = self::base64UrlDecode($encodedHeaderUser);
        if ($headerJsonString === false) {
            throw new AuthorizationException('A valid token must be provided.');
        }
        $header = json_decode($headerJsonString, true, 2);
        if (!is_array($header) || !array_key_exists('alg', $header) || !array_key_exists('typ', $header) || $header['typ'] != 'JWT') {
            throw new AuthorizationException('A valid token must be provided.');
        }
        $encodedHeaderAndPayloadUser = $encodedHeaderUser . '.' . $encodedPayloadUser;
        if ($header['alg'] === 'HS256') {
            $encodedSignature = self::signAndEncode($encodedHeaderAndPayloadUser);
            if (!hash_equals($encodedSignature, $encodedSignatureUser)) {
                throw new AuthorizationException('A valid token must be provided.');
            }
        } else {
            throw new AuthorizationException('A valid token must be provided.');
        }

        // signature is verified - check payload

        $payloadJsonString = self::base64UrlDecode($encodedPayloadUser);
        if ($payloadJsonString === false) {
            throw new AuthorizationException('A valid token must be provided.');
        }
        $payload = json_decode($payloadJsonString, true, 2);
        if (!is_array($payload)) {
            throw new AuthorizationException('A valid token must be provided.');
        }

        if (!array_key_exists('exp', $payload) || (time() > $payload['exp'])) { // 'exp' is mandatory
            throw new AuthorizationException('Token is expired.');
        }

        if (array_key_exists('nbf', $payload) && (time() < $payload['nbf'])) { // 'nbf' is optional
            throw new AuthorizationException('Token is invalid.');
        }
    }
}
