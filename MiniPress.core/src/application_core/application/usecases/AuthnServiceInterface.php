<?php

namespace Dwm\MiniPress\application_core\application\usecases;

use Dwm\MiniPress\application_core\domain\entities\UserEntity;

interface AuthnServiceInterface {
    public static function register(string $email, string $password): UserEntity;

    public static function login(string $email, string $password): ?UserEntity;

    public static function getUserById(string $userId): ?UserEntity;
}