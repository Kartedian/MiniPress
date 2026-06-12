<?php

declare(strict_types=1);

use Illuminate\Container\Container;
use Psr\Container\ContainerInterface;

/**
 * Wraps Illuminate Container to satisfy Slim's CallableResolver.
 *
 * Illuminate's has() only returns true for explicitly bound abstracts.
 * Slim's CallableResolver falls back to new $class($container) when has()
 * returns false, injecting the container instead of the real dependencies.
 * This wrapper makes has() return true for any instantiable class, so Slim
 * always delegates construction to the container's auto-wiring.
 */
class ContainerWrapper implements ContainerInterface
{
    public function __construct(private readonly Container $container) {}

    public function get(string $id): mixed
    {
        return $this->container->make($id);
    }

    public function has(string $id): bool
    {
        return $this->container->bound($id) || class_exists($id);
    }
}
