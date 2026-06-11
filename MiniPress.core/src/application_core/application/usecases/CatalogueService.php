<?php

namespace Dwm\MiniPress\application_core\application\usecases;

use Dwm\MiniPress\application_core\domain\entities\ArticleEntity;
use Dwm\MiniPress\application_core\domain\entities\CategorieEntity;
use Dwm\MiniPress\infrastructure\Article;
use Dwm\MiniPress\infrastructure\Categorie;

class CatalogueService implements CatalogueServiceInterface
{
    public function listerCategories(): array
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


    public function findByUserId(string $userId): ?User
    {
        return User::where('user_id', $userId)->first();
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('user_id', $email)->first();
    }

    public function findById(string $id): ?User
    {
        return User::find($id);
    }

    public function create(array $userData): User
    {
        $user = new User();
        
        $user->id = base64_encode(random_bytes(16));
        $user->user_id = $userData['user_id'] ?? $userData['email'];
        $user->password = $userData['password'];
        $user->role = $userData['role'] ?? 1;
        
        // Ajouter nom et prenom si disponibles
        if (isset($userData['nom'])) {
            $user->nom = $userData['nom'];
        }
        if (isset($userData['prenom'])) {
            $user->prenom = $userData['prenom'];
        }
        
        $user->save();
        
        return $user;
    }

    public function save(User $user): bool
    {   
        if (empty($user->id)) {
            $user->id = base64_encode(random_bytes(16));
        }
        
        return $user->save();
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }

    public function emailExists(string $email): bool
    {
        return User::where('user_id', $email)->exists();
    }

    
    private function generateUuid(): string
    {
    return Uuid::uuid4()->toString();
    }


}