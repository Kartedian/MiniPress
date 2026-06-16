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
    'id' => $article['id'] ?? null,
    'titre' => $article['titre'] ?? null,
    'resumer' => $article['resumer'] ?? null,
    'contenue' => $article['contenue'] ?? null,
    'date' => $article['date'] ?? null, 
    'categorie' => $article['categorie'] ?? null,
    'url_image' => $article['url_image'] ?? null,
    'auteur' => [
        'id' => $author['id'] ?? null,
        'user_id' => $author['user_id'] ?? null,
        'name' => $author['name'] ?? 'Inconnu'
    ],
    'published' => $article['published'] ?? null
]);
        
        $response->getBody()->write($payload);
        
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}