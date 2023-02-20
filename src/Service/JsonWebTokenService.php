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
        return json_decode(json_encode($this->jwt->decode($token, new Key($this->jwtKey, 'HS256'))), true);
    }

    public function getCurrentTime(): \DateTime
    {
        return (new \DateTime('now', new \DateTimeZone('Europe/Warsaw')));
    }
}