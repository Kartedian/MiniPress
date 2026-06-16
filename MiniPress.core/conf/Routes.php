<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Views\Twig;
use Slim\Exception\HttpNotFoundException;

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
use Dwm\MiniPress\webui\actions\LoginAction;
use Dwm\MiniPress\webui\actions\RegisterAction;
use Dwm\MiniPress\webui\actions\LogoutAction;

return function (App $app): App {

    //Accueil
    $app->get('/', AccueilAction::class)->setName('accueil');

    //Login
    $app->post('/login[/]', LoginAction::class);
    $app->get('/login[/]', LoginAction::class)->setName('login');

    //Register
    $app->post('/register[/]', RegisterAction::class);
    $app->get('/register[/]', RegisterAction::class)->setName('register');

    //Logout
    $app->get('/logout', LogoutAction::class)->setName('logout');

    //Article
    //Post
    $app->post('/article/create[/]', CreateArticleAction::class);

    //Get
    $app->get('/articles[/]', ArticleListeAction::class)->setName('articles');
    $app->get('/article/create[/]', CreateArticleAction::class)->setName('article_create');

    $app->post('/article/{id}/toggle-publish', ArticleTogglePublishAction::class)->setName('article_toggle_publish');
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

    $app->options('/{routes:.+}', function (Request $request, Response $response, array $args) {
        return $response;
    });
    
    $app->add(function (Request $request, $handler) {
        $response = $handler->handle($request);
        return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    });


    // Route pour afficher les images depuis le dossier uploads
    $app->get('/images/{dir1}/{dir2}/{filename}', function (Request $request, Response $response, array $args) {
        $filePath = __DIR__ . '/../images/' . $args['dir1'] . '/' . $args['dir2'] . '/' . $args['filename'];

        if (!file_exists($filePath)) {
            throw new HttpNotFoundException($request, "Image introuvable");
        }

        $ext = pathinfo($filePath, PATHINFO_EXTENSION);
        $contentType = match($ext) {
            'png' => 'image/png',
            'webp' => 'image/webp',
            default => 'image/jpeg',
        };

        $response->getBody()->write(file_get_contents($filePath));

        return $response
            ->withHeader('Content-Type', $contentType)
            ->withStatus(200);
    });

    
    return $app;
};
