<?php

namespace Dwm\MiniPress\application_core\domain\entities;

class UserEntity{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $user_id,
        public readonly string $password,
        public readonly int $Role
    ) {}
}