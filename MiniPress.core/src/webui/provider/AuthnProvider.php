<?php

namespace Dwm\MiniPress\webui\provider;

use Dwm\MiniPress\application_core\application\usecases\AuthnService;

class AuthnProvider
{
    public static function register(string $email, string $password): bool
    {
        return (AuthnService::register($email, $password) !== null);
    }

    public static function authenticate(string $email, string $password): bool
    {
        $res = AuthnService::login($email, $password);
        if ($res) {
            $_SESSION['user_id'] = $res['user_id'];
            $_SESSION['role'] = $res['role'];
            return true;
        }
        return false;
    }

    public static function logout(): void
    {
        $_SESSION = [];
        session_destroy();
    }

    public static function isAuthenticated(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public static function getUserId(): ?String
    {
        return $_SESSION['user_id'] ?? null;
    }

    public static function getUserRole(): ?int
    {
        return $_SESSION['role'] ?? null;
    }
}