<?php


namespace KMLaravel\ApiGenerator\Helpers;
use Illuminate\Support\Facades\Artisan;
use KMLaravel\ApiGenerator\Facade\KMFileHelper;

class KMRequestBuilder extends BaseBuilder
{
    protected $typeToMake = "Request";
    protected $fileName;
    protected $filepath;
    protected $fileToCreate;

    public function initialFiles($fileName): KMRequestBuilder
    {
        $this->fileName = $fileName;
        return $this;
    }

    public function updatePaths(): KMRequestBuilder
    {
        $this->filepath = KMFileHelper::getRequestFile("$this->fileName.php");
        $this->fileToCreate = KMFileHelper::getRequestFileAsStream("$this->fileName.php");
        return $this;
    }

    public function callArtisan($option = ''): KMRequestBuilder
    {
        $command = strtolower($this->typeToMake);
        Artisan::call("make:$command $this->fileName $option");
        return $this;
    }

    public function build($column)
    {
        $this->replaceRulesWithRulesOptions($this->fileToCreate, $this->filepath, $column);
    }

}
