<?php

namespace Koupon\Model;

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\JsonResponseHandler());
$whoops->register();

define('APP_ROOT', __DIR__);

$dotenv = new Dotenv();
$dotenv->load(APP_ROOT . '/.env');

class Index
{
    private $engine;
    private $response;
    private $auth;

    public function __construct()
    {
        $this->engine = new \Koupon\Model\Engine();
        $this->response = new \Koupon\Model\Response();

        $this->engine->set404(function ()
        {
            $this->response->sendNotFound();
        });


        $this->auth = new \Koupon\Model\Auth();

        $this->engine->before('GET|POST|PUT|DELETE', '/.*', function ()
        {
            if (!$this->auth->isAuthenticated())
            {
                $this->response->sendUnauthorized();
            }
        });

        $this->engine->get('/', function ()
        {
            $this->response->sendMessage('Welcome to Koupon API!');
        });

        $this->engine->run();
    }
}

new Index();
