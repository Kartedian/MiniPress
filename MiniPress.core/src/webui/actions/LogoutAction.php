<?php

namespace Dwm\MiniPress\webui\actions;

use Dwm\MiniPress\webui\provider\AuthProviderInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class LogoutAction
{
    public function __construct(private readonly AuthProviderInterface $auth){}

    public function __invoke(Request $request, Response $response, array $args)
    {
        $this->auth::logout(); // Appelle la méthode de déconnexion pour supprimer la session
        return $response
            ->withHeader('Location', '/')
            ->withStatus(302); // Redirige vers la page d'accueil après la déconnexion
    }
}