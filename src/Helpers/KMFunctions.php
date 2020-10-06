<?php


namespace KMLaravel\ApiGenerator\Helpers;


class KMFunctions
{
    public static function getMiddleware()
    {
        return config('apis_generator.middleware');
    }
    public static function getRequestAuthAccessibility()
    {
        return config('apis_generator.request_auth') == true ? "true" : "false";
    }
}
