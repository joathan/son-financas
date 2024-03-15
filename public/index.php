<?php

use Psr\Http\Message\ServerRequestInterface;
use SONFin\Application;
use SONFin\Plugins\RoutePlugin;
use SONFin\Plugins\ViewPlugin;
use SONFin\ServiceContainer;

require_once __DIR__ . '/../vendor/autoload.php';

$serviceContainer = new ServiceContainer();
$app = new Application($serviceContainer);

$app->plugin(new RoutePlugin());
$app->plugin(new ViewPlugin());

$app->get('/',
    function (ServerRequestInterface $request) use ($app){
        $view = $app->service('view.renderer');
        return $view->render('teste.html.twig', ['name' => 'Joathan']);
    }
);

$app->get('/home/{nome}',
    function (ServerRequestInterface $request) {
        $response = new \Zend\Diactoros\Response();
        $response->getBody()->write('Response com emmiter do diactoros');
        return $response;
    }
);

$app->start();
