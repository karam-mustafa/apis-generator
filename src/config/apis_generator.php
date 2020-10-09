<?php

return [

    /*
    |--------------------------------------------------------------------------
    | basic options to build files and data
    |--------------------------------------------------------------------------
    |
    | this array contains the name of function to run in generator services
    | the keys of this array are shown in labels inside create view and values
    | is the name of functions inside GeneratorService class and we check if this
    | functions is exits.
    */
    'basic_build_options' => [
        "Build requests" => 'buildRequests',
        "Build resource" => 'buildResource',
        "Build model" => 'buildModelAndMigrations',
        "Build migration" => 'buildMigration',
        "Build controller" => 'buildController',
    ],
    /*
    |--------------------------------------------------------------------------
    | advanced options to build files and data
    |--------------------------------------------------------------------------
    |
    | this array contains the name of function to run in generator services
    | is the same idea with basic option , but we separate them for reasons
    | related to facilitating development
    |
    */
    "advanced_build_options" => [
        "Build base controller if not exists" => 'runBuildBaseController',
        "Auto run migrate command" => 'runMigration',
    ],
    /*
    |--------------------------------------------------------------------------
    | extra options which user want to build
    |--------------------------------------------------------------------------
    |
    | this used if you want to build your custom options ,  we will provide this in next release.
    |
    */
//    "extra_build_options" => [
//        //
//    ],
    /*
    |--------------------------------------------------------------------------
    | package routes middleware
    |--------------------------------------------------------------------------
    |
    | this middleware array if you want to add custom middleware to package route,
    | this is applies to ( /apis-generator/index ) and ( /apis-generator/create ).
    |
    */
    "middleware" => [
        //
    ],
    /*
    |--------------------------------------------------------------------------
    | default value for authorize function
    |--------------------------------------------------------------------------
    |
    | the default value to return in authorize function inside request file.
    |
    */
    "request_auth" => true,
    /*
    |--------------------------------------------------------------------------
    | types of column in database which laravel provider.
    |--------------------------------------------------------------------------
    |
    | the options in database column select in create view,
    | you can added or optimize this columns .
    |
    */
    'column_type' => [
        "string",
        "text",
        "boolean",
        "bigInteger",
        "bigInteger",
        "bigIncrements",
        "char",
        "date",
        "dateTime",
        "dateTime",
        "time",
        "timestamp",
        "decimal",
        "decimal",
        "integer",
        "float",
        "increments",
        "longText",
        "mediumInteger",
        "mediumInteger",
        "mediumText",
        "morphs",
        "nullableTimestamps",
        "smallInteger",
        "tinyInteger",
        "mediumText",
        "softDeletes",
        "timestamps",
        "mediumText",
        "rememberToken",
    ]
];
