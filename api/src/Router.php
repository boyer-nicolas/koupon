<?php

namespace Koupon\Api;

use Bramus\Router\Router as BramusRouter;

final class Router
{
    private $routes;
    private $bramus;
    public function __construct()
    {
        $this->bramus = new BramusRouter();
    }

    public function getRouter()
    {
        return $this->bramus;
    }
}
