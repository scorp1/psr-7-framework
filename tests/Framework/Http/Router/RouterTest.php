<?php
namespace Tests\Framework\Http\Router;

use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\RouterCollection;
use Framework\Http\Router\Router;
use PHPUnit\Framework\TestCase;
use Zend\Diactoros\Request;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Uri;

class RouterTest extends TestCase
{
    public function testCorrectMethod()
    {
        $routes = new RouterCollection();

        $routes->get($nameGet = 'blog', '/blog', $handlerGet = 'handler_get');
        $routes->post($namePost = 'blog_edit', '/blog', $handlerPost = 'handler_post');

        $router = new Router($routes);

        $result = $router->match($this->buildRequest('GET', '/blog'));
        self::assertEquals($nameGet, $result->getName());
        self::assertEquals($handlerGet, $result->getHandler());

        $result = $router->match($this->buildRequest('POST', '/blog'));
        self::assertEquals($namePost, $result->getName());
        self::assertEquals($handlerPost, $result->getHandler());
    }

    public function buildRequest($method, $uri): ServerRequest
    {
        return (new ServerRequest())
        ->withMethod($method)
        ->withUri(new Uri($uri));
    }
}