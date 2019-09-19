<?php declare(strict_types=1);

namespace Downshift\WordPress\Rest;

interface RegistersRoutesInterface
{
    /**
     * @return void
     */
    public function listen(): void;
}
