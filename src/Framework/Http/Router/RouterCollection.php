<?php
namespace Framework\Http\Router;

class RouterCollection
{
    private $routers = [];

    public function addRoute(Route $route)
    {
        $this->routers[] = $route;
    }

    public function add($name, $pattern, $handler,array $methods, array $tokens = [])
    {
        $this->addRoute(new Route($name, $pattern, $handler, $methods, $tokens));
    }

   public function any($name, $pattern, $handler, array $tokens = [])
   {
       $this->addRoute(new Route($name, $pattern, $handler, [], $tokens));
   }

   public function get($name, $pattern, $handler, array $tokens = [])
   {
       $this->addRoute(new Route($name, $pattern, $handler, ['GET'], $tokens));
   }

   public function post($name, $pattern, $handler, array $tokens = [])
   {
       $this->addRoute(new Route($name, $pattern, $handler, ['POST'], $tokens));
   }

    public function getRouters()
    {
        return $this->routers;
    }
}