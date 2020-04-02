<?php

namespace Mvdnbrk\Documentation\Facades;

use Illuminate\Support\Facades\Facade;

class Documentation extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'documentation';
    }
}
