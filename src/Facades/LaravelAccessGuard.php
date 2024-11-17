<?php

namespace Sharqlabs\LaravelAccessGuard\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Sharqlabs\LaravelAccessGuard\LaravelAccessGuard
 */
class LaravelAccessGuard extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Sharqlabs\LaravelAccessGuard\LaravelAccessGuard::class;
    }
}
