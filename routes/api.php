<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../logger.php';

global $pdo;
$user = new User($pdo);
$userController = new UserController($user);

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) use ($userController) {
    $r->addRoute('POST', '/users/create', [$userController, 'create']);
    $r->addRoute('GET', '/users/get[/{id:\d+}]', [$userController, 'get']);
    $r->addRoute('PATCH', '/users/update/{id:\d+}', [$userController, 'update']);
    $r->addRoute('DELETE', '/users/delete[/{id:\d+}]', [$userController, 'delete']);
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

try {
    $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
    switch ($routeInfo[0]) {
        case FastRoute\Dispatcher::FOUND:
            $handler = $routeInfo[1];
            $vars = $routeInfo[2];
            echo $handler($vars);
            break;
        default:
            http_response_code(404);
            echo json_encode(['success' => false, 'result' => ['error' => 'Not found']]);
            logError('Route not found: ' . $uri);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'result' => ['error' => $e->getMessage()]]);
    logError('Internal server error: ' . $e->getMessage());
}