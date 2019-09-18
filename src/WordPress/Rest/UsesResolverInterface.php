<?php declare(strict_types=1);

namespace Downshift\WordPress\Rest;

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
