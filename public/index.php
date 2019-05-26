<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config.php';

use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response\HtmlResponse;
use Framework\Http\ResponseSender;
use Framework\Cache\CacheAdapter;

### Initialization

$request = ServerRequestFactory::fromGlobals();

### Actions

$name = isset($request->getQueryParams()['name']) && !empty($request->getQueryParams()['name']) ? $request->getQueryParams()['name'] : 'GUEST';
$cacheId = $name;
//$tmp = phpversion();
//var_dump($tmp);
//die();
$cached = new CacheAdapter($cfg['filesystem']);
$cached->save($name, $cacheId);

if($cached->test($cacheId)) {
  $name = $cached->load($cacheId);
}

$response = (new HtmlResponse('Hello, ' . $name . ' !'))
    ->withHeader('X-Developer', 'ScorpDev');
### Sending

$emitter = new ResponseSender();
$emitter->send($response);
