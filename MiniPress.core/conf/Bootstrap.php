<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as DB;
use Slim\Factory\AppFactory;
use Slim\Interfaces\RouteParserInterface;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Dwm\MiniPress\application_core\application\usecases\CatalogueServiceInterface;
use Dwm\MiniPress\application_core\application\usecases\CatalogueService;

// --- Base de données ----------------------------------------------------------
$config = parse_ini_file(__DIR__ . '/confdb.ini');
if ($config !== false) {
    $db = new DB();
    $db->addConnection($config);
    $db->setAsGlobal();
    $db->bootEloquent();
}

// --- Conteneur DI -------------------------------------------------------------
$container = new Container();
$container->bind(CatalogueServiceInterface::class, CatalogueService::class);

// --- Application --------------------------------------------------------------
AppFactory::setContainer($container);
$app = AppFactory::create();

$container->bind(RouteParserInterface::class, fn() => $app->getRouteCollector()->getRouteParser());

$twig = Twig::create(__DIR__ . '/../src/webui/views', ['cache' => false]);
$app->add(TwigMiddleware::create($app, $twig));

// --- Middleware -------------------------------------------------------------------
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);

// --- Routes ------------------------------------------------------------------------
$routes = require __DIR__ . '/Routes.php';
$app = $routes($app);

return $app;