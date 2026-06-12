<?php
declare(strict_types=1);

namespace Dwm\MiniPress\webui\provider;


use Dwm\MiniPress\application_core\domain\entities\UserEntity;
use Dwm\MiniPress\application_core\application\usecases\AuthnService;
use Dwm\MiniPress\application_core\application\usecases\DatabaseServiceInterface;
use Dwm\MiniPress\application_core\application\usecases\UserRole;
use Dwm\MiniPress\application_core\domain\exceptions\UserException;
use Dwm\MiniPress\webui\provider\AuthProviderInterface;

class SessionAuthProvider implements AuthProviderInterface
{
    private DatabaseServiceInterface $catalogueService;

    private const SESSION_USER_ID_KEY = 'auth_user_id';

    private const SESSION_LAST_ACTIVITY_KEY = 'auth_last_activity';

    private const SESSION_TIMEOUT = 1800;

    public function __construct(DatabaseServiceInterface $catalogueService)
    {
        $this->catalogueService = $catalogueService;

    }




    public static function login(string $email, string $password): bool
    {
        $user = AuthnService::login($email, $password);
        if ($user) {
            $_SESSION['user_id'] = $user->id;
            return true;
        }
        return false;
    }



    public static function register(String $id, String $password, String $passwordConfirm): bool
    {
        try {
            AuthnService::register($id, $password);
            return true;
        } catch (UserException $e) {
            return false;
        }
    }


    public static function logout(): void
    {
        $_SESSION = [];
        session_destroy();
    }

    

    public static function getCurrentUser(): ?UserEntity{
        if (!isset($_SESSION['user_id'])) {
            return null;
        }

        $userId = $_SESSION['user_id'];
        return AuthnService::getUserById($userId);

    }




    public static function isAuthenticated(): bool
    {
        return isset($_SESSION['user_id']);
    }


    public static function isAuthorized(UserRole ...$requiredRole): bool
    {
        $currentUser = self::getCurrentUser();
        if (!$currentUser) {
            return false;
        }

        foreach ($requiredRole as $role) {
            if ($currentUser->Role === $role->value) {
                return true;
            }
        }
        return false;
     

    }


}