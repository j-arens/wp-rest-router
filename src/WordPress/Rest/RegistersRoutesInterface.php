<?php

declare(strict_types=1);

namespace O\WordPress\Rest;

interface RegistersRoutesInterface
{
    /**
     * @return void
     */
    public function listen(): void;
}
