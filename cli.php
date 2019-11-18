<?php

require_once __DIR__ . '/vendor/autoload.php';

if (!file_exists(__DIR__ . '/.env')) {
    throw new Exception('Env file does not exist.');
}
$argv = array_slice($argv, 1);
if (!$argv) {
    throw new Exception('Need to supply more arguments.');
}

if (!$argv[0]) {
    throw new Exception('Integration config does not exist');
}

$kernel = new \App\Utils\Kernel(
    __DIR__,
    __DIR__ . '/app/src/di.php'
);
$app = new \App\Utils\Application($kernel);
$di = $kernel->getDI();
$app->runCommand(array_shift($argv), $argv);
