<?php
namespace App\Http\Action;

use Zend\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ServerRequestInterface;

class HelloAction
{
    public function __invoke(ServerRequestInterface $request)
    {
        $name = isset($request->getQueryParams()['name']) && !empty($request->getQueryParams()['name']) ? $request->getQueryParams()['name'] : 'GUEST';
        return new HtmlResponse('Hello, ' . $name);
    }
}