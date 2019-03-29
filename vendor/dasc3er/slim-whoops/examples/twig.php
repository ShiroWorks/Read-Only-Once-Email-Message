<?php

require_once __DIR__.'/../vendor/autoload.php';

use Slim\App;
use \Dasc3er\Slim\Whoops\WhoopsMiddleware;

$app = new App([
    'settings' => [
        'displayErrorDetails' => true,      // Display call stack in orignal slim error when debug is off
    ],
]);

$app->add(new WhoopsMiddleware($app->getContainer(), 'sublime'));

$container = $app->getContainer();
$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig('./views', [
        'debug' => true,
        'cache' => './cache/views',
    ]);

    $view->addExtension(new Twig_Extension_Debug());

    return $view;
};

// Work
// $app->get('/', function($request, $response, $args) use ($app) {
//     return $this->view->render($response, 'test.html', [
//         'name' => "Tester"
//     ]);
// });

// Exception
$app->get('/', function ($request, $response, $args) use ($app) {
    return $this->view->render($response, 'noExists.html');
});

$app->run();
