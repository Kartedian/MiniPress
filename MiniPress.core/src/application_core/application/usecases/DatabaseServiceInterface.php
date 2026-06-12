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

    public static function getArticlesById(string $id_a): ?array;

    public static function getArticlesByIdAuteur(string $id_auteur): array;

    public static function creerArticle(string $titre, ?string $resumer, ?string $contenue, int $categorie, string $idAuteur): ?ArticleEntity;



    
    // Méthodes pour la gestion des utilisateurs

    public static function findUserByUserId(string $userId): ?UserEntity;

    public static function findUserByEmail(string $email): ?UserEntity;

    public static function findUserById(string $id): ?UserEntity;

    public static function createUser(array $userData): UserEntity;

    public static function saveUser(UserEntity $user): bool;

    public static function deleteUser(UserEntity $user): bool;

    public static function isUserExists(string $email): bool;


}