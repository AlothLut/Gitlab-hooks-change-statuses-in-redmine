<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$app->addErrorMiddleware(true, false, false);

$app->post('/set-redmine-status/', function (Request $request, Response $response) {
    Controllers\ChangeRedmineStatus::init();
    return $response;
});

$app->run();
