<?php

namespace KMLaravel\ApiGenerator\Providers;
use Illuminate\Support\ServiceProvider;
use KMLaravel\ApiGenerator\Commands\TestCommands;
use KMLaravel\ApiGenerator\Helpers\KMFileHelper;
use KMLaravel\ApiGenerator\Routes\ApisGeneratorRoutes;

class ApisGeneratorServiceProviders extends ServiceProvider
{

    public function boot(){
        $this->registerFacades();
        $this->publishesPackages();
        $this->loadResource();

    }

    public function register(){
        $this->commands([
            TestCommands::class
        ]);
    }

    protected function publishesBaseControllers()
    {

    }

    protected function registerFacades()
    {
        $this->app->singleton("KMFileHelper" , function ($app){
            return new KMFileHelper();
        });
        $this->app->singleton("ApisGeneratorRoutes" , function ($app){
            return new ApisGeneratorRoutes();
        });
    }
    protected function publishesPackages()
    {
        $asset = __DIR__."/../Asset/";
        $views = "views/ApisGenerator";
        $scriptPath = "ApisGenerator/scripts";
        $cssPath = "ApisGenerator/css";
        $this->publishes([
            __DIR__."/../Config/ApisGenerator.php" => config_path("ApisGenerator.php")
        ] , "apis-generator-config");

        $this->publishes([
            $asset."create.blade.php" => resource_path("$views/create.blade.php"),
            $asset."index.blade.php" => resource_path("$views/index.blade.php"),
            $asset."_layouts.blade.php" => resource_path("$views/layouts/_layouts.blade.php"),
            $asset."script.js" => public_path("$scriptPath/script.js"),
            $asset."css.css" => public_path("$cssPath/css.css"),
        ] , "apis-generator-asset");

    }

    private function loadResource()
    {
        $slash = DIRECTORY_SEPARATOR;
        $this->loadRoutesFrom(__DIR__.$slash."..".$slash."Routes".$slash."Routes.php");
    }

}
