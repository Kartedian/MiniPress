<?php

namespace Dwm\MiniPress\application_core\application\usecases;

use Dwm\MiniPress\application_core\domain\entities\ArticleEntity;
use Dwm\MiniPress\application_core\domain\entities\CategorieEntity;
use Dwm\MiniPress\infrastructure\Article;
use Dwm\MiniPress\infrastructure\Categorie;
use Dwm\MiniPress\application_core\domain\exceptions\DatabaseException;
use Override;
use Throwable;

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

    public static function getArticles(): array{
        return Article::order('date')
                        ->get()
                        ->map(fn($a) => new ArticleEntity(
                            $a->id,
                            $a->titre,
                            $a->resumer,
                            $a->contenue,
                            $a->date,
                            $a->categorie,
                            $a->url_image,
                            $a->id_auteur,
                            $a->published
                        ))->all();
    }

    public static function getArticlesFromCategory(int $id_categ): array{
        return Article::where('categorie', $id_categ)
                        ->order('date')
                        ->get()
                        ->map(fn($a) => new ArticleEntity(
                            $a->id,
                            $a->titre,
                            $a->resumer,
                            $a->contenue,
                            $a->date,
                            $a->categorie,
                            $a->url_image,
                            $a->id_auteur,
                            $a->published
                        ))->all();
    }

    public static function getArticleById(string $id): ?array{
        try{
            $article = Article::findOrFail($id);
        } catch (Throwable $e) {
            throw DatabaseException::erreurRecuperation("erreur lors de la récupération de la prestation {$id} : {$e->getMessage()}");
        }

        return [
            'id' => $article->id,
            'titre' => $article->titre,
            'resumer' => $article->resumer,
            'contenue' => $article->contenue,
            'date' => $article->date,
            'categorie' => $article->categorie,
            'url_image' => $article->url_image,
            'id_auteur' => $article->id_auteur,
            'published' => $article->published
        ];
    }

    public static function getArticlesByIdAuteur(string $id_auteur): array{
        return [];
    }

    public static function creerArticle(
        string $titre,
        ?string $resumer,
        ?string $contenue,
        int $categorie,
        string $url_image,
        string $idAuteur
    ): ?ArticleEntity {
        $article = Article::create([
            'titre' => $titre,
            'resumer' => $resumer,
            'contenue' => $contenue,
            'date' => date('Y-m-d'),
            'categorie' => $categorie,
            'url_image' => $url_image,
            'id_auteur' => $idAuteur
        ]);

        if ($article) {
            return new ArticleEntity(
                $article->id,
                $article->titre,
                $article->resumer,
                $article->contenue,
                $article->date,
                $article->categorie,
                $article->url_image,
                $article->id_auteur,
                $article->published
            );
        }
        return null;
    }

    public static function creerCategorie(string $libelle, ?string $description): ?CategorieEntity
    {
        $categorie = Categorie::create([
            'libelle' => $libelle,
            'description' => $description
        ]);

        if($categorie){
            return new CategorieEntity(
                $categorie->id,
                $categorie->libelle,
                $categorie->description
            );
        }
        return null;
    }

    public static function updatePublishStatus(string $id, int $public)
    {
        $article = Article::find($id);

        if($article !== null){
            $article->published = $public;

            $article->save();
        } else {
        throw new \Exception("Article introuvable");
        }
    }

    public static function getCategorieById(int $id): ?array{
        try{
            $categorie = Categorie::findOrFail($id);
        } catch (Throwable $e) {
            throw DatabaseException::erreurRecuperation("erreur lors de la récupération de la prestation {$id} : {$e->getMessage()}");
        }

        return [
            'id' => $categorie->id,
            'libelle' => $categorie->libelle,
            'description' => $categorie->description
        ];
    }

}