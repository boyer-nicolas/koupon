<?php

namespace Koupon\Api;

require_once 'vendor/autoload.php';

use Koupon\Api\Router;
use Koupon\Api\Cart;
use Koupon\Api\CartItem;
use Koupon\Api\Response;
use \Exception;
use Whoops\Handler\Handler;

final class Index
{
    private $router;
    private $whoops;
    public function __construct()
    {
        ini_set('session.save_path', '/tmp');

        try
        {
            if (isset($_SERVER['HTTP_ORIGIN']))
            {
                // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
                // you want to allow, and if so:
                header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
                header('Access-Control-Allow-Credentials: true');
                header('Access-Control-Max-Age: 86400');    // cache for 1 day
            }

            // Access-Control headers are received during OPTIONS requests
            if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
            {

                if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                    // may also be using PUT, PATCH, HEAD etc
                    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

                if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                    header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

                exit(0);
            }

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
                    Response::json(['message' => 'Welcome to Koupon API']);
                });

                $this->router->mount('/cart', function ()
                {
                    $cart = new Cart(session_id(), 0, []);
                    $this->router->post('/add', function () use ($cart)
                    {
                        try
                        {
                            $data = json_decode(file_get_contents('php://input'), true);
                            $cart->addItem(new CartItem(
                                $data['id'],
                                $data['title'],
                                $data['price'],
                                $data['quantity']
                            ));

                            Response::json([
                                'message' => 'Item added to cart',
                                'cart' => $cart
                            ]);
                        }
                        catch (Exception $e)
                        {
                            Log::console($e->getMessage(), "error", $e);
                            Response::json(['error' => $e->getMessage()]);
                        }
                    });
                });
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
