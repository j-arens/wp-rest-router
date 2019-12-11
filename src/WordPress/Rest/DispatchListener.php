<?php

declare(strict_types=1);

namespace O\WordPress\Rest;

class DispatchListener
{
    /**
     * @var bool
     */
    protected $didDispatch = false;

    /**
     *
     */
    public function __construct()
    {
        add_filter('rest_pre_dispatch', [$this, 'onDispatch'], PHP_INT_MAX);
    }

    /**
     *
     */
    public function onDispatch($_)
    {
        $this->didDispatch = true;
        return $_;
    }

    /**
     *
     */
    public function dispatched(): bool
    {
        return $this->didDispatch;
    }
}
