<?php

namespace TiagoLemosNeitzke\FilamentAcl;

use Illuminate\Support\Facades\Facade;

class FilamentAcl extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'filament-acl';
    }
}
