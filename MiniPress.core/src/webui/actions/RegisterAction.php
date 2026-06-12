<?php

namespace Dwm\MiniPress\webui\actions;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Dwm\MiniPress\webui\provider\AuthProviderInterface;
use Slim\Views\Twig;

class RegisterAction
{
    public function __construct(private readonly AuthProviderInterface $auth){}

    public function __invoke(Request $request, Response $response, array $args)
    {
        if ($request->getMethod() === 'POST') {
            $data = $request->getParsedBody();
            $name = $data['name'] ?? null;
            $email = $data['email'] ?? null;
            $password = $data['password'] ?? null;
            $passwordConfirm = $data['passwordConfirm'] ?? null;

            if ($email && $password && $name && $passwordConfirm) {
                try {
                    if ($this->auth::register($name, $email, $password, $passwordConfirm)) { //TODO: utiliser slim
                        return $response
                            ->withHeader('Location', '/login')
                            ->withStatus(302); // Redirige vers la page de connexion après une inscription réussie
                    } else {
///////////////// Message d'erreur en cas d'échec de l'inscription ///////////////
                        $view = Twig::fromRequest($request);
                        return $view->render($response, 'RegisterView.html', ['error' => 'L\'inscription a échoué']);
                    }
                } catch (\Exception $e) {
                    $view = Twig::fromRequest($request);
                    return $view->render($response, 'RegisterView.html', ['error' => 'Une erreur est survenue lors de l\'inscription : ' . $e->getMessage()]);
                }
            } else {
                $view = Twig::fromRequest($request);
                return $view->render($response, 'RegisterView.html', ['error' => 'Veuillez remplir tous les champs']);
            }
        }
        // Affiche le formulaire d'inscription
        $view = Twig::fromRequest($request);
        return $view->render($response, 'RegisterView.html');
    }
}