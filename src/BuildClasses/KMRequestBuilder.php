<?php


namespace KMLaravel\ApiGenerator\BuildClasses;

use Illuminate\Support\Facades\Artisan;

class KMRequestBuilder extends KMBaseBuilder
{
    protected $typeToMake = "Request";

    /**
     * @param string $option
     * @return KMRequestBuilder
     */
    public function callArtisan($option = ''): KMRequestBuilder
    {
        $command = strtolower($this->typeToMake);
        Artisan::call("make:$command $this->fileName $option");
        return $this;
    }
}
