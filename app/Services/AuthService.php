<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;

class AuthService
{
    public static function base64UrlDecode($data)
    {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }

    public static function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public function createToken()
    {
        $durationSeconds = 60 * 90;
        if (env('APP_DEBUG', false)) {
            $durationSeconds = 60 * 60 * 8;
        }

        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode(['iat' => time(), 'nbf' => time(), 'exp' => time() + $durationSeconds]);

        $encodedHeaderAndPayload = static::base64UrlEncode($header) . '.' . static::base64UrlEncode($payload);

        $signature = hash_hmac('sha256', $encodedHeaderAndPayload, env('APP_KEY'), true);
        $encodedSignature = static::base64UrlEncode($signature);

        return $encodedHeaderAndPayload . '.' . $encodedSignature;
    }

    public function login($inputData)
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
}
