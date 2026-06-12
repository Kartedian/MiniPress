<?php

namespace Dwm\MiniPress\webui\actions;

use Dwm\MiniPress\webui\provider\CsrfTokenProvider;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Dwm\MiniPress\application_core\application\usecases\DatabaseServiceInterface;
use Dwm\MiniPress\webui\provider\AuthnProvider;

class CreateCategorieAction
{
    private $db;

    public function __construct(DatabaseServiceInterface $db)
    {
        $this->db = $db;
    }

    public function __invoke(Request $request, Response $response, array $args)
    {
        if ($request->getMethod() === 'POST') {
            $parsedBody = $request->getParsedBody();

            // Vérification du token CSRF
            if (!CsrfTokenProvider::validateToken($parsedBody['csrf_token'] ?? '')) {
                throw new HttpBadRequestException($request, "Invalid CSRF token");
            }

            try {
                $categorie = $this->db->creerCategorie(
                    $parsedBody['titre'],
                    $parsedBody['description'] ?? null
                );
                return $response->withHeader('Location', "/categorie/$categorie->id/")->withStatus(302);
            } catch (\Exception $e) {
                throw new HttpInternalServerErrorException($request, "Failed to create article: " . $e->getMessage());
            }
        }

        if ($request->getMethod() === 'GET') {
            $csrfToken = CsrfTokenProvider::generateToken();
            $view = Twig::fromRequest($request);
            return $view->render($response, 'CreateCategorieView.html', [
                'csrf_token' => $csrfToken
            ]);
        }

        throw new HttpBadRequestException($request, "Unsupported HTTP method");
    }
}
