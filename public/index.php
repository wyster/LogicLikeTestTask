<?php declare(strict_types=1);

chdir(dirname(__DIR__ . 'logiclike'));

require 'vendor/autoload.php';

$configPath = 'config.local.php';
if (!file_exists($configPath)) {
    $configPath = 'config.example.php';
}
$config = require $configPath;

$logger = new \App\Logger();
$storage =\Zend\Cache\StorageFactory::factory($config['cache']);
$userStorage = new \App\UserStorage($storage);
$protector = new App\Protector($userStorage, $config, $logger);

if ($protector->isAllowAccess()) {
    if (PHP_SAPI === 'cli') {
        return $logger->info('Hello World!');
    }

    echo 'Hello World!';
    return;
}

http_response_code(403);
header('X-Ban-Will-Expire: ' . $protector->banExpireAt()->format('Y-m-d H:i:s'));

if (PHP_SAPI === 'cli') {
    $logger->info('Access denied!');
}