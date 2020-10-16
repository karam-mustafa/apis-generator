<?php


namespace KMLaravel\ApiGenerator\Facade;


use Illuminate\Support\Facades\Facade;

/**
 * @method  static \KMLaravel\ApiGenerator\Helpers\KMGeneratorCommand getReservedNames()
 */
class KMGeneratorCommandFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'KMGeneratorCommandFacade';
    }
}
