<?php

namespace Dwm\MiniPress\webui\actions;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Dwm\MiniPress\application_core\application\usecases\DatabaseServiceInterface;
use Dwm\MiniPress\webui\provider\CsrfTokenProvider;
use Dwm\MiniPress\webui\provider\AuthProviderInterface;
use Slim\Views\Twig;
use Slim\Exception\HttpBadRequestException;

class CreateArticleAction
{
    public function __construct(
        private readonly DatabaseServiceInterface $db,
        private readonly AuthProviderInterface $auth
    ) {}

    public function __invoke(Request $request, Response $response): Response
    {
        if ($request->getMethod() === 'POST') {
            $parsedBody = $request->getParsedBody();

            if (!CsrfTokenProvider::validateToken($parsedBody['csrf_token'] ?? '')) {
                throw new HttpBadRequestException($request, "Jeton CSRF invalide");
            }

            $uploadedFiles = $request->getUploadedFiles();
            $imageFile = $uploadedFiles['image_file'] ?? null;

            $imagePath = null;

            if ($imageFile && $imageFile->getError() === UPLOAD_ERR_OK) {
                $imagePath = $this->db->stockerImageArticle($imageFile);
            }

            $userId = $this->auth->getUserId();
            $article = $this->db->creerArticle(
                $parsedBody['titre'],
                $parsedBody['resumer'] ?? null,
                $parsedBody['contenue'] ?? null,
                (int)$parsedBody['categorie'],
                $imagePath,
                $userId
            );

            return $response->withHeader('Location', "/article/" . $article->id)->withStatus(302);
        }

        $view = Twig::fromRequest($request);
        return $view->render($response, 'CreateArticleView.html', [
            'categories' => $this->db->getCategories(),
            'csrf_token' => CsrfTokenProvider::generateToken()
        ]);
    }
}