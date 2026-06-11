<?php

namespace Dwm\MiniPress\application_core\application\usecases;

use Dwm\MiniPress\application_core\domain\entities\ArticleEntity;
use Dwm\MiniPress\application_core\domain\entities\CategorieEntity;
use Dwm\MiniPress\infrastructure\Article;
use Dwm\MiniPress\infrastructure\Categorie;

class DatabaseService implements DatabaseServiceInterface
{
    public static function getCategories(): array
    {
        return Categorie::all()->map(fn($c) => new CategorieEntity(
            $c->id,
            $c->libelle,
            $c->description
        ))->all();
    }

    public static function creerArticle(
        string $titre,
        ?string $resumer,
        ?string $contenue,
        int $categorie,
        string $idAuteur
    ): ?ArticleEntity {
        return null;
    }

}