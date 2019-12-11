<?php

declare(strict_types=1);

namespace O\WordPress\Rest;

interface UsesResolverInterface
{
    /**
     *
     */
    public function setResolver(callable $resolver): void;

    /**
     *
     */
    public function getResolver(): ?callable;
}
