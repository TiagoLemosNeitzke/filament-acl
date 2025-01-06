<?php

namespace TiagoLemosNeitzke/FilamentAcl\FilamentAcl\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \TiagoLemosNeitzke/FilamentAcl\FilamentAcl\FilamentAcl
 */
class FilamentAcl extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \TiagoLemosNeitzke/FilamentAcl\FilamentAcl\FilamentAcl::class;
    }
}
