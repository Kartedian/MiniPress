<?php

namespace Dwm\MiniPress\application_core\application\usecases;

use Dwm\MiniPress\application_core\application\usecases\AuthnServiceInterface;
use Dwm\MiniPress\application_core\domain\entities\UserEntity;
use Dwm\MiniPress\Webui\Providers\AuthProviderInterface;
use Dwm\MiniPress\infrastructure\User;
use Dwm\MiniPress\application_core\application\usecases\CatalogueServiceInterface;
use Dwm\MiniPress\application_core\domain\exceptions\UserException;

enum UserRole: int
    {
        case USER = 1;
        case ADMIN = 100;
    }

class AuthnService implements AuthnServiceInterface
{   

    private CatalogueServiceInterface $catalogueService;
    private AuthProviderInterface $authProvider;

    public function __construct(CatalogueServiceInterface $catalogueService, AuthProviderInterface $authProvider)
    {
        $this->catalogueService = $catalogueService;
        $this->authProvider = $authProvider;
    }



    public function register(string $email, string $password): UserEntity 
    {
        // Validation des données d'entrée
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Invalid email format.");
        }
        $this->validatePassword($password);

        $existingUser = $this->catalogueService->findByEmail($email); 
        if ($existingUser !== null) {
            throw new \RuntimeException("A user with this email already exists.");
        }

        $hashedPassword = $this->hashPassword($password);

        $user = new User();
        $user->id = base64_encode(random_bytes(16));
        $user->user_id = $email; 
        $user->password = $hashedPassword;
        $user->role = UserRole::USER->value;

        $this->catalogueService->save($user);
        return new UserEntity(
            (string)$user->id,
            $user->user_id,
            $user->password,
            (int)$user->role
        );
    }



    public function registerUser(string $userId, string $password): UserEntity
    {
        
        $this->validatePassword($password);

        $existingUser = $this->catalogueService->findByUserId($userId);
        if ($existingUser !== null) {
            throw new \RuntimeException("Un utilisateur avec cet ID utilisateur existe déjà");
        }

        $hashedPassword = $this->hashPassword($password);

        $user = new User();
        $user->id = base64_encode(random_bytes(16));
        $user->user_id = $userId;
        $user->password = $hashedPassword; 
        $user->role = UserRole::USER->value;

        // Sauvegarder l'utilisateur
        $this->catalogueService->save($user);
        return new UserEntity(
            (string)$user->id,
            $user->user_id,
            $user->password,
            (int)$user->role
        );
    }



    public function login(string $email, string $password): ?UserEntity
    {
        if (empty($email) || empty($password)) { 
            return null;
        }

        $user = $this->catalogueService->findByEmail($email);
        if ($user === null) {
            return null;
        }

        if ($this->verifyPassword($password, $user->password)) {
        
            if (!isset($user->id)) {
                throw new \LogicException("User object is missing an ID property.");
            }
            $this->authProvider->setActiveUserId((string)$user->id);
            return new UserEntity(
                (string)$user->id,
                $user->user_id,
                $user->password,
                (int)$user->role
            );
        }

        return null;
    }

    public function signOut(): void
    {
        $this->authProvider->clearActiveUser(); 
    }

    public function isSignedIn(): bool
    {
        return $this->authProvider->isAuthenticated(); 
    }

    public function getCurrentUserId(): ?string
    {
        return $this->authProvider->getCurrentUserId();
    }


    private function validatePassword(string $password): void
    {
        if (empty($password)) {
            throw new \InvalidArgumentException("Password cannot be empty.");
        }

        if (strlen($password) < 6) {
            throw new \InvalidArgumentException("Password must be at least 6 characters long.");
        }

        if (strlen($password) > 255) {
            throw new \InvalidArgumentException("Password is too long (max 255 characters).");
        }
    }


    private function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT, [
            'cost' => 12, 
        ]);
    }

    private function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

}
