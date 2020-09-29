<?php


namespace KMLaravel\ApiGenerator\Helpers;


use Illuminate\Support\Facades\Artisan;
use KMLaravel\ApiGenerator\Facade\KMFileHelper;

class KMModelAndMigrationHelper extends BaseBuilder
{
    protected $typeToMake = "Model";

    /**
     * @param string $option
     * @return KMModelAndMigrationHelper
     */
    public function callArtisan($option = ''): KMModelAndMigrationHelper
    {
        $command = strtolower($this->typeToMake);
        Artisan::call("make:$command $this->fileName $option");
        return $this;
    }
}
