<?php
namespace App\Http\Action;

use Zend\Diactoros\Response\HtmlResponse;

class AboutAction
{
    public function __invoke()
    {
        return new HtmlResponse('This is simple site!');
    }
}