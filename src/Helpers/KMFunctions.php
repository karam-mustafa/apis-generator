<?php


namespace KMLaravel\ApiGenerator\Helpers;


class KMFunctions
{
    /**
     * @return \Illuminate\Config\Repository|mixed
     */
    public static function getMiddleware()
    {
        return config('apis_generator.middleware');
    }

    /**
     * @return string
     */
    public static function getRequestAuthAccessibility()
    {
        return config('apis_generator.request_auth') == true ? "true" : "false";
    }
}
