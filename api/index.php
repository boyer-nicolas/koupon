<?php

namespace Koupon\Api;

require_once 'vendor/autoload.php';

use Koupon\Api\Router;
use Koupon\Api\Cart;
use Koupon\Api\CartItem;
use Koupon\Api\Response;
use Koupon\Api\Products;
use \Exception;
use Whoops\Handler\Handler;

final class Index
{
    private $router;
    private $whoops;
    public function __construct()
    {
        ini_set('session.save_path', '/tmp');
        ini_set('session.gc_probability', 1);
        ini_set('session.gc_divisor', 1);
        ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 30);
        ini_set('session.cookie_secure', false);
        session_id('koupon');
        session_start();

        try
        {
            if (isset($_SERVER['HTTP_ORIGIN']))
            {
                // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
                // you want to allow, and if so:
                $http_origin = $_SERVER['HTTP_ORIGIN'];
                $allowedOrigins = [
                    "163.172.191.153",
                    "koupon.vercel.app"
                ];
                if (!in_array($http_origin, $allowedOrigins))
                {
                    header("Access-Control-Allow-Origin: $http_origin");
                }
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
                    $cart = new Cart();

                    $this->router->get('/', function () use ($cart)
                    {
                        try
                        {
                            Response::json([
                                'cart' => $cart->get()
                            ]);
                        }
                        catch (Exception $e)
                        {
                            Log::console($e->getMessage(), "error", $e);
                            Response::json(['error' => $e->getMessage()]);
                        }
                    });

                    $this->router->mount('/coupon', function () use ($cart)
                    {
                        $this->router->post('/add', function () use ($cart)
                        {
                            try
                            {
                                $data = json_decode(file_get_contents('php://input'), true);
                                $code = Filters::validateString($data['code']);

                                $cart->addCoupon($code);

                                Response::json([
                                    'message' => 'Coupon added to cart',
                                    'cart' => $cart->get()
                                ]);
                            }
                            catch (Exception $e)
                            {
                                Log::console($e->getMessage(), "error", $e);
                                Response::json(['error' => $e->getMessage()]);
                            }
                        });

                        $this->router->post('/remove', function () use ($cart)
                        {
                            try
                            {
                                $cart->removeCoupon();

                                Response::json([
                                    'message' => 'Coupon removed from cart',
                                    'cart' => $cart->get()
                                ]);
                            }
                            catch (Exception $e)
                            {
                                Log::console($e->getMessage(), "error", $e);
                                Response::json(['error' => $e->getMessage()]);
                            }
                        });
                    });

                    $this->router->post('/add', function () use ($cart)
                    {
                        try
                        {
                            $data = json_decode(file_get_contents('php://input'), true);
                            $id = Filters::validateInt($data['id']);
                            $title = Filters::validateString($data['title']);
                            $price = Filters::validateFloat($data['price']);
                            $quantity = Filters::validateInt($data['quantity']);
                            $link = Filters::validateString($data['link']);
                            $tags = Filters::validateArray($data['tags']);
                            $image = Filters::validateString($data['image']);

                            $cart->addItem(new CartItem(
                                $id,
                                $title,
                                $price,
                                $quantity,
                                $link,
                                $tags,
                                $image
                            ));

                            Response::json([
                                'message' => 'Item added to cart',
                                'cart' => $cart->getItems()
                            ]);
                        }
                        catch (Exception $e)
                        {
                            Log::console($e->getMessage(), "error", $e);
                            Response::json(['error' => $e->getMessage()]);
                        }
                    });

                    $this->router->post('/remove', function () use ($cart)
                    {
                        try
                        {
                            $data = json_decode(file_get_contents('php://input'), true);
                            $id = Filters::validateInt($data['id']);
                            $title = Filters::validateString($data['name']);
                            $price = Filters::validateFloat($data['price']);
                            $quantity = Filters::validateInt($data['quantity']);
                            $link = Filters::validateString($data['link']);
                            $tags = Filters::validateArray($data['tags']);
                            $image = Filters::validateString($data['image']);

                            $cart->removeItem(new CartItem(
                                $id,
                                $title,
                                $price,
                                $quantity,
                                $link,
                                $tags,
                                $image
                            ));

                            Response::json([
                                'message' => 'Item removed from cart',
                                'cart' => $cart->getItems()
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
