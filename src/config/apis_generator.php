<?php

return [
    'basic_build_options' => [
        "Build requests" => 'buildRequests',
        "Build resource" => 'buildResource',
        "Build model" => 'buildModelAndMigrations',
        "Build migration" => 'buildMigration',
        "Build controller" => 'buildController',
    ],
    "advanced_build_options" => [
        "Build base controller if not exists" => 'runBuildBaseController',
        "Auto run migrate command" => 'runMigration',
    ],
    "extra_build_options" => [
        //
    ],
    "middleware" => [
    ],
    "request_auth" => true,
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
