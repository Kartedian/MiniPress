<?php
declare(strict_types=1);

namespace Dwm\MiniPress\Webui\Providers;

use Dwm\MiniPress\application_core\domain\entities\UserEntity;

interface AuthProviderInterface
{
    public function getSignedInUser(): ?UserEntity;

    
    public function setActiveUserId(string $userId): void;

    
    public function clearActiveUser(): void;

    public function isAuthenticated(): bool;

    public function getCurrentUserId(): ?string;

    public function getUserRole(): ?int;

    public function isAdmin(): bool;

    public function isUser(): bool;


}