<?php

namespace Dwm\MiniPress\application_core\application\usecases;

use Dwm\MiniPress\application_core\application\usecases\AuthnServiceInterface;
use Dwm\MiniPress\application_core\domain\entities\UserEntity;
use Dwm\MiniPress\Webui\Providers\AuthProviderInterface;
use Dwm\MiniPress\application_core\application\usecases\DatabaseServiceInterface;
use Dwm\MiniPress\application_core\domain\exceptions\UserException;

enum UserRole: int
    {
        case USER = 1;
        case AUTHOR = 10;
        case ADMIN = 100;
    }


class AuthnService implements AuthnServiceInterface
{   

    private static DatabaseServiceInterface $catalogueService;
    
    public static function init(DatabaseServiceInterface $catalogueService): void
    {
        self::$catalogueService = $catalogueService;
    }

    public static function register(string $name, string $email, string $password): UserEntity 
    {
        if (self::$catalogueService::isUserExists($email)) {
        throw new UserException("Email already exists");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $userData = [
        'name' => $name,
        'user_id' => $email,
        'password' => $hashedPassword,
        'role' => UserRole::USER->value
    ];

    return self::$catalogueService::createUser($userData);
        
    }

    


    public static function login(string $email, string $password): ?UserEntity
    {
        $user = self::$catalogueService->findUserByEmail($email);
        if (!$user) {
            return null;
        }

        if (password_verify($password, $user->password)) {
            return new UserEntity(
                (string)$user->id,
                $user->name,
                $user->user_id,
                $user->password,
                (int)$user->Role
            );
        }
        return null;
    }


    


    public static function getUserById(string $userId): ?UserEntity
    {
        $user = self::$catalogueService->findUserById($userId);
        if (!$user) {
            return null;
        }

        return new UserEntity(
            (string)$user->id,
            $user->name,
            $user->user_id,
            $user->password,
            (int)$user->Role
        );
        
    }

    public static function getNameUserById(string $userId): ?string{
        $user = self::$catalogueService->findUserById($userId);

        if (!$user) {
            return null;
        }

        return $user->name;
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
