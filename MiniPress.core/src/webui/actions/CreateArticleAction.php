<?php

namespace Dwm\MiniPress\webui\actions;
use Dwm\MiniPress\webui\provider\CsrfTokenProvider;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Dwm\MiniPress\application_core\application\usecases\DatabaseServiceInterface;
use Dwm\MiniPress\webui\provider\AuthProviderInterface;

class CreateArticleAction
{
    public function __construct(private readonly DatabaseServiceInterface $db, private readonly AuthProviderInterface $auth){}

    public function __invoke(Request $request, Response $response, array $args)
    {   
        if($this->auth::isAuthenticated()){
            if ($request->getMethod() === 'POST') {
            $parsedBody = $request->getParsedBody();

            // Vérification du token CSRF
            if (!CsrfTokenProvider::validateToken($parsedBody['csrf_token'] ?? '')) {
                throw new HttpBadRequestException($request, "Invalid CSRF token");
            }

            try {
                $article = $this->db::creerArticle(
                    $parsedBody['titre'],
                    $parsedBody['resumer'] ?? null,
                    $parsedBody['contenue'] ?? null,
                    (int)$parsedBody['categorie'],
                    $parsedBody['url_image'] ?? null,
                    $this->auth::getUserId()
                );
                return $response->withHeader('Location', "/article/$article->id")->withStatus(302);
            } catch (\Exception $e) {
                throw new HttpInternalServerErrorException($request, "Failed to create article: " . $e->getMessage());
            }
        }

        if ($request->getMethod() === 'GET') {
            $csrfToken = CsrfTokenProvider::generateToken();
            $view = Twig::fromRequest($request);
            
            return $view->render($response, 'CreateArticleView.html', [
                'csrf_token' => $csrfToken,
                'categories' => $this->db->getCategories()
            ]);  
        }
        
            throw new HttpBadRequestException($request, "Unsupported HTTP method");
        }
        return $response
            ->withHeader('Location', '/login')
            ->withStatus(302);
    }
}
?>