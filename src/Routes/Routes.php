<?php

use Illuminate\Support\Facades\Route;

Route::namespace("KMLaravel\ApiGenerator\Controllers")
    ->middleware(['web'])
    ->group(function () {
        Route::group([], function () {
            Route::get('/apisGenerator/index', function () {
                return view('ApisGenerator/index');
            })->name('apisGenerator.index');
            Route::get('/apisGenerator/create', function () {
                return view('ApisGenerator/create');
            })->name('apisGenerator.create');
            Route::post('/apisGenerator/create', "ApisGeneratorController@create")
                ->name('apisGenerator.create.send');
        });
    });
function getParentMiddleware()
{
    $parent = config('apis_generator.middleware');
    if (isset($parent)) {
        return array_keys($parent);
    }
}

function getChildMiddleware()
{
    $parent = config('apis_generator.middleware');
    $data = [];
    foreach (config('apis_generator.middleware') as $item) {
        foreach ($item as $item1 => $item2) {
            $data[] = $item2;
        }
    }
    if (isset($parent)) {
        return $data;
    }
}
