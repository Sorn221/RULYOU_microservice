<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $container = $app->getContainer();


    $app->group('/users', function (Group $group) use ($container) {
        $group->post('/create', function (Request $request, Response $response, array $args) use ($container) {
            $controller = new \App\Controllers\UserController($container->get('entityManager'));
            return $controller->create($request, $response, $args);
        });

        $group->get('/get[/{id}]', function (Request $request, Response $response, array $args) use ($container) {
            $controller = new \App\Controllers\UserController($container->get('entityManager'));
            return $controller->get($request, $response, $args);
        });

        $group->patch('/update/{id}', function (Request $request, Response $response, array $args) use ($container) {
            $controller = new \App\Controllers\UserController($container->get('entityManager'));
            return $controller->update($request, $response, $args);
        });

        $group->delete('/delete[/{id}]', function (Request $request, Response $response, array $args) use ($container) {
            $controller = new \App\Controllers\UserController($container->get('entityManager'));
            return $controller->delete($request, $response, $args);
        });
    });
};