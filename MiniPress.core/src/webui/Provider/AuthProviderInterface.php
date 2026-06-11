<?php
declare(strict_types=1);

namespace Dwm\MiniPress\Webui\Providers;

use Dwm\MiniPress\application_core\domain\entities\UserEntity;
use Dwm\MiniPress\application_core\application\usecases\UserRole;

interface AuthProviderInterface
{
    public function login(String $id, String $password): void;

    public function register(String $id, String $password, String $passwordConfirm): void;

    public function logout(): void;

    public function getCurrentUser(): ?UserEntity;

    public function isAuthenticated(): bool;

    public function isAutorized(UserRole ...$requiredRole): bool;
}