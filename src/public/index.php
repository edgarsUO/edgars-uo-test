<?php

use App\CacheKernel;
use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    $kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);

    if ('prod' === $kernel->getEnvironment()) {
        $kernel = new CacheKernel($kernel);
    }
    return $kernel;
};
