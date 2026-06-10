<?php

namespace Dwm\MiniPress\application_core\application\usecases;

use Dwm\MiniPress\application_core\domain\entities\UserEntity;

interface AuthnServiceInterface {

    public function register(string $email, string $password): UserEntity;

    public function login(string $email, string $password): ?UserEntity;

    
}