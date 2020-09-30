<?php

namespace KMLaravel\ApiGenerator\Services;


use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use KMLaravel\ApiGenerator\BuildClasses\KMControllerBuilder;
use KMLaravel\ApiGenerator\BuildClasses\KMModelAndMigrationBuilder;
use KMLaravel\ApiGenerator\BuildClasses\KMRequestBuilder;
use KMLaravel\ApiGenerator\Facade\KMFileHelper;
use KMLaravel\ApiGenerator\Requests\ApisGeneratorRequest;

class GeneratorService
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

    public function initialRequest($request): GeneratorService
    {
        $this->request = $request;
        $this->column = $request->column;
        $this->apiTitle = $request->title;
        $buildOptionWithPrefix = [];
        foreach ($this->request->options as $item => $status) {
            $buildOptionWithPrefix[] = $item;
        }
        $this->buildOption = $buildOptionWithPrefix;
        return $this;
    }
    /**
     * @return GeneratorService
     */
    protected function setCheckIfBaseControllerExists(): GeneratorService
    {
        $this->checkIfBaseControllerExists = File::exists(app_path($this->baseControllerPath));
        return $this;
    }

    /**
     */
    public function generateApi()
    {
        $this->setCheckIfBaseControllerExists();
        foreach ($this->buildOption as $item) {
            if (method_exists(__CLASS__, $item)) {
                $this->$item();
            }
        }
        $this->buildController();
    }

    /**
     *
     */
    protected function buildMigrations()
    {
        $builder = new KMModelAndMigrationBuilder();
        $modelPath = $builder->initialResource("$this->apiTitle", "modelAndMigrationReplacer")
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
        $requestPath = $builder->initialResource("$this->apiTitle" . "Request", "requestReplacer")
            ->callArtisan()
            ->build($this->column);
        return $this->requestPath = $requestPath;
    }

    protected function buildController()
    {
        $builder = new KMControllerBuilder();
        $builder->initialResource("$this->apiTitle" . "Controller", "controllerReplacer")
            ->callArtisan()
            ->build($this->column,
                [
                    "model_path" => $this->modelPath ,
                    "resource_path" => $this->resourcePath ,
                    "request_path" => $this->requestPath
                ]);
    }

    /**
     *
     */
    protected function buildResource()
    {
        Artisan::call("make:resource " . "$this->apiTitle" . "Resource");
        return $this->resourcePath = KMFileHelper::getResourceFile("$this->apiTitle" . "Resource.php");
    }
}
