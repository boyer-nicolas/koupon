<?php

namespace Koupon\Model;

use Koupon\Model\Auth;

final class Filters
{
    private $data;
    private $auth;

    public function __construct()
    {
        $this->data = $_POST;
        $this->auth = new Auth();
    }

    /**
     * @param string $key
     * 
     * @return string
     */
    public function sanitize(string $key): string
    {
        return htmlspecialchars($this->data[$key]);
    }

    public function authenticate(): bool
    {
        // Look for bearer token
        $headers = apache_request_headers();
        $authorizationHeader = $headers['Authorization'];
        $token = explode(' ', $authorizationHeader)[1];

        if (!$token)
        {
            return false;
        }

        try
        {
            $this->auth->decode($token);
            return true;
        }
        catch (\Exception $e)
        {
            return false;
        }
    }
}
