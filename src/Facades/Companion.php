<?php

namespace Xbigdaddyx\Companion\Facades;

use Illuminate\Support\Facades\Facade;

class Companion extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'companion';
    }
}
