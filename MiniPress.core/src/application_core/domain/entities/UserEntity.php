<?php

namespace Dwm\MiniPress\application_core\domain\entities;

class UserEntity{
    public function __construct(
        public readonly int $id,
        public readonly string $user_id,
        public readonly ?string $password,
        public readonly ?string $Role
    ) {}
}