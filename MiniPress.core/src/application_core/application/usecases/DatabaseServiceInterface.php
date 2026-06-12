<?php

namespace Dwm\MiniPress\application_core\application\usecases;

use Dwm\MiniPress\application_core\domain\entities\ArticleEntity;
use Dwm\MiniPress\application_core\domain\entities\CategorieEntity;
use Dwm\MiniPress\application_core\domain\entities\UserEntity;

interface DatabaseServiceInterface
{
    public static function getCategories(): array;

    public static function getArticles(): array;

    public static function getArticlesFromCategory(int $id_categ): array;

    public static function getArticleById(string $id): ?array;

    public static function getArticlesByIdAuteur(string $id_auteur): array;

    public static function creerArticle(string $titre, ?string $resumer, ?string $contenue, int $categorie, string $url_image, string $idAuteur): ?ArticleEntity;

    public static function creerCategorie(string $libelle, ?string $description): ?CategorieEntity;

    public static function updatePublishStatus(string $id, int $public);

    public static function getCategorieById(int $id): ?array;
}