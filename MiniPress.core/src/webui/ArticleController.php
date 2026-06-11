<?php

namespace Dwm\MiniPress\webui;

use Dwm\MiniPress\application_core\application\usecases\CatalogueServiceInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class ArticleController
{
    public function __construct(private CatalogueServiceInterface $catalogueService) {}

    public function showForm(Request $request, Response $response): Response
    {
        $view = Twig::fromRequest($request);
        $categories = $this->catalogueService->listerCategories();
        $succes = isset($request->getQueryParams()['succes']);

        return $view->render($response, 'createArticle.twig', [
            'categories' => $categories,
            'succes'     => $succes,
        ]);
    }

    public function traiterForm(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $titre    = trim($data['titre'] ?? '');
        $resumer  = $data['resumer']  !== '' ? $data['resumer']  : null;
        $contenue = $data['contenue'] !== '' ? $data['contenue'] : null;
        $categorie = (int) ($data['categorie'] ?? 0);

        $erreurs = [];

        if ($titre === '') {
            $erreurs[] = 'Le titre est obligatoire.';
        }

        if ($categorie === 0) {
            $erreurs[] = 'La catégorie est obligatoire.';
        }

        if (!empty($erreurs)) {
            $view = Twig::fromRequest($request);
            $categories = $this->catalogueService->listerCategories();

            return $view->render($response, 'createArticle.twig', [
                'categories' => $categories,
                'erreurs'    => $erreurs,
                'donnees'    => $data,
            ]);
        }

        $idAuteur = $_SESSION['user_id'] ?? '';

        $this->catalogueService->creerArticle($titre, $resumer, $contenue, $categorie, $idAuteur);

        return $response
            ->withHeader('Location', '/articles/creer?succes=1')
            ->withStatus(302);
    }
}
