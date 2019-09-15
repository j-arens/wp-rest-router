<?php

use Kahlan\Filter\Filters;

Filters::apply($this, 'bootstrap', function ($next) {
    require __DIR__ . '/functions.php';
    return $next();
});
