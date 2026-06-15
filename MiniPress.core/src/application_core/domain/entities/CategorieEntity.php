<?php

namespace Dwm\MiniPress\application_core\domain\entities;

class CategorieEntity{
    public function __construct(
        public readonly int $id,
        public readonly string $libelle,
        public readonly ?string $description
    ) {}
}