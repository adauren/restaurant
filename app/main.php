<?php

use Twig\Environment;
use DI\ContainerBuilder;
use FastRoute\RouteCollector;
use Nette\Database\Connection;

$containerBuilder = new ContainerBuilder;

$containerBuilder->addDefinitions([
  Connection::class => function () {
    return new Connection("mysql:host=localhost;dbname=pontos", "root", "");
  }, 
  Environment::class => function () {
    $loader = new Twig\Loader\FilesystemLoader(__DIR__ . '\views');
    $twig = new Environment($loader);

    return $twig;
  }
]);

$container = $containerBuilder->build();

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
  $r->addRoute('GET', '/', ['App\controllers\HomeController', 'index']);
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
  $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
  case FastRoute\Dispatcher::NOT_FOUND:
    echo "Not Found";
    break;
  case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
    $allowedMethods = $routeInfo[1];
    echo "Not Found";
    break;
  case FastRoute\Dispatcher::FOUND:
    $handler = $routeInfo[1];
    $vars = $routeInfo[2];

    $container->call($handler, $vars);
    break;
}
