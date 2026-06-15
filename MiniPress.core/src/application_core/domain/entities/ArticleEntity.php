<?php

namespace Dwm\MiniPress\application_core\domain\entities;

use DateTime;

class ArticleEntity{
    public function __construct(
        public readonly string $id,
        public readonly string $titre,
        public readonly ?string $resumer,
        public readonly ?string $contenue,
        public readonly DateTime $date,
        public readonly int $categorie,
        public readonly string $url_image,
        public readonly string $id_Auteur,
        public readonly int $published
    ) {}
}