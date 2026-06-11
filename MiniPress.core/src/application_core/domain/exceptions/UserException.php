<?php

namespace Dwm\MiniPress\application_core\domain\exceptions;

use RuntimeException;

class UserException extends RuntimeException
{
    public static function ErreurEmailDejaUtilise(string $email): self
    {
        return new self("L'email '{$email}' est déjà utilisé par un autre utilisateur.");
    }

    public static function ErreurMotDePasseInvalide(): self
    {
        return new self("Le mot de passe fourni est invalide.");
    }

    public static function ErreurUtilisateurNonTrouve(string $email): self
    {
        return new self("Aucun utilisateur trouvé avec l'email '{$email}'.");
    }
}