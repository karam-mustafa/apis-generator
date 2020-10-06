<?php

namespace KMLaravel\ApiGenerator\Services;


use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use KMLaravel\ApiGenerator\BuildClasses\KMControllerBuilder;
use KMLaravel\ApiGenerator\BuildClasses\KMModelAndMigrationBuilder;
use KMLaravel\ApiGenerator\BuildClasses\KMRequestBuilder;
use KMLaravel\ApiGenerator\Facade\KMFileFacade;

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
    private $basicBuildOption = [];
    /**
     * @var array
     */
    private $advancedBuildOption = [];
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
        [$this->request , $this->column , $this->apiTitle ] = [$request , $request->column , $request->title];
        // append all basic options that actor want to run migration  to new array
        foreach ($this->request->basic_options as $item => $status) {
            $this->basicBuildOption [] = $item;
        }
        // append all advanced options that actor want to run migration  to new array
        foreach ($this->request->advanced_options as $item => $status) {
            $this->advancedBuildOption[] = $item;
        }
        return $this;
    }

    /**
     * @return GeneratorService
     * this setter function for baseControllerExists property to determine
     * if actor choose to run migration  to install base controller before.
     */
    public function setBaseControllerExists(): GeneratorService
    {
        $this->baseControllerExists = KMFileFacade::baseControllerExists();
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
     * @return \KMLaravel\ApiGenerator\Helpers\KMFile
     * this function is use separate class its work like a services to auto build model and check from
     * migration options and finally push the path for model to paths property to use it when we create
     * controller as final step.
     */
    protected function buildModelAndMigrations()
    {
        $builder = new KMModelAndMigrationBuilder();
        $modelClass = "$this->apiTitle";
        $migrationRequired = in_array("buildMigration" , $this->basicBuildOption) ? "-m" : "";
        $builder->initialResource($modelClass, "modelAndMigrationReplacer")
            ->callArtisan($migrationRequired)
            ->build($this->column, ["migrationRequired" => $migrationRequired]);
        return $this->paths["{{ model_path }}"] = KMFileFacade::getClassNameSpace("model", $modelClass);
    }

    /**
     *
     */
    protected function runBuildBaseController()
    {
        if (!$this->baseControllerExists) {
            file_put_contents(KMFileFacade::baseControllerPath(),
                File::get(KMFileFacade::getFilesFromStubs(/** @scrutinizer ignore-call */"BaseController"))
            );
        }
    }

    /**
     * if actor choose to run migration.
     * **************************************
     * we will update options in next release
     * **************************************
     * @param string $options
     * @return int
     */
    protected function runMigration($options = "")
    {
        return Artisan::call("migrate $options");
    }

    /**
     * @return \KMLaravel\ApiGenerator\Helpers\KMFile
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
        return $this->paths["{{ request_path }}"] = KMFileFacade::getClassNameSpace("Requests", $requestClass);
    }

    /**
     * @return mixed
     * here we build controller ads final step in building process
     * the idea is take all paths that resulted from the previous process ( request ,  model , migrations ..)
     * and its addition to controller path , and then choose  controllerReplacer function to replaces the
     * resource in controller.stub file and append it to controllers folder.
     * *******************************************************
     * we will update option to make actor use if he will used
     * our controller or choose to build custom controller
     * *******************************************************
     */
    protected function buildController()
    {
        $builder = new KMControllerBuilder();
        $controllerClass = "$this->apiTitle" . "Controller";
        $controllerBuildType = !$this->baseControllerExists ? "basicBuild" : "build";
        return $builder->initialResource($controllerClass, "controllerReplacer")
            ->callArtisan()
            ->$controllerBuildType($this->column, array_merge($this->paths, [
                "{{ controller_path }}" => KMFileFacade::getClassNameSpace("Controller", $controllerClass),
            ]));
    }

    /**
     * this auto generate resources file which is use standard laravel resources file
     * and get the path do this resource and push them to paths array
     */
    protected function buildResource()
    {
        Artisan::call("make:resource " . "$this->apiTitle" . "Resource");
        return $this->paths["{{ resource_path }}"] = KMFileFacade::getClassNameSpace("Resources", "$this->apiTitle" . "Resource");
    }

    /**
     * @return \KMLaravel\ApiGenerator\Helpers\KMFile
     * in this function we can register some information about our process result
     * like controller name , url ( will be api title as default ) , and title for this api.
     * *******************************
     * we will add more flexibility to routing process
     * *******************************
     */
    protected function registerCredential()
    {
        $data = [
            "title" => $this->apiTitle,
            "route" => "$this->apiTitle" . "Controller",
            "url" => "$this->apiTitle",
            "name" => "$this->apiTitle",
            "type" => "resource",
        ];
        return KMFileFacade::setDataToCredentialJsonFile($data);
    }
}
