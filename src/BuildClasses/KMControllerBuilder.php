<?php


namespace KMLaravel\ApiGenerator\BuildClasses;


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
        "{{ controller_namespace }}" => [],
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
        "{{ controller_namespace }}" => "{{ controller_namespace }}",
    ];

    /**
     * @param array $columns
     * @param array $options
     */
    public function controllerReplacer($columns, $options = [])
    {
        // build paths and classes
        $this->classReplacer($options);
        $file = KMFileFacade::getFilesFromStubsAsStream("Controller");
        // get controller path we create in callArtisan function from Controllers Folder to inject paths and classes
        $fileInApp = app_path("Http/Controllers/$this->fileName.php");
        $this->pathsAndClassesReplacer($file, $fileInApp);
    }

    /**
     * @param array $column
     * @param array $options
     * @return mixed
     */
    public function build($column, $options = [])
    {
        $this->controllerReplacer($column, $options);
        return $this->filepath;
    }
    /**
     * @param array $column
     * @param array $options
     * @return mixed
     * @desc this function run as simply artisan make:controller command if the base controller dose not exits.
     */
    public function basicBuild($column, $options = [])
    {
        return $this->filepath;
    }

    /**
     * @param array $option
     * @desc
     * Convert options to array to use foreach at all
     * Find intersecting values between paths and actor inputs
     * get full class namespace from options
     * get class name and fill paths array
     */
    protected function classReplacer($option)
    {
        if (!gettype($option) == 'array') $option = [$option];
        $controllerNameSpace = $this->checkIfFileInSubFolder("App\Http\Controllers" , $this->fileName);
        $option['{{ controller_namespace }}'] = $controllerNameSpace;
        foreach ($option as $path => $classNameSpace) {
            $className = $this->getCLassName($classNameSpace);
            $classNameSpace = str_replace('/' , '\\' , $classNameSpace);
            $className = $this->array_last_value(explode('/' , $className));
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
            $nameSpace = array_key_first($values) ?? "{{ chose_your_name_space_here }}";
            $classType = $this->classes[$itemToReplace];
            $class = $values[$nameSpace] ?? "{{ chose_your_class_here }}";
            $fileReplaced = str_replace([$itemToReplace, $classType], [$nameSpace, $class], $newFile);
            $newFile = $fileReplaced;
        }
        return file_put_contents($fileInApp, $newFile);
    }
}
