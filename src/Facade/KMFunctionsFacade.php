<?php


namespace KMLaravel\ApiGenerator\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \KMLaravel\ApiGenerator\Helpers\KMFunctions getMiddleware()
 * @method static \KMLaravel\ApiGenerator\Helpers\KMFunctions getRequestAuthAccessibility()
 */
class KMFunctionsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'KMFunctionsFacade';
    }
}
