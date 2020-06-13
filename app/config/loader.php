<?php

$loader = new \Phalcon\Loader();
$loader->registerDirs([
    APP_PATH . '/tasks',
    APP_PATH . '/models',
]);

$loader->registerNamespaces([
    'Service' => APP_PATH . "/service",
], true);


$loader->register();
