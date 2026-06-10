<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

use Illuminate\Database\Capsule\Manager as DB;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

// --- Base de données ----------------------------------------------------------
$config = parse_ini_file(__DIR__ . '/confdb.ini');
if ($config !== false) {
    $db = new DB();
    $db->addConnection($config);
    $db->setAsGlobal();
    $db->bootEloquent();
}