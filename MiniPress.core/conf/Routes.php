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
use Dwm\MiniPress\webui\actions\AccueilAction;
use Dwm\MiniPress\webui\actions\ArticleDetailAction;
use Dwm\MiniPress\webui\actions\ArticleListeAction;
use Dwm\MiniPress\webui\actions\ArticleTogglePublishAction;
use Dwm\MiniPress\webui\actions\CategorieListeAction;
use Dwm\MiniPress\webui\actions\CreateArticleAction;
use Dwm\MiniPress\webui\actions\CreateCategorieAction;
use Dwm\MiniPress\webui\actions\CategorieDetailAction;

return function (App $app): App {

    //Accueil
    $app->get('/', AccueilAction::class)->setName('accueil');

    //Article
    //Post
    $app->post('/article/create[/]', CreateArticleAction::class);

    //Get
    $app->get('/articles[/]', ArticleListeAction::class)->setName('articles');
    $app->get('/article/create[/]', CreateArticleAction::class)->setName('article_create');

    $app->post('/article/{id:\d+}/toggle-publish', ArticleTogglePublishAction::class)->setName('article_toggle_publish');
    $app->get('/article/{id}[/]', ArticleDetailAction::class)->setName('article_detail');

    //Categorie
    //Post
    $app->post('/categorie/create[/]', CreateCategorieAction::class);
    //Get
    $app->get('/categories[/]', CategorieListeAction::class)->setName('categories');
    $app->get('/categorie/create[/]', CreateCategorieAction::class)->setName('categorie_create');

    $app->get('/categorie/{id:\d+}[/]', CategorieDetailAction::class)->setName('categorie_detail');


    //API
    $app->get('/api/categories', GetCategoriesApi::class);
    $app->get('/api/articles', GetArticlesApi::class);
    $app->get('/api/articles/{id_a}', GetArticleInfoApi::class)->setName('article_info_api');
    $app->get('/api/categories/{id_categ}/articles', GetArticlesFromCategoriesApi::class);
    $app->get('/api/auteurs/{id}/articles', GetArticlesByAuteurIdApi::class);

    return $app;
};