<?php

namespace Koupon\Model;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\Dotenv\Dotenv;
use Koupon\Model\Filters;

final class Auth
{
    private $secretKey;
    private $issuedAt;
    private $expire;
    private $serverName;
    private $jwtData;
    private $filters;

    public function __construct()
    {
        $dotenv = new Dotenv();
        $dotenv->load(APP_ROOT . '/.env');

        $this->filters   = new Filters();
        $this->secretKey  = $_ENV['APP_SECRET'];
        $this->issuedAt   = new \DateTimeImmutable();
        $this->expire     = $this->issuedAt->modify('+6 minutes')->getTimestamp();
        $this->serverName = $_ENV['APP_URL'];

        $this->jwtData = [
            'iat'  => $this->issuedAt->getTimestamp(),
            'iss'  => $this->serverName,
            'nbf'  => $this->issuedAt->getTimestamp(),
            'exp'  => $this->expire,
        ];
    }

    public function getJWT(): string
    {
        return JWT::encode(
            $this->jwtData,
            $this->secretKey,
            'HS512'
        );
    }

    public function decode(string $jwt)
    {
        return JWT::decode($jwt, $this->secretKey, ['HS512']);
    }

    public function isAuthenticated(): bool
    {
        return  $this->filters->authenticate();
    }
}
