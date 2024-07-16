<?php
require __DIR__ . '/vendor/autoload.php';

use Slim\Factory\AppFactory;
use Slim\Middleware\ErrorMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Middleware\AuthMiddleware;

// Start a session
session_start();

// Create Slim app
$app = AppFactory::create();

// Add CORS middleware
$app->add(function (Request $request, $handler): Response {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Register Middleware
$app->add(new AuthMiddleware());

// Register routes
(require __DIR__ . '/backend/src/routes/api.php')($app);

// Run app
$app->run();
