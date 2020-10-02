<?php

namespace KMLaravel\ApiGenerator\Routes;

use Illuminate\Support\Facades\Route;
use KMLaravel\ApiGenerator\Facade\KMFileHelper;

class ApisGeneratorRoutes
{
    public static function getRoutes()
    {
       return Route::group([] , function () {
           $extraRoutes = KMFileHelper::getCredentialJsonFileAsJson();
           foreach ($extraRoutes as $api) {
               foreach ($api as $value) {
                   Route::apiResource($value->url, $value->route);
               }
           }
       });
    }
}
