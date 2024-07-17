<?php
// Display errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include Composer's autoload file
require __DIR__ . '/vendor/autoload.php';

// Use necessary Slim classes
use Slim\Factory\AppFactory;
use Slim\Middleware\ErrorMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Middleware\AuthMiddleware;

// Start a session
session_start();

// Create the Slim app
$app = AppFactory::create();

// Add CORS middleware to allow cross-origin requests
$app->add(function (Request $request, $handler): Response {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

// Add error handling middleware
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Register authentication middleware
$app->add(new AuthMiddleware());

// Define the root route to serve the home page
$app->get('/', function (Request $request, Response $response) {
    $filePath = 'C:/xampp/htdocs/room-booking-app/frontend/user/index.html';
    if (file_exists($filePath)) {
        $response->getBody()->write(file_get_contents($filePath));
        return $response->withHeader('Content-Type', 'text/html');
    } else {
        $response->getBody()->write('Home page not found');
        return $response->withStatus(404);
    }
});

// Serve static files from the frontend directory
$app->get('/frontend/{file:.+}', function (Request $request, Response $response, array $args) {
    $filePath = 'C:/xampp/htdocs/room-booking-app/frontend/user/' . $args['file'];
    if (file_exists($filePath)) {
        $stream = new Slim\Psr7\Stream(fopen($filePath, 'r'));
        return $response->withBody($stream)->withHeader('Content-Type', mime_content_type($filePath));
    } else {
        $response->getBody()->write('File not found');
        return $response->withStatus(404);
    }
});

// Register API routes
(require __DIR__ . '/backend/src/routes/api.php')($app);

// Run the app
$app->run();
