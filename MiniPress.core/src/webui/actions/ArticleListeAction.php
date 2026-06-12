<?php

namespace Dwm\MiniPress\webui\actions;

use Dwm\MiniPress\application_core\application\usecases\DatabaseServiceInterface;
use Dwm\MiniPress\application_core\domain\exceptions\DatabaseException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Views\Twig;

class ArticleListeAction
{
    public function __construct(private readonly DatabaseServiceInterface $db){}

    public function __invoke(Request $request, Response $response, array $args){
        $queryParams = $request->getQueryParams();
        $categorieId = $queryParams['categorie'] ?? null;
        try{
            $categories = $this->db->getCategories();

            if(!empty($categorieId)){
                $articles = $this->db->getArticlesFromCategory((int)$categorieId);
            }
            else{
                $articles = $this->db->getArticles();
            }
        }
        catch(DatabaseException $e){
            throw new HttpInternalServerErrorException($request, $e->getMessage());
        }

        $view = Twig::fromRequest($request);
        return $view->render($response, 'ArticleListeView.html', [
            'articles' => $articles,
            'categories' => $categories,
            'current_cat' => $categorieId,
        ]);
    }
}