<?php


namespace KMLaravel\ApiGenerator\BuildClasses;

use Illuminate\Support\Facades\Artisan;

class KMModelAndMigrationBuilder extends KMBaseBuilder
{
    protected $typeToMake = "Model";

    /**
     * @param string $option
     * @return KMModelAndMigrationBuilder
     */
    public function callArtisan($option = ''): KMModelAndMigrationBuilder
    {
        $command = strtolower($this->typeToMake);
        Artisan::call("make:$command $this->fileName $option");
        return $this;
    }
}
