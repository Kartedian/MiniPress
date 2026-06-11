<?php
namespace Dwm\MiniPress\webui\provider;

class CsrfTokenProvider
{
    /**
     * Génère un token CSRF sécurisé et le stocke en session.
     * @return string Le token généré
     */
    public static function generateToken(): string
    {
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        return $token;
    }

    /**
     * Valide le token CSRF fourni par rapport à celui stocké en session.
     * @param string|null $token Le token à valider
     * @return bool true si le token est valide, false sinon
     */
    public static function validateToken(?string $token): bool
    {
        if (empty($token) || !isset($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }
}