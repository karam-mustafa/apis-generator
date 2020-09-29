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
    /**
     * @var
     */
    private $checkIfBaseControllerExists;
    private $baseControllerPath = "Http/Controllers/BaseController.php";
    private $buildOption;
    private $apiTitle;
    private $column;
    private $modelPath;
    private $requestPath;
    private $resourcePath;

    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * @return GeneratorFactory
     */
    protected function setBuildOption(): GeneratorFactory
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
    protected function setCheckIfBaseControllerExists(): GeneratorFactory
    {
        $this->checkIfBaseControllerExists = File::exists(app_path($this->baseControllerPath));
        return $this;
    }

    /**
     * @return bool
     */
    protected function buildOption()
    {
        return $this->request->options !== null;
    }

    /**
     * @param $request
     */
    public function generateApi($request)
    {
        $this->checkValidation($request)->setBuildOption()->setCheckIfBaseControllerExists();
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
        $modelPath = $builder->initialResource("$this->apiTitle", "buildColumnInModelWithMigration")
            ->callArtisan('-m')
            ->build($this->column);
        $this->modelPath = $modelPath;
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
        $requestPath = $builder->initialResource("$this->apiTitle" . "Request", "replaceRulesWithRulesOptions")
            ->callArtisan()
            ->build($this->column);
        return $this->requestPath = $requestPath;
    }

    protected function buildController()
    {

    }

    /**
     *
     */
    protected function buildResource()
    {
        Artisan::call("make:resource " . "$this->apiTitle" . "Resource");
        return $this->resourcePath = KMFileHelper::getResourceFile("$this->apiTitle" . "Resource.php");
    }

    protected function checkValidation($request)
    {
        $request->validated();
        $this->apiTitle = ucfirst($this->request->title);
        foreach ($this->request->column as $name => $col) {
            $newName = str_replace(" ", "_", $name);
            $this->column[$newName] = $col;
        }
        return $this;
    }
}
