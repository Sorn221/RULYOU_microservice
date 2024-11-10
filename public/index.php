<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../app/bootstrap.php';

use Slim\Factory\AppFactory;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpMethodNotAllowedException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app = AppFactory::create();

$container = $app->getContainer();
if ($container === null) {
    $container = new DI\Container();
    AppFactory::setContainer($container);
}

$container->set('entityManager', function () {
    global $entityManager;
    return $entityManager;
});

$routes = require __DIR__ . '/../app/routes.php';
$routes($app);

$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler(function (
    Request $request,
    Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails
) use ($app) {
    $response = $app->getResponseFactory()->createResponse();
    $response->getBody()->write(json_encode([
        'success' => false,
        'error' => [
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ]
    ]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
});

$app->run();