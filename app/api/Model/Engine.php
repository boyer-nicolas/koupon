<?php

namespace Koupon\Model;

use \Bramus\Router\Router;

final class Engine
{
    private $router;

    public function __construct()
    {
        $this->router = new Router();
    }

    /**
     * @return [type]
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @return [type]
     */
    public function run()
    {
        $this->router->run();
    }

    /**
     * @param string $path
     * @param Object $callback
     * 
     * @return [type]
     */
    public function get(string $path, Object $callback)
    {
        $this->router->get($path, $callback);
    }

    /**
     * @param string $path
     * @param Object $callback
     * 
     * @return [type]
     */
    public function post(string $path, Object $callback)
    {
        $this->router->post($path, $callback);
    }

    /**
     * @param string $path
     * @param Object $callback
     * 
     * @return [type]
     */
    public function put(string $path, Object $callback)
    {
        $this->router->put($path, $callback);
    }

    /**
     * @param string $path
     * @param Object $callback
     * 
     * @return [type]
     */
    public function delete(string $path, Object $callback)
    {
        $this->router->delete($path, $callback);
    }

    /**
     * @param string $path
     * @param Object $callback
     * 
     * @return [type]
     */
    public function patch(string $path, Object $callback)
    {
        $this->router->patch($path, $callback);
    }

    /**
     * @param string $path
     * @param Object $callback
     * 
     * @return [type]
     */
    public function options(string $path, Object $callback)
    {
        $this->router->options($path, $callback);
    }

    /**
     * @param string $method
     * @param mixed $path
     * @param Object $callback
     * 
     * @return [type]
     */
    public function before(string $method, $path, Object $callback)
    {
        $this->router->before($method, $path, $callback);
    }

    /**
     * @param string $path
     * @param Object $callback
     * 
     * @return [type]
     */
    public function mount(string $path, Object $callback)
    {
        $this->router->mount($path, $callback);
    }

    /**
     * @param string $namespace
     * 
     * @return [type]
     */
    public function setNamespace(string $namespace)
    {
        $this->router->setNamespace($namespace);
    }

    /**
     * @param Object $callback
     * 
     * @return [type]
     */
    public function set404(Object $callback)
    {
        $this->router->set404($callback);
    }

    /**
     * @param string $basePath
     * 
     * @return [type]
     */
    public function setBasePath(string $basePath)
    {
        $this->router->setBasePath($basePath);
    }
}
