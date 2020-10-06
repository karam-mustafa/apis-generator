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
            if (isset($extraRoutes)){
                foreach ($extraRoutes as $api) {
                    if (isset($api->route) && isset($api->url)) {
                        Route::apiResource($api->url, $api->route);
                    }
                }
            }
        });
    }
}
