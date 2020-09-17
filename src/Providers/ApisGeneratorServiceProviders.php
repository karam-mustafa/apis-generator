<?php

namespace KMLaravel\ApiGenerator\Providers;
use Illuminate\Support\ServiceProvider;

class ApisGeneratorServiceProviders extends ServiceProvider
{

    public function boot(){
        $this->registerFacades();
        $this->publishesPackages();
    }

    public function register(){

    }

    protected function publishesBaseControllers()
    {

    }

    protected function registerFacades()
    {
//        $this->app->singleton('FileHelper' , function ($app){
//            return new \KmTools\ApiHelper\HelperClasses\FileHelper();
//        });
    }

    private function publishesPackages()
    {
        $this->publishes([
            __DIR__."\\config\\ApisGenerator.php" => config('ApisGenerator.php')
        ] , 'apis-generator-config');
        $this->publishes([
            __DIR__."\\asset\\create.php" => config('create.php'),
            __DIR__."\\asset\\index.php" => config('index.php'),
        ] , 'apis-generator-asset-config');
    }
}
