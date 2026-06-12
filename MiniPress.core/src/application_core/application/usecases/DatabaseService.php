<?php

namespace Dwm\MiniPress\application_core\application\usecases;

use Dwm\MiniPress\application_core\domain\entities\ArticleEntity;
use Dwm\MiniPress\application_core\domain\entities\CategorieEntity;
use Dwm\MiniPress\infrastructure\Article;
use Dwm\MiniPress\infrastructure\Categorie;
use Dwm\MiniPress\application_core\domain\entities\UserEntity;
use Dwm\MiniPress\infrastructure\User;
use Ramsey\Uuid\Uuid;
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


    public static function getArticles(string $type = "date", string $order = "desc"): array{
        return Article::orderBy($type, $order)
                        ->get()
                        ->all();
    }

    public static function getArticlesFromCategory(int $id_categ): array{
        $article = Article::where('categorie', $id_categ)
                        ->orderBy('date', 'desc')
                        ->get()
                        ->map(fn($a) => new ArticleEntity(
                            $a->id,
                            $a->titre,
                            $a->resumer,
                            $a->contenue,
                            new \DateTime($a->date),
                            $a->categorie,
                            $a->url_image,
                            $a->id_auteur,
                            $a->published
                        ))->all();

        return [
            'id' => $article->id,
            'titre' => $article->titre,
            'resumer' => $article->resumer,
            'contenue' => $article->contenue,
            'date' => new \DateTime($article->date),
            'categorie' => $article->categorie,
            'url_image' => $article->url_image,
            'id_auteur' => $article->id_auteur,
            'published' => $article->published
        ];
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
            'date' => new \DateTime($article->date),
            'categorie' => $article->categorie,
            'url_image' => $article->url_image,
            'id_auteur' => $article->id_auteur,
            'published' => $article->published
        ];
    }

    public static function getArticlesByIdAuteur(string $id_auteur): array{
        return Article::where('id_auteur', $id_auteur)
                        ->order('date', 'desc')
                        ->get()
                        ->all();
    }

    public static function getAuthorById(string $id_auteur): array 
    {
        return User::where('id', $id_auteur)
                    ->first()
                    ->toArray();
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
            'date' => new \DateTime(),
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
                new \DateTime($article->date),
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



    
       

    public static function findUserByUserId(string $userId): ?UserEntity
    {
        return User::where('user_id', $userId)->first();
    }

    public static function findUserByEmail(string $email): ?UserEntity
    {
        return User::where('user_id', $email)->first();
    }

    public static function findUserById(string $id): ?UserEntity
    {
        return User::find($id);
    }

    public static function createUser(array $userData): UserEntity
    {
        $user = new User();
        
        $user->id = base64_encode(random_bytes(16));
        $user->user_id = $userData['user_id'] ?? $userData['email'];
        $user->password = $userData['password'];
        $user->role = $userData['role'] ?? 1;
        
        if (isset($userData['nom'])) {
            $user->nom = $userData['nom'];
        }
        if (isset($userData['prenom'])) {
            $user->prenom = $userData['prenom'];
        }
        
        $user->save();
        
        return new UserEntity(
            (string)$user->id,
            $user->user_id,
            $user->password,
            (int)$user->role
        );
    }

    public static function saveUser(UserEntity $user): bool
    {   
        $model = User::find($user->id);
        
        if (!$model) {
            $model = new User();
            $model->id = $user->id ?: base64_encode(random_bytes(16));
        }
        
        $model->user_id = $user->user_id;
        $model->password = $user->password;
        $model->role = $user->Role;
        
        return $model->save();
    }

    public static function deleteUser(UserEntity $user): bool
    {
        $model = User::find($user->id);
        
        if ($model) {
            return $model->delete();
        }
        
        return false;
    }

    public static function isUserExists(string $email): bool
    {
        return User::where('user_id', $email)->exists();
    }

    
    private static function generateUuid(): string
    {
    return Uuid::uuid4()->toString();
    }




    
    

}