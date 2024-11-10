<?php
require __DIR__ . '/../vendor/autoload.php';

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\DBAL\DriverManager;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$isDevMode = true;
$proxyDir = null;
$cache = null;
$useSimpleAnnotationReader = false;

if ($isDevMode) {
    $cache = new ArrayAdapter();
} else {
    $cache = new FilesystemAdapter(
        $namespace = '',
        $defaultLifetime = 0,
        $directory = __DIR__ . '/../var/cache'
    );
}

$config = ORMSetup::createAttributeMetadataConfiguration(
    [__DIR__ . "/src/Models"],
    $isDevMode,
    $proxyDir,
    $cache,
    $useSimpleAnnotationReader
);

$connectionParams = [
    'driver' => 'pdo_mysql',
    'host' => $_ENV['DB_HOST'],
    'dbname' => $_ENV['DB_NAME'],
    'user' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASSWORD'],
];

try {
    $connection = DriverManager::getConnection($connectionParams);
    $connection->connect();
} catch (\Exception $e) {
    echo "Failed to connect to the database: " . $e->getMessage() . "\n";
    exit(1);
}

try {
    $entityManager = new EntityManager($connection, $config);
} catch (\Exception $e) {
    echo "Failed to create EntityManager: " . $e->getMessage() . "\n";
    exit(1);
}