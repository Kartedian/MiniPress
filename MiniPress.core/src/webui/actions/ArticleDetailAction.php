<?php

namespace Dwm\MiniPress\webui\actions;

use Dwm\MiniPress\application_core\application\usecases\DatabaseServiceInterface;
use Dwm\MiniPress\application_core\domain\exceptions\DatabaseException;
use Dwm\MiniPress\webui\provider\AuthnProvider;
use Dwm\MiniPress\webui\provider\CsrfTokenProvider;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Views\Twig;

class ArticleDetailAction
{
    public function __construct(private readonly DatabaseServiceInterface $db) {}

    public function __invoke(Request $request, Response $response, array $args)
    {
        try{
            $article = $this->db->getArticleById($args['id']);
        } catch (DatabaseException $e) {
            throw new HttpInternalServerErrorException($request, $e->getMessage());
        }    

        $currentUserId = AuthnProvider::getUserId();

        $isOwner = ($currentUserId !== null && $currentUserId === $article['id_auteur']);

        $csrfToken = CsrfTokenProvider::generateToken();
        $view = Twig::fromRequest($request);
        return $view->render($response, 'ArticleDetailView.html', [
            'article' => $article,
            'is_owner' => $isOwner,
            'csrf_token' => $csrfToken
        ]);
    }
}
