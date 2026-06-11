<?php

namespace Dwm\MiniPress\application_core\application\usecases;

use Dwm\MiniPress\application_core\domain\entities\ArticleEntity;
use Dwm\MiniPress\application_core\domain\entities\CategorieEntity;

interface CatalogueServiceInterface
{
    /** @return CategorieEntity[] */
    public function listerCategories(): array;

    public function creerArticle(
        string $titre,
        ?string $resumer,
        ?string $contenue,
        int $categorie,
        string $idAuteur
    ): ArticleEntity;
}