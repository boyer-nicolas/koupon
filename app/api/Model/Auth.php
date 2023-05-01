<?php

namespace Koupon\Model;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Koupon\Model\Filters;
use Koupon\Model\Response;
use Koupon\Model\Config;

final class Auth
{
    private $secretKey;
    private $issuedAt;
    private $expire;
    private $serverName;
    private $payload;
    private $filters;
    private $jwt;
    private $response;
    private $config;

    public function __construct()
    {
        $this->config = new Config();
        $this->filters   = new Filters();
        $this->secretKey  = $this->config->get('APP_SECRET');
        $this->issuedAt   = new \DateTimeImmutable();
        $this->expire     = $this->issuedAt->modify('+6 minutes')->getTimestamp();
        $this->serverName = $this->config->get('APP_URl');
        $this->response  = new Response();

        $this->payload = [
            'userid'  => 0,
            'name'  => $this->serverName,
            'iat'  => $this->issuedAt->getTimestamp(),
            'exp'  => $this->expire,
        ];
    }

    /**
     * @return string
     */
    public function generateJWT(): string
    {
        $secret_Key  = $this->secretKey;
        $date   = new \DateTimeImmutable();
        $expire_at     = $date->modify('+6 minutes')->getTimestamp();      // Add 60 seconds
        $domainName = "app.localhost";
        $username   = "username";                                           // Retrieved from filtered POST data
        $request_data = [
            'iat'  => $date->getTimestamp(),         // Issued at: time when the token was generated
            'iss'  => $domainName,                       // Issuer
            'nbf'  => $date->getTimestamp(),         // Not before
            'exp'  => $expire_at,                           // Expire
            'userName' => $username,                     // User name
        ];
        return JWT::encode(
            $request_data,
            $secret_Key,
            'HS512'
        );
    }

    /**
     * @param string $jwt
     * 
     * @return [type]
     */
    public function setJWT(string $jwt)
    {
        $this->jwt = $jwt;
    }

    /**
     * @param string $jwt
     * 
     * @return [type]
     */
    public function decode(string $jwt)
    {
        try
        {
            JWT::decode($jwt, new Key($this->secretKey, 'HS512'));
            return true;
        }
        catch (\Exception $e)
        {
            $this->response->sendUnauthorized();
            return false;
        }
    }

    /**
     * @return [type]
     */
    public function isAuthenticated()
    {
        return $this->decode($this->getJWT());
    }

    /** 
     * Get header Authorization
     * */
    /**
     * @return [type]
     */
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
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            if (isset($requestHeaders['Authorization']))
            {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    /**
     * get access token from header
     * @return [type]
     */
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
