<?php

use Illuminate\Support\Facades\Route;

Route::namespace("KMLaravel\ApiGenerator\Controllers")
    ->middleware('web')
    ->group(function () {
        Route::get('/apisGenerator/index', function () {
            return view('ApisGenerator/index');
        })->name('apisGenerator.index');
        Route::get('/apisGenerator/create', function () {
            return view('ApisGenerator/create');
        })->name('apisGenerator.create');
        Route::post('/apisGenerator/create', "ApisGeneratorController@create")
            ->name('apisGenerator.create.send');
    });
