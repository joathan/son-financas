<?php

use Psr\Http\Message\ServerRequestInterface;
use SONFin\Application;
use SONFin\Plugins\RoutePlugin;
use SONFin\ServiceContainer;

require_once __DIR__ . '/../vendor/autoload.php';

$serviceContainer = new ServiceContainer();
$app = new Application($serviceContainer);

$app->plugin(new RoutePlugin());

$app->get('/',
    function (ServerRequestInterface $request) {
        echo "Home";
    }
);

$app->get('/home/{nome}',
    function (ServerRequestInterface $request) {
        echo "Mostrando a home";
        echo "<br>";
        echo $request->getAttribute('nome');
    }
);

$app->start();