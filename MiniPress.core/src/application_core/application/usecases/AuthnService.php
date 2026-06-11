<?php

namespace Dwm\MiniPress\application_core\application\usecases;

use Dwm\MiniPress\application_core\application\usecases\AuthnServiceInterface;
use Dwm\MiniPress\infrastructure\User;

class AuthnService implements AuthnServiceInterface
{

    public const ROLE_USER = 1;
    public const ROLE_ADMIN = 100;



    public function register(string $email, string $password): User 
    {
        // Validation des données d'entrée
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Invalid email format.");
        }
        $this->validatePassword($password);

        $existingUser = $this->userRepository->findByEmail($email); 
        if ($existingUser !== null) {
            throw new \RuntimeException("A user with this email already exists.");
        }

        $hashedPassword = $this->hashPassword($password);

        $user = new User();
        $user->user_id = $email; 
        $user->password = $hashedPassword;
        $user->role = self::ROLE_USER;

        $this->userRepository->save($user);
        return $user;
    }







}