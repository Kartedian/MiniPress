<?php

namespace Dwm\MiniPress\api;

use Dwm\MiniPress\application_core\application\usecases\DatabaseServiceInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class GetCategoriesApi
{

    public function __construct(private readonly DatabaseServiceInterface $catalogueService) {}

    public function __invoke(Request $request, Response $response): Response
    {
        $categories = $this->catalogueService->getCategories();

        $payload = json_encode($categories);

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}
