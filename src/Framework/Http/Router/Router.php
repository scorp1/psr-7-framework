<?php
namespace Framework\Http\Router;

use Psr\Http\Message\ServerRequestInterface;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Zend\Diactoros\ServerRequest;

class Router
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
            if ($route->methods && !\in_array($request->getMethod(), $route->methods, true)) {
                continue;
            }
            $pattern = preg_replace_callback('~\{([^\}]+)\}~', function($matches) use ($route) {
                $argument = $matches[1];
                $replace = $route->tokens[$argument] ?? '[^}]+';
                return '(?P<' . $argument . '>' . $replace . ')';
            }, $route->pattern);
            $path = $request->getUri()->getPath();

            if (preg_match('~^' . $pattern . '$~i', $path, $matches)) {

                return new Result(
                    $route->name,
                    $route->handler,
                    array_filter($matches, '\is_string', ARRAY_FILTER_USE_KEY)
                );
            }
        }
        throw new RequestNotMatchedException($request);
    }

    public function generate($name, array $params = []): string
    {
        $arguments = array_filter($params);

        foreach ($this->routes->getRouters() as $route){
            if ($name !== $route->name) {
                continue;
            }

            $url = preg_replace_callback('~\{([^\}]+)\}~', function($matches) use (&$arguments) {
                    $argument = $matches[1];
                    if (!array_key_exists($argument, $arguments)) {
                        throw new \InvalidArgumentException('Missing parameter ' . $arguments);
                    }
                    return $argument[$arguments];
                }, $route->pattern);

            if($url !== null) {
                return $url;
            }
        }
    }
}