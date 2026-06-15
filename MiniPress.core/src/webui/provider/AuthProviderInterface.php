<?php
declare(strict_types=1);

namespace Dwm\MiniPress\webui\provider;

use Dwm\MiniPress\application_core\domain\entities\UserEntity;
use Dwm\MiniPress\application_core\application\usecases\UserRole;

interface AuthProviderInterface
{
    public static function login(String $id, String $password): bool;

    public static function register(string $name, String $id, String $password, String $passwordConfirm): bool;

    public static function logout(): void;

    public static function getUser(): ?UserEntity;

    public static function isAuthenticated(): bool;

    public static function isAuthorized(UserRole ...$requiredRole): bool;

    public static function getUserId(): ?string;
}