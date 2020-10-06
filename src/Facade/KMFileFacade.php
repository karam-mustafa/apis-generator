<?php

namespace KMLaravel\ApiGenerator\Facade;

use Illuminate\Support\Facades\Facade;


/**
 * @method static \KMLaravel\ApiGenerator\Helpers\KMFile getFilesFromStubs (string $file)
 * @method static \KMLaravel\ApiGenerator\Helpers\KMFile getRequestFile (string $file)
 * @method static \KMLaravel\ApiGenerator\Helpers\KMFile getResourceFile(string $file)
 * @method static \KMLaravel\ApiGenerator\Helpers\KMFile getModelFile(string $file)
 * @method static \KMLaravel\ApiGenerator\Helpers\KMFile getMigrationFile(string $file)
 * @method static \KMLaravel\ApiGenerator\Helpers\KMFile getRequestFileAsStream(string $file)
 * @method static \KMLaravel\ApiGenerator\Helpers\KMFile getModelFileAsStream(string $file)
 * @method static \KMLaravel\ApiGenerator\Helpers\KMFile getClassNameSpace(string $type , mixed $className)
 * @method static \KMLaravel\ApiGenerator\Helpers\KMFile getFilesFromStubsAsStream(string $file)
 * @method static \KMLaravel\ApiGenerator\Helpers\KMFile getCredentialJsonFilePath()
 * @method static \KMLaravel\ApiGenerator\Helpers\KMFile getCredentialJsonFileAsJson()
 * @method static \KMLaravel\ApiGenerator\Helpers\KMFile setDataToCredentialJsonFile(array $newData)
 * @method static \KMLaravel\ApiGenerator\Helpers\KMFile baseControllerExists()
 * @method static \KMLaravel\ApiGenerator\Helpers\KMFile baseControllerPath()
 */
class KMFileFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'KMFileFacade';
    }
}
