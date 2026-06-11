<?php

namespace Dwm\MiniPress\application_core\application\usecases;

use Dwm\MiniPress\application_core\domain\entities\ArticleEntity;
use Dwm\MiniPress\application_core\domain\entities\CategorieEntity;
use Dwm\MiniPress\application_core\domain\entities\UserEntity;

interface CatalogueServiceInterface
{
    public function getCategories(): array;

    public function getArticles(): array;

    public function getArticlesFromCategory(int $id_categ): array;

    public function getArticlesById(string $id_a): ?array;

    public function getArticlesByIdAuteur(string $id_auteur): array;

    public function creerArticle(
        string $titre,
        ?string $resumer,
        ?string $contenue,
        int $categorie,
        string $idAuteur
    ): ArticleEntity;



    public function findByUserId(string $userId): ?User;

    public function findByEmail(string $email): ?User;

    public function findById(string $id): ?User;

    public function create(array $userData): User;

    public function save(User $user): bool;

    public function delete(User $user): bool;

    public function emailExists(string $email): bool;









}