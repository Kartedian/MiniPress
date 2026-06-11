<?php

namespace Dwm\MiniPress\application_core\application\usecases;

use Dwm\MiniPress\application_core\application\usecases\AuthnServiceInterface;
use Dwm\MiniPress\infrastructure\User;
use Dwm\MiniPress\application_core\domain\exceptions\UserException;

class AuthnService implements AuthnServiceInterface
{
    public static function register(string $email, string $password)
    {
        $user = new User();
        if (User::where('user_id', $email)->exists()) {
            throw UserException::ErreurEmailDejaUtilise($email);
        }
        $user->user_id = $email;
        $user->password = password_hash($password, PASSWORD_DEFAULT);
        $user->role = 1;
        return $user->save();
    }

    public static function authenticate(string $email, string $password)
    {
        try {
            $user = User::where('user_id', $email)->first();
            if ($user && password_verify($password, $user->password)) {
                return ['user_id' => $user->id, 'role' => $user->role];
            }
            return false;
        } catch (\Exception $e) {
            throw UserException::ErreurUtilisateurNonTrouve($email);
        }
    }
}