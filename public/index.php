<?php

use Psr\Http\Message\ServerRequestInterface;
use SONFin\Application;
use SONFin\Plugins\AuthPlugin;
use SONFin\Plugins\RoutePlugin;
use SONFin\Plugins\ViewPlugin;
use SONFin\Plugins\DbPlugin;
use SONFin\ServiceContainer;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/helpers.php';

$serviceContainer = new ServiceContainer();
$app = new Application($serviceContainer);

$app->plugin(new RoutePlugin());
$app->plugin(new ViewPlugin());
$app->plugin(new DbPlugin());
$app->plugin(new AuthPlugin());

$app->get(
    '/home/{nome}',
    function (ServerRequestInterface $request) {
        $response = new \Zend\Diactoros\Response();
        $response->getBody()->write('Response com emmiter do diactoros');
        return $response;
    }
);

$app->post(
    '/set-locale',
    function (ServerRequestInterface $request) use ($app) {
        session_start();
        $_SESSION['locale'] = $_POST['locale'] ?? 'pt_BR';

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
);

$app->get('/images/{filename}', function ($request) {
    $response = new \Zend\Diactoros\Response();
    $filename = $request->getAttribute('filename'); // Extrai o filename da URL
    $file = __DIR__ . '/../public/images/' . basename($filename); // Uso de basename() para segurança

    if (file_exists($file) && is_file($file)) {
        $fileType = mime_content_type($file);
        $imageContent = file_get_contents($file);
        $response->getBody()->write($imageContent);
        return $response->withHeader('Content-Type', $fileType);
    } else {
        return $response->withStatus(404, 'Image not found');
    }
});

$app->get('/', function () use ($app) {
    $view = $app->service('view.renderer');
    return $view->render('dashboard.html.twig');
}, 'home');

require_once __DIR__ .'./../src/Controllers/category-costs.php';
require_once __DIR__ .'./../src/Controllers/bill-receives.php';
require_once __DIR__ .'./../src/Controllers/bill-pays.php';
require_once __DIR__ .'./../src/Controllers/users.php';
require_once __DIR__ .'./../src/Controllers/auth.php';

$app->start();
