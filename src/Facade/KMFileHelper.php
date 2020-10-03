<?php

namespace KMLaravel\ApiGenerator\Facade;

use Illuminate\Support\Facades\Facade;


/**
 * @method static \KMLaravel\ApiGenerator\Helpers\KMFileHelper getFilesFromStubs (string $file)
 * @method static \KMLaravel\ApiGenerator\Helpers\KMFileHelper getRequestFile (string $file)
 * @method static \KMLaravel\ApiGenerator\Helpers\KMFileHelper getResourceFile(string $file)
 * @method static \KMLaravel\ApiGenerator\Helpers\KMFileHelper getModelFile(string $file)
 * @method static \KMLaravel\ApiGenerator\Helpers\KMFileHelper getMigrationFile(string $file)
 * @method static \KMLaravel\ApiGenerator\Helpers\KMFileHelper getRequestFileAsStream(string $file)
 * @method static \KMLaravel\ApiGenerator\Helpers\KMFileHelper getModelFileAsStream(string $file)
 * @method static \KMLaravel\ApiGenerator\Helpers\KMFileHelper getClassNameSpace(string $type , mixed $className)
 * @method static \KMLaravel\ApiGenerator\Helpers\KMFileHelper getFilesFromStubsAsStream(string $file)
 * @method static \KMLaravel\ApiGenerator\Helpers\KMFileHelper getCredentialJsonFilePath()
 * @method static \KMLaravel\ApiGenerator\Helpers\KMFileHelper getCredentialJsonFileAsJson()
 * @method static \KMLaravel\ApiGenerator\Helpers\KMFileHelper setDataToCredentialJsonFile(array $newData)
 * @method static \KMLaravel\ApiGenerator\Helpers\KMFileHelper baseControllerExists()
 * @method static \KMLaravel\ApiGenerator\Helpers\KMFileHelper baseControllerPath()
 */
class KMFileHelper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'KMFileHelper';
    }
}
