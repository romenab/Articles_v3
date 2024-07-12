<?php
namespace Articles;
require_once 'vendor/autoload.php';

use Articles\Controller\CommentController;
use Articles\Controller\ViewController;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use Articles\Controller\ArticleController;
use Articles\Database\Sqlite;
use Psr\Log\LoggerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use function FastRoute\simpleDispatcher;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use DI;

$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader);

$container = new DI\Container();

$container->set(ArticleController::class, function ($container) {
    return new ArticleController($container->get(Sqlite::class));
});
$container->set(CommentController::class, function ($container) {
    return new CommentController($container->get(Sqlite::class));
});
$container->set(ViewController::class, function ($container) {
    return new ViewController($container->get(Sqlite::class));
});
$container->set(LoggerInterface::class,(new Logger('app'))->pushHandler(
    new StreamHandler('storage/logs/app.log', Logger::INFO)));


$dispatcher = simpleDispatcher(function (RouteCollector $r) {
    $routes = include('routes.php');
    foreach ($routes as $route) {
        [$method, $url, $controller] = $route;
        $r->addRoute($method, $url, $controller);
    }
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case Dispatcher::NOT_FOUND:
        http_response_code(404);
        break;
    case Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        break;
    case Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        [$controller, $method] = $handler;
        $response = ($container->get($controller))->$method(...array_values($vars));
        if ($response instanceof Response)
        {
            echo $twig->render($response->getTemplate() . '.html', $response->getData());
        }

        if ($response instanceof RedirectResponse)
        {
            header('Location: ' . $response->getLocation());
        }
        break;
}