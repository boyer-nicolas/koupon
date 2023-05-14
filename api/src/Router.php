<?php

namespace Koupon\Api;

use Bramus\Router\Router as BramusRouter;

final class Router
{
    private $routes;
    private $bramus;
    public function __construct()
    {
        session_start();
        $this->bramus = new BramusRouter();
        $this->bramus->set404(function ()
        {
            header('HTTP/1.1 404 Not Found');
            die(json_encode(['error' => '404 Not Found']));
        });
    }

    public function getRouter()
    {
        return $this->bramus;
    }
}
