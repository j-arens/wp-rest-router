<?php declare(strict_types=1);

namespace Downshift\WordPress\Rest;

interface UsesResolverInterface
{
    /**
     *
     */
    public function setResolver(callable $resolver);

    /**
     *
     */
    public function getResolver(): ?callable;
}
