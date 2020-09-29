<?php


namespace KMLaravel\ApiGenerator\Helpers;
use Illuminate\Support\Facades\Artisan;
use KMLaravel\ApiGenerator\Facade\KMFileHelper;

class KMRequestBuilder extends BaseBuilder
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
