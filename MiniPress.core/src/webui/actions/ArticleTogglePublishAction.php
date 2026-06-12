<?php

namespace Dwm\MiniPress\webui\actions;

use Dwm\MiniPress\application_core\application\usecases\DatabaseServiceInterface;
use Dwm\MiniPress\webui\provider\AuthnProvider;
use Dwm\MiniPress\webui\provider\CsrfTokenProvider;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpForbiddenException;

class ArticleTogglePublishAction
{
    public function __construct(private readonly DatabaseServiceInterface $db){}

    public function __invoke(Request $request, Response $response, array $args)
    {
        $parsedBody = $request->getParsedBody();

        if (!CsrfTokenProvider::validateToken($parsedBody['csrf_token'] ?? '')) {
            throw new HttpBadRequestException($request, "Jeton CSRF invalide");
        }

        $article = $this->db->getArticleById($args['id']);
        $currentUserId = AuthnProvider::getUserId();

        if ($currentUserId === null || $currentUserId !== $article['id_auteur']) {
            throw new HttpForbiddenException($request, "Vous n'êtes pas autorisé à modifier cet article.");
        }

        $nouvelEtat = ($article['published'] == 1) ? 0 : 1;
        $this->db->updatePublishStatus($article['id'], $nouvelEtat);

        return $response->withHeader('Location', "/article/" . $article['id'])->withStatus(302);
    }
}