<?php

namespace App\Service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JsonWebTokenService
{
    public function __construct(
        private string $jwtKey,
        private JWT    $jwt
    )
    {
    }

    public function createToken(array $payload): string
    {
        return $this->jwt->encode($payload, $this->jwtKey, 'HS256');
    }

    public function decodeToken(string $token): array
    {
        dd($this->jwt->decode($token, new Key($this->jwtKey, 'HS256')));
    }
}