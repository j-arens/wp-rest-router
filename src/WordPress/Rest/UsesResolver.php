<?php

declare(strict_types=1);

namespace O\WordPress\Rest;

trait UsesResolver
{
    /**
     *
     */
    public function setResolver(callable $resolver): void
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
