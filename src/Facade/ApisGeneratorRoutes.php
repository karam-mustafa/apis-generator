<?php

namespace KMLaravel\ApiGenerator\Facade;

use Illuminate\Support\Facades\Facade;


/**
 * @method static \KMLaravel\ApiGenerator\Routes\ApisGeneratorRoutes getRoutes ()
 **/
class ApisGeneratorRoutes extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ApisGeneratorRoutes';
    }
}
