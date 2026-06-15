<?php

namespace Dwm\MiniPress\application_core\domain\exceptions;

use RuntimeException;

class ArticleException extends RuntimeException
{
    public static function tokenInvalide(): self
    {
        return new self("Le token fourni est invalide ou ne correspond à aucune Article.");
    }

    public static function erreurRecuperation(string $message)
    {
        return new self("Erreur article : {$message}");
    }
}
