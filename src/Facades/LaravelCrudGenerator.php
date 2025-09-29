<?php

namespace Rampokker\LaravelCrudGenerator\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Rampokker\LaravelCrudGenerator\LaravelCrudGenerator
 */
class LaravelCrudGenerator extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Rampokker\LaravelCrudGenerator\LaravelCrudGenerator::class;
    }
}
