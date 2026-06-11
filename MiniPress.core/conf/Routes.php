<?php

use Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// API
use Dwm\MiniPress\api\GetCategoriesApi;
use Dwm\MiniPress\api\GetArticlesApi;
use Dwm\MiniPress\api\GetArticleInfoApi;
use Dwm\MiniPress\api\GetArticlesFromCategoriesApi;
use Dwm\MiniPress\api\GetArticlesByAuteurIdApi;

return function (App $app): App{
    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write("<h1>Welcome to MiniPress</h1>");
        return $response;
    });

    // --- API routes ---
    $app->get('/api/categories', GetCategoriesApi::class);
    $app->get('/api/articles', GetArticlesApi::class);
    $app->get('/api/articles/{id_a}', GetArticleInfoApi::class)->setName('article_info_api');
    $app->get('/api/categories/{id_categ}/articles', GetArticlesFromCategoriesApi::class);
    $app->get('/api/auteurs/{id}/articles', GetArticlesByAuteurIdApi::class);


    return $app;
};