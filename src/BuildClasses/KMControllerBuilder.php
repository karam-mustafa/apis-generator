<?php


namespace KMLaravel\ApiGenerator\BuildClasses;


use Illuminate\Support\Facades\Artisan;

class KMControllerBuilder extends KMBaseBuilder
{
    protected $typeToMake = "Controller";
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
//        dd($options);
    }
    /**
     * @param $column
     * @param array $options
     * @return mixed
     */
    public function build($column,$options = [])
    {
        $this->controllerReplacer($column, $options);
        return $this->filepath;
    }
}
