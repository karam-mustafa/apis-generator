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
    private $paths;

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
        $this->registerCredential();
    }

    /**
     *
     */
    protected function buildMigrations()
    {
        $builder = new KMModelAndMigrationBuilder();
        $modelClass = "$this->apiTitle";
        $builder->initialResource($modelClass, "modelAndMigrationReplacer")
            ->callArtisan('-m')
            ->build($this->column);
        $this->paths["{{ model_path }}"] = KMFileHelper::getClassNameSpace("model", $modelClass);
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
        $requestClass = "$this->apiTitle" . "Request";
        $builder->initialResource($requestClass, "requestReplacer")
            ->callArtisan()
            ->build($this->column);
        $this->paths["{{ request_path }}"] = KMFileHelper::getClassNameSpace("Requests", $requestClass);
    }

    protected function buildController()
    {
        $builder = new KMControllerBuilder();
        $controllerClass = "$this->apiTitle" . "Controller";
         $builder->initialResource($controllerClass, "controllerReplacer")
            ->callArtisan()
            ->build($this->column, array_merge( $this->paths, [
                "{{ controller_path }}" => KMFileHelper::getClassNameSpace("Controller", $controllerClass),
            ]));
    }

    /**
     *
     */
    protected function buildResource()
    {
        Artisan::call("make:resource " . "$this->apiTitle" . "Resource");
        return $this->paths["{{ resource_path }}"] = KMFileHelper::getClassNameSpace("Resources", "$this->apiTitle" . "Resource");
    }
    /**
     *
     */
    protected function registerCredential()
    {
        $data = [
            $this->apiTitle => [
                "route" => "$this->apiTitle" . "Controller",
                "url" =>  "$this->apiTitle",
                "name" =>  "$this->apiTitle",
            ]
        ];
       return KMFileHelper::setDataToCredentialJsonFile($data);
    }
}
