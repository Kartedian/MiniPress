<?php
declare(strict_types=1);

namespace Dwm\MiniPress\Webui\Providers;


use Dwm\MiniPress\application_core\domain\entities\UserEntity;
use Dwm\MiniPress\application_core\application\usecases\AuthnService;
use Dwm\MiniPress\application_core\application\usecases\CatalogueService;

class SessionAuthProvider implements AuthProviderInterface
{
    private CatalogueServiceInterface $catalogueService;

    private const SESSION_USER_ID_KEY = 'auth_user_id';

    private const SESSION_LAST_ACTIVITY_KEY = 'auth_last_activity';

    private const SESSION_TIMEOUT = 1800;

    public function __construct(CatalogueServiceInterface $catalogueService)
    {
        $this->catalogueService = $catalogueService;

    }


    public function setActiveUserId(string $userId): void
    {
        session_regenerate_id(true);

        $_SESSION[self::SESSION_USER_ID_KEY] = $userId; 
        $_SESSION[self::SESSION_LAST_ACTIVITY_KEY] = time();

    }

    public function clearActiveUser(): void
    {
        $this->clearSession();
    }
    
    public function getCurrentUserId(): ?string
    {
        if (!$this->isSessionValid()) {
            return null;
        }
        return $_SESSION[self::SESSION_USER_ID_KEY] ?? null;
    }


    /**
     * {@inheritDoc}
     */
    public function getSignedInUser(): ?UserEntity
    {
        if (!$this->isSessionValid()) {
            return null;
        }

        $userId = $_SESSION[self::SESSION_USER_ID_KEY] ?? null;
        
        if ($userId === null) {
            return null;
        }

        try {
            $userRecord = $this->catalogueService->findById($userId);
            
            if ($userRecord !== null) {
                $this->updateLastActivity();
                
                return new UserEntity(
                    (string)$userRecord->id,
                    $userRecord->user_id,
                    $userRecord->password,
                    (int)$userRecord->role
                );
            }
            
            return $userRecord;
            
        } catch (\Exception $e) {
            // En cas d'erreur, nettoyer la session
            $this->clearSession();
            return null;
        }
    }


    /**
     * Vérifie si un utilisateur est actuellement authentifié
     * 
     * @return bool
     */
    public function isAuthenticated(): bool
    {
        return $this->getSignedInUser() !== null;
    }

    /**
     * Obtient le rôle de l'utilisateur connecté
     * 
     * @return int|null Le rôle ou null si non connecté
     */
    public function getUserRole(): ?int
    {
        $user = $this->getSignedInUser();
        return $user ? $user->role : null; // Changed to direct property access
    }




    /**
     * Vérifie si l'utilisateur connecté a le rôle admin
     * 
     * @return bool
     */
    public function isAdmin(): bool
    {
        $user = $this->getSignedInUser();
        return $user && $user->role === AuthnService::ROLE_ADMIN;
    }

    

    /**
     * Vérifie si l'utilisateur connecté est un utilisateur standard
     * 
     * @return bool
     */
    public function isUser(): bool
    {
        $user = $this->getSignedInUser();
        return $user && $user->role === AuthnService::ROLE_USER;
    }

    /**
     * Crée une session pour l'utilisateur authentifié
     * 
     * @param User $user
     */
    private function createUserSession(UserEntity $user): void
    {
        session_regenerate_id(true);
        
        $_SESSION[self::SESSION_USER_ID_KEY] = $user->id; 
        $_SESSION[self::SESSION_LAST_ACTIVITY_KEY] = time();
        
    
        $_SESSION['auth_user_role'] = $user->role; 
    }

    /**
     * Nettoie les données d'authentification de la session
     */
    private function clearSession(): void
    {
        unset($_SESSION[self::SESSION_USER_ID_KEY]);
        unset($_SESSION[self::SESSION_LAST_ACTIVITY_KEY]);
        unset($_SESSION['auth_user_role']);
        
        
    }

    /**
     * Vérifie si la session est valide (non expirée)
     * 
     * @return bool
     */
    private function isSessionValid(): bool
    {
        $lastActivity = $_SESSION[self::SESSION_LAST_ACTIVITY_KEY] ?? null;
        
        if ($lastActivity === null) {
            return false;
        }

        // Vérifier si la session n'a pas expiré
        if ((time() - $lastActivity) > self::SESSION_TIMEOUT) {
            $this->clearSession();
            return false;
        }

        return true;
    }

    /**
     * Met à jour le timestamp de dernière activité
     */
    private function updateLastActivity(): void
    {
        $_SESSION[self::SESSION_LAST_ACTIVITY_KEY] = time();
    }
}