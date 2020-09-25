<?php

namespace KMLaravel\ApiGenerator\Helpers;


use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use KMLaravel\ApiGenerator\Facade\KMFileHelper;

class GeneratorFactory
{
    /**
     * @var
     */
    private $request;
    private $checkIfBaseControllerExists;
    private $baseControllerPath = "Http/Controllers/BaseController.php";
    private $buildOption;
    private $apiTitle;
    private $column;

    public function __construct($request)
    {
        $this->request = $request;
        $this->apiTitle = ucfirst($this->request->title);
        foreach ($this->request->column as $name => $col) {
            $newName = str_replace(" ", "_", $name);
            $this->column[$newName] = $col;
        }
        return $this;
    }

    /**
     * @return GeneratorFactory
     */
    public function setBuildOption(): GeneratorFactory
    {
        $buildOptionWithPrefix = [];
        foreach ($this->request->options as $item => $status) {
            $buildOptionWithPrefix[] = $item;
        }
        $this->buildOption = $buildOptionWithPrefix;
        return $this;
    }

    /**
     * @return GeneratorFactory
     */
    public function setCheckIfBaseControllerExists(): GeneratorFactory
    {
        $this->checkIfBaseControllerExists = File::exists(app_path($this->baseControllerPath));
        return $this;
    }
    public function checkValidate()
    {
        $validatedData = [
            "title" => "required|string",
            "options" => "required",
            "column" => "required",
        ];
        if (isset($this->request->column)) {
            foreach ($this->request->column as $column) {
                foreach ($column as $name => $item) {
                    if (!isset($item['type'])) {
                        $validatedData['type'] = "type for column $name is required";
                    }
                }
            }
        }
        $this->request->validate($validatedData);
        return $this;
    }

    /**
     * @return bool
     */
    public function buildOption()
    {
        return $this->request->options !== null;
    }

    /**
     *
     */
    public function generateApi()
    {
        foreach ($this->buildOption as $item) {
            if (method_exists(__CLASS__, $item)) {
                $this->$item();
            }
        }
    }

    /**
     *
     */
    protected function buildMigrations()
    {
        $builder = new KMModelAndMigrationHelper();
        $builder->initialFiles("$this->apiTitle")
            ->callArtisan('-m')
            ->updatePaths()
            ->build($this->column);
    }

    /**
     *
     */
    protected function buildBaseController()
    {
        if (!$this->checkIfBaseControllerExists) {
            file_put_contents(
                app_path($this->baseControllerPath),
                File::get(KMFileHelper::getFilesFromStubs("BaseController.stub"))
            );
        }
    }

    /**
     *
     */
    protected function buildRequests()
    {
        $builder = new KMRequestBuilder();
        $builder->initialFiles("$this->apiTitle" . "Request")
            ->callArtisan()
            ->updatePaths()
            ->build($this->column);
    }

    /**
     *
     */
    protected function buildResource()
    {
        Artisan::call("make:resource " . "$this->apiTitle" . "Resource");
    }
}
