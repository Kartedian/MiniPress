<?php

namespace Dwm\MiniPress\application_core\application\usecases;

use Dwm\MiniPress\application_core\domain\entities\ArticleEntity;
use Dwm\MiniPress\application_core\domain\entities\CategorieEntity;
use Dwm\MiniPress\infrastructure\Article;
use Dwm\MiniPress\infrastructure\Categorie;
use Dwm\MiniPress\application_core\domain\entities\UserEntity;
use Dwm\MiniPress\infrastructure\User;
use Ramsey\Uuid\Uuid;


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


        
    public static function findByUserId(string $userId): ?UserEntity
    {
        return User::where('user_id', $userId)->first();
    }

    public static function findByEmail(string $email): ?UserEntity
    {
        return User::where('user_id', $email)->first();
    }

    public static function findById(string $id): ?UserEntity
    {
        return User::find($id);
    }

    public static function create(array $userData): UserEntity
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

    public static function save(UserEntity $user): bool
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

    public static function delete(UserEntity $user): bool
    {
        $model = User::find($user->id);
        
        if ($model) {
            return $model->delete();
        }
        
        return false;
    }

    public static function emailExists(string $email): bool
    {
        return User::where('user_id', $email)->exists();
    }

    
    private static function generateUuid(): string
    {
    return Uuid::uuid4()->toString();
    }




    

}