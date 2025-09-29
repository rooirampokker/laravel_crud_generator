<?php

namespace Rampokker\CrudScaffold\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Rampokker\CrudScaffold\LaravelCrudGenerator
 */
class LaravelCrudGenerator extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Rampokker\CrudScaffold\LaravelCrudGenerator::class;
    }
}
