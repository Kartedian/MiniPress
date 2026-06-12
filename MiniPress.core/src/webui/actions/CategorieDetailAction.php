<?php

namespace Dwm\MiniPress\webui\actions;

use Dwm\MiniPress\application_core\application\usecases\DatabaseServiceInterface;
use Dwm\MiniPress\application_core\domain\exceptions\DatabaseException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Views\Twig;

class CategorieDetailAction
{
    public function __construct(private readonly DatabaseServiceInterface $db) {}

    public function __invoke(Request $request, Response $response, array $args)
    {
        try{
            $categorie = $this->db->getCategorieById($args['id']);
        } catch (DatabaseException $e) {
            throw new HttpInternalServerErrorException($request, $e->getMessage());
        }    

        $view = Twig::fromRequest($request);
        return $view->render($response, 'CategorieDetailView.html', [
            'libelle' => $categorie['libelle'],
            'description' => $categorie['description']
        ]);
    }
}
