<?php

use Dwm\MiniPress\application_core\application\usecases\CatalogueService;
use Dwm\MiniPress\webui\ArticleController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Views\Twig;

return function (App $app): App {

    $catalogueService  = new CatalogueService();
    $articleController = new ArticleController($catalogueService);

    //Accueil
    $app->get('/', function (Request $request, Response $response): Response {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'index.twig');
    });

    //Articles
    $app->get('/articles/creer', [$articleController, 'showForm']);
    $app->post('/articles/creer', [$articleController, 'traiterForm']);

    return $app;
};