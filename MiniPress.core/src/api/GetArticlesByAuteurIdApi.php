<?php

namespace Dwm\MiniPress\api;

use Dwm\MiniPress\application_core\application\usecases\DatabaseServiceInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Slim\Interfaces\RouteParserInterface;

class GetArticlesByAuteurIdApi
{

    public function __construct(
        private readonly DatabaseServiceInterface $catalogueService,
        private readonly RouteParserInterface $routeParser
    ) {}

    public function __invoke(Request $request, Response $response): Response
    {
        $id_auteur = $request->getAttribute('id');

        $articles = $this->catalogueService->getArticlesByIdAuteur($id_auteur);

        // titre, date, auteur, url
        $result = array_map(function($article) {
            return [
                'titre' => $article['Titre'],
                'date' => $article['Date'],
                'auteur' => $article['ID-Auteur'],
                // récupérer l'url de l'article avec route
                'url' => $this->routeParser->urlFor('article_info_api', ['id' => $article['ID']])
            ];
        }, $articles);

        $payload = json_encode($result);
        
        $response->getBody()->write($payload);
        
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}