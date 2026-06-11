<?php

namespace Dwm\MiniPress\application_core\application\usecases;

interface CatalogueServiceInterface
{
    public function getCategories(): array;

    public function getArticles(): array;

    public function getArticlesFromCategory(int $id_categ): array;

    public function getArticlesById(string $id_a): ?array;

    public function getArticlesByIdAuteur(string $id_auteur): array;
}