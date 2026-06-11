<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Views\Twig;

//WebUI
use Dwm\MiniPress\webui\ArticleController;

// API
use Dwm\MiniPress\api\GetCategoriesApi;
use Dwm\MiniPress\api\GetArticlesApi;
use Dwm\MiniPress\api\GetArticleInfoApi;
use Dwm\MiniPress\api\GetArticlesFromCategoriesApi;
use Dwm\MiniPress\api\GetArticlesByAuteurIdApi;

return function (App $app): App {

    //Accueil
    $app->get('/', function (Request $request, Response $response): Response {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'index.twig');
    });

    //Articles
    //GET
    $app->get('/articles/creer', ArticleController::class)->setName('showForm');
    //POST
    $app->post('/articles/creer', ArticleController::class)->setName('traiterForm');

    //API
    $app->get('/api/categories', GetCategoriesApi::class);
    $app->get('/api/articles', GetArticlesApi::class);
    $app->get('/api/articles/{id_a}', GetArticleInfoApi::class)->setName('article_info_api');
    $app->get('/api/categories/{id_categ}/articles', GetArticlesFromCategoriesApi::class);
    $app->get('/api/auteurs/{id}/articles', GetArticlesByAuteurIdApi::class);

    return $app;
};