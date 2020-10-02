<?php


namespace KMLaravel\ApiGenerator\BuildClasses;


use Illuminate\Support\Facades\Artisan;
use KMLaravel\ApiGenerator\Facade\KMFileHelper;

class KMControllerBuilder extends KMBaseBuilder
{
    protected $typeToMake = "Controller";
    protected $paths = [
        "{{ model_path }}" => [],
        "{{ resource_path }}" => [],
        "{{ request_path }}" => [],
        "{{ controller_path }}" => [],
    ];
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

    public function controllerReplacer($columns, $options = [])
    {
        $this->classReplacer($options);
        $file = KMFileHelper::getFilesFromStubsAsStream("Controller");
        $fileInApp = app_path("Http/Controllers/$this->fileName.php");
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

    protected function classReplacer($option)
    {
        if (!gettype($option) == 'array') $option = [$option];
        $this->paths = array_intersect_key($this->paths , $option);
        foreach ($option as $path => $classNameSpace) {
            $fullNameSpaceClass = explode('\\', $classNameSpace);
            $lastWord = array_key_last(explode('\\', $classNameSpace));
            $className = $this->array_first_value(explode('\\', $fullNameSpaceClass[$lastWord]));
            $this->paths[$path] = [
                $classNameSpace => $className,
            ];
        }
    }

    protected function pathsAndClassesReplacer($file, $fileInApp)
    {
        $newFile = $file;
        foreach ($this->paths as $itemToReplace => $values) {
            $nameSpace = array_key_first($values);
            $classType = $this->classes[$itemToReplace];
            $class = $values[$nameSpace];
            $fileReplaced = str_replace([$itemToReplace , $classType], [$nameSpace, $class], $newFile);
            $newFile = $fileReplaced;
        }
        return file_put_contents($fileInApp, $newFile);
    }

    protected function array_first_value($array)
    {
        return $array[0];
    }
}
