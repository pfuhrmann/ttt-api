<?php declare(strict_types=1);

require '../vendor/autoload.php';

$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];
$c = new \Slim\Container($configuration);
$app = new \Slim\App($c);
$app->get('/init', 'DH\TttApi\Controllers\GameController:init');
$app->post('/move', 'DH\TttApi\Controllers\GameController:move');
$app->run();
