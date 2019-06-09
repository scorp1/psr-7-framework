<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config.php';

use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response\HtmlResponse;
use Framework\Http\ResponseSender;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Cache\CacheAdapter;
use Framework\Counting\Counting;
use Zend\Diactoros\Response\JsonResponse;
use Framework\Import\ImportShops;
use Zend\Cache\Psr\CacheItemPool\CacheItemPoolDecorator;
use Framework\Http\Router\RouterCollection;
use Psr\Http\Message\ServerRequestInterface;

### Initialization

$routes = new RouterCollection();

### Routes
$routes->get('home', '/', function (ServerRequestInterface $request) {
        $name = isset($request->getQueryParams()['name']) && !empty($request->getQueryParams()['name']) ? $request->getQueryParams()['name'] : 'GUEST';
        return new HtmlResponse('Hello ' . $name);
    });

$routes->get('about', '/about', function (){
    return new HtmlResponse('Simple site page!');
});

$routes->get('blog', '/blog', function (){
    return new JsonResponse([
       ['id' => 2, 'title' => 'The Second Post'],
        ['id' => 1, 'title' => 'The First Post']
    ]);
});

$routes->get('wallpapers', '/wallpapers/{l}/{h}/', function (ServerRequestInterface $request) {
    $length = $request->getAttribute('l');
    $height = $request->getAttribute('h');
    $type = isset($request->getQueryParams()['type']) && !empty($request->getQueryParams()['type']) ? $request->getQueryParams()['type'] : 0.5;

    if (empty($length) || empty($height)){
        return new JsonResponse(['error' => 'Empty length or height.'], 404);
    }
    $resultRoll = new Counting($height, $length, $type);
    $countWallpapers = $resultRoll->getCountRoll();
    return new JsonResponse(['count wallpapers rullons' => $countWallpapers]);
});

$routes->get('blog_show', '/blog/{id}', function (ServerRequestInterface $request){
    $id = $request->getAttribute('id');
    if ($id > 5) {
        return new JsonResponse(['error' => 'Unidefined page'], 404);
    }
    return new JsonResponse(['id' => $id, 'title' => 'Post # '. $id]);
}, ['id' => '\d+']);

$routersAll = $routes->getRouters();

$router = new \Framework\Http\Router\Router($routes);

### Running

$request = ServerRequestFactory::fromGlobals();

try{
    $result = $router->match($request);
    foreach ($result->getAttributes() as $attribute => $value){
        $request = $request->withAttribute($attribute, $value);
    }
    //возвращает анонимную функцию которая привязана к этому маршруту
    $action = $result->getHandler();
    //запускаем нашу анонимную функцию с примешиными к ней атрибутами
    //и обработчик который из request извлекает id будет работать
    $response = $action($request);

} catch (RequestNotMatchedException $e) {
    $response = new JsonResponse(['error' => 'Unidefined page'], 404);
}
/*
### Actions

$name = isset($request->getQueryParams()['name']) && !empty($request->getQueryParams()['name']) ? $request->getQueryParams()['name'] : 'GUEST';
$cacheId = $name;

$length = $request->getQueryParams()['length'];
$height = $request->getQueryParams()['height'];
$type = isset($request->getQueryParams()['type']) && !empty($request->getQueryParams()['type']) ? $request->getQueryParams()['type'] : 0.5;

$resultRoll = new \Framework\Counting\Counting($height, $length, $type);
//$tmp = phpversion();
//var_dump($tmp);
//die();
$cached = new CacheAdapter($cfg['filesystem']);
$cached->save($name, $cacheId);

$storage = \Zend\Cache\StorageFactory::factory([
    'adapter' => [
        'name'    => 'apc',
        'options' => [],
    ],
]);
$pool = new CacheItemPoolDecorator($storage);


if($cached->test($cacheId)) {
  $name = $cached->load($cacheId);
}
*/
### PostProcessing

$response = $response
    ->withHeader('X-Developer', 'ScorpDev');

### Sending



$emitter = new ResponseSender();
$emitter->send($response);

