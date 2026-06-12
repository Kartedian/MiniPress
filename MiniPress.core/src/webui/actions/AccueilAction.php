<?php

namespace Dwm\MiniPress\webui\actions;

use Dwm\MiniPress\application_core\application\usecases\DatabaseServiceInterface;
use Dwm\MiniPress\webui\provider\AuthProviderInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class AccueilAction
{
    public function __construct(private readonly AuthProviderInterface $authProvider){}

    public function __invoke(Request $request, Response $response, array $args){
        $view = Twig::fromRequest($request);
        return $view->render($response, 'AccueilView.html', [
            'isAuthenticated' => $this->authProvider::isAuthenticated()
        ]);
    }
}