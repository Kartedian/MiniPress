<?php

use Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return function (App $app): App{
    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write("<h1>Welcome to MiniPress</h1>");
        return $response;
    });

    return $app;
};