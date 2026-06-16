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
use Dwm\MiniPress\application_core\application\usecases\UserRole;
use Psr\Http\Message\UploadedFileInterface;
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


    public static function getArticles(?string $userId = null, string $type = "date", string $order = "desc"): array {
        
        $query = Article::orderBy($type, $order);

        if ($userId !== null) {
            $query->where(function ($q) use ($userId) {
                $q->where('published', 1)
                  ->orWhere('id_auteur', $userId);
            });
        } else {
            $query->where('published', 1);
        }

        return $query->get()->all();
    }

    public static function getArticlesFromCategory(int $id_categ): array{
        return Article::where('categorie', $id_categ)
                        ->where('published', 1)
                        ->orderBy('date', 'desc')
                        ->get()
                        ->all();
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
            $article->date ? new \DateTime($article->date) : new \DateTime(),
            'categorie' => $article->categorie,
            'url_image' => $article->url_image,
            'id_auteur' => $article->id_auteur,
            'published' => $article->published
        ];
    }

    public static function getArticlesByIdAuteur(string $id_auteur): array{
        return Article::where('id_auteur', $id_auteur)
                        ->orderBy('date', 'desc')
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
            if(User::where('id', $idAuteur)->first()->role === UserRole::USER->value) {
                User::where('id', $idAuteur)->update(['role' => UserRole::AUTHOR->value]);
            }
            return new ArticleEntity(
                $article->id,
                $article->titre,
                $article->resumer,
                $article->contenue,
                $article->date ? new \DateTime($article->date) : new \DateTime(),
                $article->categorie,
                $article->url_image,
                $article->id_auteur,
                $article->published ? 0 : 1,
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
            $article->published = $public == 1? true : false;

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


    public static function stockerImageArticle(UploadedFileInterface $file): string
    {
        $originalName = $file->getClientFilename();
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if (!in_array($ext, ['png', 'jpg', 'jpeg', 'webp'])) {
            throw new \Exception('Format d\'image non supporté (PNG, JPG, WEBP uniquement).');
        }

        $uploadsBaseDir = __DIR__ . '/../../../../images';

        $storedName = null;
        $maxAttempts = 10;

        for ($i = 0; $i < $maxAttempts; $i++) {
            $candidate = bin2hex(random_bytes(8)); 
            $exists = Article::where('url_image', 'LIKE', "%" . $candidate . "%")->exists();
            
            if (!$exists) {
                $storedName = $candidate;
                break;
            }
        }

        if ($storedName === null) {
            throw new \Exception('Impossible de générer un nom unique pour l\'image.');
        }

        $dir1 = substr($storedName, 0, 2);
        $dir2 = substr($storedName, 2, 2);
        $targetDir = $uploadsBaseDir . '/' . $dir1 . '/' . $dir2;

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $fileName = $storedName . '.' . $ext;
        $targetPath = $targetDir . '/' . $fileName;

        $file->moveTo($targetPath);

        return '/images/' . $dir1 . '/' . $dir2 . '/' . $fileName;
    }



    
       

    public static function findUserByUserId(string $userId): ?UserEntity
    {
        $user = User::where('user_id', $userId)->first();

        if ($user === null) {
            return null;
        }

        return new UserEntity(
            (string)$user->id,
            $user->name,
            $user->user_id,
            $user->password,
            (int)$user->role
        );
    }

    public static function findUserByEmail(string $email): ?UserEntity
    {
        // Remarque : Si "user_id" correspond à l'email dans ta base de données, cette requête est correcte.
        $user = User::where('user_id', $email)->first();

        if ($user === null) {
            return null;
        }

        return new UserEntity(
            (string)$user->id,
            $user->name,
            $user->user_id,
            $user->password,
            (int)$user->role
        );
    }

    public static function findUserById(string $id): ?UserEntity
    {
        $user = User::find($id);

        if ($user === null) {
            return null;
        }

        return new UserEntity(
            (string)$user->id,
            $user->name,
            $user->user_id,
            $user->password,
            (int)$user->role
        );
    }

    public static function createUser(array $userData): UserEntity
    {
        $user = new User();
        
        $user->name = $userData['name'];
        $user->user_id = $userData['user_id'] ?? $userData['email'];
        $user->password = $userData['password'];
        $user->role = $userData['role'] ?? 1;
        
        $user->save();
        
        return new UserEntity(
            (string)$user->id,
            $user->name,
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