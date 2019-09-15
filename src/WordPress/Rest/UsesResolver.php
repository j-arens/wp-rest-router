<?php declare(strict_types=1);

namespace Downshift\WordPress\Rest;

trait UsesResolver
{
    /**
     *
     */
    public function setResolver(callable $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     *
     */
    public function getResolver(): ?callable
    {
        return $this->resolver;
    }
}
