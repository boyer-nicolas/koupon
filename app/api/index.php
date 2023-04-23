<?php

namespace Koupon\Model;

require __DIR__ . '/vendor/autoload.php';


$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\JsonResponseHandler());
$whoops->register();

define('APP_ROOT', dirname(__DIR__));

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
            $this->response->sendNotFound('Page not found!');
        });

        $this->engine->mount('/api', function ()
        {
            $this->auth = new \Koupon\Model\Auth();
            $this->auth->isAuthenticated();

            $this->engine->get('/', function ()
            {
                $this->response->sendMessage('Welcome to Koupon API!');
            });
        });

        $this->engine->run();
    }
}

new Index();
