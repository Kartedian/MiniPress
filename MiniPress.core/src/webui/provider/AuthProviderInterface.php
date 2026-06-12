<?php
declare(strict_types=1);

namespace Dwm\MiniPress\webui\provider;

use Dwm\MiniPress\application_core\domain\entities\UserEntity;
use Dwm\MiniPress\application_core\application\usecases\UserRole;

interface AuthProviderInterface
{
    public static function login(String $id, String $password): bool;

    public static function register(String $id, String $password, String $passwordConfirm): bool;

    public static function logout(): void;

    public static function getCurrentUser(): ?UserEntity;

    public static function isAuthenticated(): bool;

    public static function isAuthorized(UserRole ...$requiredRole): bool;
}