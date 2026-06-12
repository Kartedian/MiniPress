<?php

namespace Dwm\MiniPress\api;

use Dwm\MiniPress\application_core\application\usecases\DatabaseServiceInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class GetArticleInfoApi
{

    public function __construct(private readonly DatabaseServiceInterface $catalogueService) {}

    public function __invoke(Request $request, Response $response): Response
    {
        $id_a = $request->getAttribute('id_a');

        $article = $this->catalogueService::getArticleById($id_a);

        $author = $this->catalogueService::getAuthorById($article['id_auteur']);

        $payload = json_encode([
            'id' => $article['id'],
            'titre' => $article['titre'],
            'resumer' => $article['resumer'],
            'contenue' => $article['contenue'],
            'date' => $article['date'],
            'categorie' => $article['categorie'],
            'url_image' => $article['url_image'],
            'auteur' => [
                'id' => $author['id'],
                'user_id' => $author['user_id'],
                'name' => $author['name']
            ],
            'published' => $article['published']
        ]);
        
        $response->getBody()->write($payload);
        
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}