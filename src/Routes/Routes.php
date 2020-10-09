<?php

use Illuminate\Support\Facades\Route;
use KMLaravel\ApiGenerator\Facade\KMFunctionsFacade;

Route::namespace("KMLaravel\ApiGenerator\Controllers")
    ->group(function () {
        // this is pull middleware information from apis_generator.php config file
        Route::group(['middleware' => array_merge(['web'], KMFunctionsFacade::getMiddleware() ?? [])], function () {
            Route::group([], function () {
                // all apis table page
                Route::get('/apis-generator/index', function () {
                    return view('ApisGenerator/index');
                })->name('apisGenerator.index');
                // create new api page
                Route::get('/apis-generator/create', function () {
                    return view('ApisGenerator/create');
                })->name('apisGenerator.create');
                // post information route
                Route::post('/apis-generator/create', "ApisGeneratorController@create")
                    ->name('apisGenerator.create.send');
            });
        });
    });

