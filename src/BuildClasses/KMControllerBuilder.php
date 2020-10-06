<?php


namespace KMLaravel\ApiGenerator\BuildClasses;


use Illuminate\Support\Facades\Artisan;
use KMLaravel\ApiGenerator\Facade\KMFileFacade;

class KMControllerBuilder extends KMBaseBuilder
{
    /**
     * @var string
     */
    protected $typeToMake = "Controller";
    /**
     * @var array
     */
    protected $paths = [
        "{{ model_path }}" => [],
        "{{ resource_path }}" => [],
        "{{ request_path }}" => [],
        "{{ controller_path }}" => [],
    ];
    /**
     * @var array
     * @desc this property use keys in paths property to determine which path we will replace with class
     * @example if we want to replace {{ model_class }} in controller.stub , so we will get $this->classes['{{ model_path }}']
     * you will find full logic process in pathsAndClassesReplacer function
     */
    protected $classes = [
        "{{ model_path }}" => "{{ model_class }}",
        "{{ resource_path }}" => "{{ resource_class }}",
        "{{ request_path }}" => "{{ request_class }}",
        "{{ controller_path }}" => "{{ controller_class }}",
    ];

    /**
     * @param string $option
     * @return KMControllerBuilder
     */
    public function callArtisan($option = ''): KMControllerBuilder
    {
        $command = strtolower($this->typeToMake);
        Artisan::call("make:$command $this->fileName $option");
        return $this;
    }

    /**
     * @param $columns
     * @param array $options
     */
    public function controllerReplacer($columns, $options = [])
    {
        // build paths and classes
        $this->classReplacer($options);
        $file = KMFileFacade::getFilesFromStubsAsStream("Controller");
        // get controller path we create in callArtisan function from Controllers Folder to inject paths and classes
        $fileInApp = /** @scrutinizer ignore-call */app_path("Http/Controllers/$this->fileName.php");
        $this->pathsAndClassesReplacer($file, $fileInApp);
    }

    /**
     * @param $column
     * @param array $options
     * @return mixed
     */
    public function build($column, $options = [])
    {
        $this->controllerReplacer($column, $options);
        return $this->filepath;
    }
    /**
     * @param $column
     * @param array $options
     * @return mixed
     * @desc this function run as simply artisan make:controller command if the base controller dose not exits.
     */
    public function basicBuild($column, $options = [])
    {
        return $this->filepath;
    }

    /**
     * @param $option
     * @desc
     * Convert options to array to use foreach at all
     * Find intersecting values between paths and actor inputs
     * get full class namespace from options
     * get class name and fill paths array
     */
    protected function classReplacer($option)
    {
        if (!gettype($option) == 'array') $option = [$option];
        $this->paths = array_intersect_key($this->paths, $option);
        foreach ($option as $path => $classNameSpace) {
            $className = $this->getCLassName($classNameSpace);
            $this->paths[$path] = [
                $classNameSpace => $className,
            ];
        }
    }

    /**
     * @param string|mixed $file
     * @param mixed | string $fileInApp
     * @return false|int
     * @desc the function is doing :
     * get the namespace from path which is now the main keys in this array
     * @example
     * $paths = [
     * "App\User" => "User"
     * ]
     * then get this class "User"
     * and we put this in last file we modified in $newFile variable
     */
    protected function pathsAndClassesReplacer($file, $fileInApp)
    {
        // set default value for file , and at next loop we will override this value for build this controller
        $newFile = $file;
        foreach ($this->paths as $itemToReplace => $values) {
            $nameSpace = array_key_first($values);
            $classType = $this->classes[$itemToReplace];
            $class = $values[$nameSpace];
            $fileReplaced = str_replace([$itemToReplace, $classType], [$nameSpace, $class], $newFile);
            $newFile = $fileReplaced;
        }
        return file_put_contents($fileInApp, $newFile);
    }
}
