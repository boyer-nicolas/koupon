<?php

namespace Koupon\Api;

final class Session
{
    private $session;

    public function __construct()
    {
        session_start();
        $this->session = $_SESSION;
    }

    public static function start()
    {
        session_start();
    }

    public static function destroy()
    {
        session_destroy();
    }

    public function get($key)
    {
        return $this->session[$key] ?? null;
    }

    public function set($key, $value)
    {
        $this->session[$key] = $value;
    }

    public function has($key)
    {
        return isset($this->session[$key]);
    }

    public function remove($key)
    {
        unset($this->session[$key]);
    }

    public function all()
    {
        return $this->session;
    }

    public function clear()
    {
        $this->session = [];
    }

    public function dump()
    {
        var_dump($this->session);
    }

    public function __destruct()
    {
        $_SESSION = $this->session;
    }

    public function __toString()
    {
        return json_encode($this->session);
    }
}
