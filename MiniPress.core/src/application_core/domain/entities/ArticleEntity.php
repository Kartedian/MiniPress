<?php

namespace Dwm\MiniPress\application_core\domain\entities;

class ArticleEntity{
    public function __construct(
        public readonly string $id,
        public readonly string $titre,
        public readonly ?string $resumer,
        public readonly ?string $contenue,
        public readonly \DateTimeImmutable $date,
        public readonly int $categorie,
        public readonly string $id_Auteur,
        public readonly int $etats
    ) {}
}