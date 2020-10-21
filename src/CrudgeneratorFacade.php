<?php

namespace Codegaf\Crudgenerator;

use Illuminate\Support\Facades\Facade;

/**
 * @see \10codesoftware\Crudgenerator\Skeleton\SkeletonClass
 */
class CrudgeneratorFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'crudgenerator';
    }
}
