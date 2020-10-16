<?php

namespace KMLaravel\ApiGenerator\Providers;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use KMLaravel\ApiGenerator\Helpers\KMFile;
use KMLaravel\ApiGenerator\Helpers\KMFunctions;
use KMLaravel\ApiGenerator\Helpers\KMGeneratorCommand;
use KMLaravel\ApiGenerator\Helpers\KMRoutes;

class ApisGeneratorServiceProviders extends ServiceProvider
{

    public function boot(){
        $this->registerFacades();
        $this->publishesPackages();
        $this->loadResource();

    }

    public function register(){
    }

    /**
     *
     */
    protected function publishesBaseControllers()
    {

    }

    /**
     *
     */
    protected function registerFacades()
    {
        $this->app->singleton("KMFileFacade" , function ($app){
            return new KMFile();
        });
        $this->app->singleton("KMRoutesFacade" , function ($app){
            return new KMRoutes();
        });
        $this->app->singleton("KMFunctionsFacade" , function ($app){
            return new KMFunctions();
        });
        $this->app->singleton("KMGeneratorCommandFacade" , function ($app){
            return new KMGeneratorCommand();
        });
    }

    /**
     *
     */
    protected function publishesPackages()
    {
        $asset = __DIR__."/../Asset/";
        $views = "views/ApisGenerator";
        $scriptPath = "ApisGenerator/scripts";
        $cssPath = "ApisGenerator/css";
        $this->publishes([
            __DIR__."/../Config/apis_generator.php" => config_path("apis_generator.php")
        ] , "apis-generator-config");

        $this->publishes([
            $asset."create.blade.php" => resource_path("$views/create.blade.php"),
            $asset."index.blade.php" => resource_path("$views/index.blade.php"),
            $asset."credential.json" => resource_path("$views/credential.json"),
            $asset."_layouts.blade.php" => resource_path("$views/layouts/_layouts.blade.php"),
            $asset."script.js" => public_path("$scriptPath/script.js"),
            $asset."css.css" => public_path("$cssPath/css.css"),
        ] , "apis-generator-asset");

    }

    /**
     *
     */
    private function loadResource()
    {
        $slash = DIRECTORY_SEPARATOR;
        $this->loadRoutesFrom(__DIR__.$slash."..".$slash."Routes".$slash."Routes.php");
    }

}
