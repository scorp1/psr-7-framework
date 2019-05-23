<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response\HtmlResponse;
use Framework\Http\ResponseSender;

### Initialization

$request = ServerRequestFactory::fromGlobals();

### Actions

$name = isset($request->getQueryParams()['name']) && !empty($request->getQueryParams()['name']) ? $request->getQueryParams()['name'] : 'GUEST';

$response = (new HtmlResponse('Hello, ' . $name . ' !'))
    ->withHeader('X-Developer', 'ScorpDev');

### Sending

$emitter = new ResponseSender();
$emitter->send($response);
