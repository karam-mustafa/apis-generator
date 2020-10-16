<?php

namespace KMLaravel\ApiGenerator\Helpers;

use Illuminate\Support\Facades\Route;
use KMLaravel\ApiGenerator\Facade\KMFileFacade;

class KMRoutes
{
    public static function getRoutes()
    {
        return Route::group([], function () {
            $extraRoutes = KMFileFacade::getCredentialJsonFileAsJson();
            if (isset($extraRoutes)){
                foreach ($extraRoutes as $api) {
                    if (isset($api->controller) && isset($api->url)) {
                        $api->controller = str_replace('/' , '\\' , $api->controller);
                        Route::apiResource($api->url, $api->controller);
                    }
                }
            }
        });
    }
}
