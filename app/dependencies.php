<?php
use Psr\Container\ContainerInterface;
use Slim\Factory\AppFactory;

$container = new DI\Container();
AppFactory::setContainer($container);

$container->set('entityManager', function (ContainerInterface $c) {
    global $entityManager;
    return $entityManager;
});