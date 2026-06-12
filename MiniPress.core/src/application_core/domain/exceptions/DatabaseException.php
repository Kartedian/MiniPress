<?php

namespace Dwm\MiniPress\application_core\domain\exceptions;

use RuntimeException;

class DatabaseException extends RuntimeException
{
    public static function erreurRecuperation(string $message): self
    {
        return new self("Erreur database : {$message}");
    }
}
