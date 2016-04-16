<?php

require __DIR__ . '/../vendor/autoload.php';

session_start();

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
	$routes = include('Routes.php');
	foreach ($routes as $route) {
		$r->addRoute($route[0], $route[1], $route[2]);
	}
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
		$error = new \Controller\Error(404, 'Resource not found');
		$error->render();

		break;
	case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
		$allowedMethods = $routeInfo[1];
		$error = new \Controller\Error(405, 'Access not allowed');
		$error->render();

		break;
	case FastRoute\Dispatcher::FOUND:
		$className = $routeInfo[1][0];
		$method = $routeInfo[1][1];
		$vars = $routeInfo[2];


		$class = new $className;
		$class->$method($vars);
		break;
}
