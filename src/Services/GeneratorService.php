<?php

namespace KMLaravel\ApiGenerator\Services;


use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use KMLaravel\ApiGenerator\BuildClasses\KMControllerBuilder;
use KMLaravel\ApiGenerator\BuildClasses\KMModelAndMigrationBuilder;
use KMLaravel\ApiGenerator\BuildClasses\KMRequestBuilder;
use KMLaravel\ApiGenerator\Facade\KMFileHelper;

class GeneratorService
{

    /**
     * @var object
     */
    private $request;
    /**
     * @var bool
     */
    private $baseControllerExists;
    /**
     * @var array
     */
    private $basicBuildOption;
    /**
     * @var array
     */
    private $advancedBuildOption;
    /**
     * @var string
     */
    private $apiTitle;
    /**
     * @var array
     */
    private $column;
    /**
     * @var array
     */
    private $paths;

    /**
     * @param $request
     * @return GeneratorService
     * @desc this function initialize request object which has been validated from controller and assigned all data.
     *
     */
    public function initialRequest($request): GeneratorService
    {
        $this->request = $request;
        $this->column = $request->column;
        $this->apiTitle = $request->title;
        $basicBuildOptionWithPrefix = [];
        $advancedBuildOptionWithPrefix = [];
        // append all basic options that actor want to run migration  to new array
        foreach ($this->request->basic_options as $item => $status) {
            $basicBuildOptionWithPrefix[] = $item;
        }
        $this->basicBuildOption = $basicBuildOptionWithPrefix;
        // append all advanced options that actor want to run migration  to new array
        foreach ($this->request->advanced_options as $item => $status) {
            $advancedBuildOptionWithPrefix[] = $item;
        }
        $this->advancedBuildOption = $advancedBuildOptionWithPrefix;
        return $this;
    }

    /**
     * @return GeneratorService
     * this setter function for baseControllerExists property to determine
     * if actor choose to run migration  to install base controller before.
     */
    public function setBaseControllerExists(): GeneratorService
    {
        $this->baseControllerExists = KMFileHelper::baseControllerExists();
        return $this;
    }

    /**
     * this main function which process is distributed throughout functions available in this current class
     * the function is check if this function is exists and this function ame came from actor options to run
     * migration which is loaded from api_generator config file.
     */
    public function generateApi()
    {
        $this->setBaseControllerExists();
        // merge basic options and advanced options.
        //we here want to be advanced options last options to run inside foreach.
        $options = array_merge($this->basicBuildOption , $this->advancedBuildOption);
        foreach ($options as $option) {
            if (method_exists(__CLASS__, $option)) {
                $this->$option();
            }
        }
        // of course we will build controller as a last step
        // because now we have all paths we need
        $this->buildController();
        // at the end we will register some data to credential file in assets
        $this->registerCredential();
    }

    /**
     * @return \KMLaravel\ApiGenerator\Helpers\KMFileHelper
     * this function is use separate class its work like a services to auto build model and check from
     * migration options and finally push the path for model to paths property to use it when we create
     * controller as final step.
     */
    protected function buildModelAndMigrations()
    {
        $builder = new KMModelAndMigrationBuilder();
        $modelClass = "$this->apiTitle";
        $migrationRequired = in_array("buildMigration" , $this->basicBuildOption);
        $builder->initialResource($modelClass, "modelAndMigrationReplacer")
            ->callArtisan($migrationRequired ? '-m' : '')
            ->build($this->column, ["migrationRequired" => $migrationRequired]);
        return $this->paths["{{ model_path }}"] = KMFileHelper::getClassNameSpace("model", $modelClass);
    }

    /**
     *
     */
    protected function runBuildBaseController()
    {
        if (!$this->baseControllerExists) {
            file_put_contents(KMFileHelper::baseControllerPath(),
                File::get(KMFileHelper::getFilesFromStubs("BaseController"))
            );
        }
    }

    /**
     * if actor choose to run migration.
     */
    protected function runMigration()
    {
        return Artisan::call("migrate");
    }

    /**
     * @return \KMLaravel\ApiGenerator\Helpers\KMFileHelper
     * this function is used KMRequestBuilder separate class its work like a services to auto build request and
     * put the rule which actor is selected and finally push the path for model to paths property to use it when
     * we create controller as final step.
     */
    protected function buildRequests()
    {
        $builder = new KMRequestBuilder();
        $requestClass = "$this->apiTitle" . "Request";
        $builder->initialResource($requestClass, "requestReplacer")
            ->callArtisan()
            ->build($this->column);
        return $this->paths["{{ request_path }}"] = KMFileHelper::getClassNameSpace("Requests", $requestClass);
    }

    /**
     * @return mixed
     * here we build controller ads final step in building process
     * the idea is take all paths that resulted from the previous process ( request ,  model , migrations ..)
     * and its addition to controller path , and then choose  controllerReplacer function to replaces the
     * resource in controller.stub file and append it to controllers folder.
     */
    protected function buildController()
    {
        $builder = new KMControllerBuilder();
        $controllerClass = "$this->apiTitle" . "Controller";
        return $builder->initialResource($controllerClass, "controllerReplacer")
            ->callArtisan()
            ->build($this->column, array_merge($this->paths, [
                "{{ controller_path }}" => KMFileHelper::getClassNameSpace("Controller", $controllerClass),
            ]));
    }

    /**
     * this auto generate resources file which is use standard laravel resources file
     * and get the path do this resource and push them to paths array
     */
    protected function buildResource()
    {
        Artisan::call("make:resource " . "$this->apiTitle" . "Resource");
        return $this->paths["{{ resource_path }}"] = KMFileHelper::getClassNameSpace("Resources", "$this->apiTitle" . "Resource");
    }

    /**
     * @return \KMLaravel\ApiGenerator\Helpers\KMFileHelper
     * in this function we can register some information about our process result
     * like controller name , url ( will be api title as default ) , and title for this api.
     * @note we will develop route later
     */
    protected function registerCredential()
    {
        $data = [
            $this->apiTitle => [
                "route" => "$this->apiTitle" . "Controller",
                "url" => "$this->apiTitle",
                "name" => "$this->apiTitle",
            ]
        ];
        return KMFileHelper::setDataToCredentialJsonFile($data);
    }
}
