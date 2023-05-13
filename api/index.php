<?php

namespace Koupon\Api;

require_once 'vendor/autoload.php';

use Koupon\Api\Router;
use Koupon\Api\Cart;
use \Exception;
use Whoops\Handler\Handler;

final class Index
{
    private $router;
    private $whoops;
    public function __construct()
    {
        try
        {
            // disable xdebug

            // Handle cors
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
            header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
            Log::console($_SERVER['REQUEST_METHOD'] . " " . $_SERVER['REQUEST_URI'], "info");

            $whoops = new \Whoops\Run;
            $whoops->pushHandler(new \Whoops\Handler\JsonResponseHandler());
            $whoops->pushHandler(function ($e)
            {
                Log::console($e->getMessage(), "error", $e);
                return Handler::DONE;
            });
            $whoops->register();

            $bramus = new Router();
            $this->router = $bramus->getRouter();

            $this->router->mount('/api', function ()
            {
                $this->router->get('/', function ()
                {
                    echo 'Hello World';
                });

                $this->router->post('/cart/add', function ()
                {
                    $cart = new Cart();
                    $cart->add();
                });
            });

            $this->router->set404(function ()
            {
                header('HTTP/1.1 404 Not Found');
                die(json_encode(['error' => '404 Not Found']));
            });

            $this->router->run();
        }
        catch (Exception $e)
        {
            Log::console($e->getMessage(), "error", $e);
        }
    }
}

new Index();
