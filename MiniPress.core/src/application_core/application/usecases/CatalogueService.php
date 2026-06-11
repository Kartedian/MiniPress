<?php

namespace Dwm\MiniPress\application_core\application\usecases;

use Dwm\MiniPress\application_core\domain\entities\ArticleEntity;
use Dwm\MiniPress\application_core\domain\entities\CategorieEntity;
use Dwm\MiniPress\infrastructure\Article;
use Dwm\MiniPress\infrastructure\Categorie;

class CatalogueService implements CatalogueServiceInterface
{
    public function getCategories(): array
    {
        return Categorie::all()->map(fn($c) => new CategorieEntity(
            $c->id,
            $c->libelle,
            $c->description
        ))->all();
    }

    public function creerArticle(
        string $titre,
        ?string $resumer,
        ?string $contenue,
        int $categorie,
        string $idAuteur
    ): ArticleEntity {
        $now = new \DateTimeImmutable();

        Article::create([
            'Titre'     => $titre,
            'resumer'   => $resumer ?? '',
            'Contenue'  => $contenue,
            'Date'      => $now->format('Y-m-d H:i:s'),
            'Categorie' => $categorie,
            'ID-Auteur' => $idAuteur,
            'Etats'     => 0,
        ]);

        return new ArticleEntity(
            '',
            $titre,
            $resumer,
            $contenue,
            $now,
            $categorie,
            $idAuteur,
            0,
        );
    }
}