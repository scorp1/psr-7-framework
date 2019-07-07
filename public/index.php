<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Http\Action;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response\HtmlResponse;
use Framework\Http\ResponseSender;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Zend\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;
use Framework\Http\Router\Adapter\AuraRouterAdapter;

### Initialization

$aura = new Aura\Router\RouterContainer();
$routes = $aura->getMap();

$routes->get('hello', '/', Action\HelloAction::class);
$routes->get('about', '/about', Action\AboutAction::class);
$routes->get('blog', '/blog', Action\Blog\IndexAction::class);
$routes->get('blog_show', '/blog/{id}', Action\Blog\ShowAction::class)->tokens(['id' => '\d+']);

$router = new AuraRouterAdapter($aura);
$resolver = new \Framework\Http\ActionResolver();

### Running

$request = ServerRequestFactory::fromGlobals();

try{
    //получаем маршурут от AuraRouterAdapter
    $result = $router->match($request);
    foreach ($result->getAttributes() as $attribute => $value){
        $request = $request->withAttribute($attribute, $value);
    }
    ### Actions

    //Вызывает класс который привязан к данному маршуруту
    $action = $resolver->resolve($result->getHandler());

    //вызываем класс контроллер который получили в $action
    $response = $action($request);

} catch (RequestNotMatchedException $e) {
    $response = new JsonResponse(['error' => 'Unidefined page'], 404);
}

### PostProcessing

$response = $response->withHeader('X-Developer', 'ScorpDev');

### Sending

$emitter = new ResponseSender();
$emitter->send($response);