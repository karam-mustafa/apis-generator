<?php

namespace KMLaravel\ApiGenerator\Routes;

use Illuminate\Support\Facades\Route;
use KMLaravel\ApiGenerator\Facade\KMFileHelper;

class ApisGeneratorRoutes
{
    public static function getRoutes()
    {
        return Route::group([], function () {
            $extraRoutes = KMFileHelper::getCredentialJsonFileAsJson();
            foreach ($extraRoutes as $api) {
                if (isset($api)){
                    foreach ($api as $value) {
                        if (isset($value->route)) {
                            Route::apiResource($value->url, $value->route);
                        }
                    }
                }
            }
        });
    }
}
