<?php declare(strict_types=1);

require 'vendor/autoload.php';

$configPath = 'config.local.php';
if (!file_exists($configPath)) {
    $configPath = 'config.example.php';
}
$config = require $configPath;

$logger = new \App\Logger();
$storage = \Zend\Cache\StorageFactory::factory($config['cache']);
if ($storage instanceof \Zend\Cache\Storage\FlushableInterface) {
    $logger->info('Storage flushed: ' . $storage->flush());
}