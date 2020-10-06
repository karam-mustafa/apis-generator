<?php

namespace KMLaravel\ApiGenerator\Facade;

use Illuminate\Support\Facades\Facade;


/**
 * @method static \KMLaravel\ApiGenerator\Helpers\KMRoutes getRoutes ()
 **/
class KMRoutesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'KMRoutesFacade';
    }
}
