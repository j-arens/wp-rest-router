<?php

use Kahlan\Filter\Filters;

Filters::apply($this, 'bootstrap', function ($next) {
    require __DIR__ . '/wp-mocks.php';
    return $next();
});
