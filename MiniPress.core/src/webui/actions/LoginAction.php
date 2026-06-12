<?php

namespace Dwm\MiniPress\webui\actions;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Dwm\MiniPress\webui\provider\AuthProviderInterface;
use Slim\Views\Twig;

class LoginAction
{
    public function __construct(private readonly AuthProviderInterface $auth){}

    public function __invoke(Request $request, Response $response, array $args)
    {
        if ($request->getMethod() === 'POST') {
            $data = $request->getParsedBody();
            $email = $data['email'] ?? null;
            $password = $data['password'] ?? null;

            if ($email && $password) {
                try {
                    if ($this->auth::login($email, $password)) { 
                        return $response
                            ->withHeader('Location', '/')
                            ->withStatus(302); // Redirige vers la page d'accueil après une connexion réussie
                    } else {
/////////////// Message d'erreur en cas d'échec de l'authentification ///////////////
                        $view = Twig::fromRequest($request);
                        return $view->render($response, 'LoginView.html', ['error' => 'Email ou mot de passe incorrect']);
                    }
                } catch (\Exception $e) {
                    $view = Twig::fromRequest($request);
                    return $view->render($response, 'LoginView.html', ['error' => 'Une erreur est survenue lors de l\'authentification : ' . $e->getMessage()]);
                }
            } else {
                $view = Twig::fromRequest($request);
                return $view->render($response, 'LoginView.html', ['error' => 'Veuillez remplir tous les champs']);
            }
        }
        // Affiche le formulaire de connexion
        $view = Twig::fromRequest($request);
        return $view->render($response, 'LoginView.html');
    }
}