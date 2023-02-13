<?php

namespace App\Service;

use Firebase\JWT\JWT;

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
}