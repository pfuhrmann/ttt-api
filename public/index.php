<?php declare(strict_types=1);

require '../vendor/autoload.php';

$app = new \Slim\App;
$app->get('/init', 'DH\TttApi\Controllers\GameController:init');
$app->post('/move', 'DH\TttApi\Controllers\GameController:move');
$app->run();
