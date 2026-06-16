<?php

namespace Dwm\MiniPress\application_core\application\usecases;

use Dwm\MiniPress\application_core\domain\entities\ArticleEntity;
use Dwm\MiniPress\application_core\domain\entities\CategorieEntity;
use Dwm\MiniPress\application_core\domain\entities\UserEntity;
use Psr\Http\Message\UploadedFileInterface;

interface DatabaseServiceInterface
{
    public static function getCategories(): array;

    public static function getArticles(?string $userId = null, string $type = "date", string $order = "desc"): array;

    public static function getArticlesFromCategory(int $id_categ): array;

    public static function getArticleById(string $id): ?array;

    public static function getArticlesByIdAuteur(string $id_auteur): array;

    public static function getAuthorById(string $id_auteur): ?array;

    public static function creerArticle(string $titre, ?string $resumer, ?string $contenue, int $categorie, string $url_image, string $idAuteur): ?ArticleEntity;

    public static function creerCategorie(string $libelle, ?string $description): ?CategorieEntity;

    public static function updatePublishStatus(string $id, int $public);

    public static function getCategorieById(int $id): ?array;

    public static function stockerImageArticle(\Psr\Http\Message\UploadedFileInterface $file): string;


    


    
    // Méthodes pour la gestion des utilisateurs

    public static function findUserByUserId(string $userId): ?UserEntity;

    public static function findUserByEmail(string $email): ?UserEntity;

    public static function findUserById(string $id): ?UserEntity;

    public static function createUser(array $userData): UserEntity;

    public static function saveUser(UserEntity $user): bool;

    public static function deleteUser(UserEntity $user): bool;

    public static function isUserExists(string $email): bool;


}