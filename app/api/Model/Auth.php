<?php

namespace Koupon\Model;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Koupon\Model\Filters;
use Koupon\Model\Response;

final class Auth
{
    private $secretKey;
    private $issuedAt;
    private $expire;
    private $serverName;
    private $jwtData;
    private $filters;
    private $jwt;
    private $response;

    public function __construct()
    {

        $this->filters   = new Filters();
        $this->secretKey  = $_ENV['APP_SECRET'];
        $this->issuedAt   = new \DateTimeImmutable();
        $this->expire     = $this->issuedAt->modify('+6 minutes')->getTimestamp();
        $this->serverName = $_ENV['APP_URL'];
        $this->response  = new Response();

        $this->jwtData = [
            'iat'  => $this->issuedAt->getTimestamp(),
            'iss'  => $this->serverName,
            'nbf'  => $this->issuedAt->getTimestamp(),
            'exp'  => $this->expire,
        ];
    }

    public function generateJWT(): string
    {
        return JWT::encode(
            $this->jwtData,
            $this->secretKey,
            'HS512'
        );
    }

    public function setJWT(string $jwt)
    {
        $this->jwt = $jwt;
    }

    public function decode(string $jwt)
    {
        try
        {
            JWT::decode($jwt, $this->secretKey, ['HS512']);
            return true;
        }
        catch (\Exception $e)
        {
            $this->response->sendUnauthorized();
            return false;
        }
    }

    public function isAuthenticated()
    {
        return $this->decode($this->getJWT());
    }

    /** 
     * Get header Authorization
     * */
    private function getAuthorizationHeader()
    {
        $headers = null;
        if (isset($_SERVER['Authorization']))
        {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION']))
        { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        }
        elseif (function_exists('apache_request_headers'))
        {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization']))
            {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    /**
     * get access token from header
     * */
    private function getJWT()
    {
        $headers = $this->getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers))
        {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches))
            {
                return $matches[1];
            }
        }
        return $this->response->sendUnauthorized();
    }
}
