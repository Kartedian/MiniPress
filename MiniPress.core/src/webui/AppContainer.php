<?php

namespace Dwm\MiniPress\webui;

use Illuminate\Container\Container;

/**
 * Étend le container Illuminate pour que Slim 4 puisse résoudre
 * n'importe quelle classe concrète par auto-wiring, pas seulement
 * les classes explicitement enregistrées.
 *
 * Slim 4 appelle has() avant de passer au container ; le Container
 * Illuminate ne renvoie true que pour les classes liées explicitement.
 * Ici on accepte aussi tout classe concrète existante.
 */
class AppContainer extends Container
{
    public function has(string $id): bool
    {
        return parent::has($id) || class_exists($id);
    }
}
