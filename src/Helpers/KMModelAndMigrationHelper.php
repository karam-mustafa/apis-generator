<?php


namespace KMLaravel\ApiGenerator\Helpers;


use Illuminate\Support\Facades\Artisan;
use KMLaravel\ApiGenerator\Facade\KMFileHelper;

class KMModelAndMigrationHelper extends BaseBuilder
{
    protected $typeToMake = "Model";
    protected $fileName;
    protected $filepath;
    protected $fileToCreate;

    public function initialFiles($fileName): KMModelAndMigrationHelper
    {
        $this->fileName = $fileName;
        return $this;
    }

    public function updatePaths(): KMModelAndMigrationHelper
    {
        $this->filepath = KMFileHelper::getModelFile("$this->fileName.php");
        $this->fileToCreate = KMFileHelper::getModelFileAsStream("$this->fileName.php");
        return $this;
    }

    public function callArtisan($option = ''): KMModelAndMigrationHelper
    {
        $command = strtolower($this->typeToMake);
        Artisan::call("make:$command $this->fileName $option");
        return $this;
    }

    public function build($column)
    {
        $this->buildColumnInModelWithMigration($this->fileToCreate, $this->filepath, $column);
    }
}
