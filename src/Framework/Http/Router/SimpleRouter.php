<?php
namespace Framework\Http\Router;

use Framework\Http\Router\Exception\RouteNotFoundException;

class SimpleRouter implements Router
{
    private $routes;

    public function __construct(RouterCollection $routes)
    {
        $this->routes = $routes;
    }

    /**
     * @param ServerRequestInterface $request
     * @return Framework\Http\Router\Result
     */
    public function match(ServerRequestInterface $request): Result
    {
        foreach ($this->routes->getRouters() as $route){
            if ($result = $route->match($request)) {
                return $result;
            }
        }
        throw new RequestNotMatchedException($request);
    }

    public function generate($name, array $params = []): string
    {
        $arguments = array_filter($params);

        foreach ($this->routes->getRouters() as $route){
            if (null !== $url = $route->generate($name, array_filter($params))) {
                return $url;
            }
        }
        throw new RouteNotFoundException($request);
    }
}