<?php

use Psr\Http\Message\ServerRequestInterface;
use SONFin\Application;
use SONFin\Models\CategoryCost;
use SONFin\Plugins\RoutePlugin;
use SONFin\Plugins\ViewPlugin;
use SONFin\Plugins\DbPlugin;
use SONFin\ServiceContainer;
use Zend\Diactoros\Response\RedirectResponse;

require_once __DIR__ . '/../vendor/autoload.php';

$serviceContainer = new ServiceContainer();
$app = new Application($serviceContainer);

$app->plugin(new RoutePlugin());
$app->plugin(new ViewPlugin());
$app->plugin(new DbPlugin());

$app->get('/home/{nome}',
    function (ServerRequestInterface $request) {
        $response = new \Zend\Diactoros\Response();
        $response->getBody()->write('Response com emmiter do diactoros');
        return $response;
    }
);

$app
    ->get('/category-costs', function () use ($app){
        $view = $app->service('view.renderer');
        $category = new CategoryCost();
        $categories = $category->all();
        return $view->render('category-costs/list.html.twig', ['categories' => $categories]);
})
    ->get('/category-costs/new', function () use ($app){
        $view = $app->service('view.renderer');
        return $view->render('category-costs/create.html.twig');
})
    ->post('/category-costs/store', function (ServerRequestInterface $request) use ($app){
        $data = $request->getParsedBody();
        $category = new CategoryCost();
        $category->create($data);
        return new RedirectResponse('/category-costs');
});

$app->start();
