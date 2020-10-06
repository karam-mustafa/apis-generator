## Apis Generator
[![Latest Stable Version](https://poser.pugx.org/kmlaravel/api-generator/v)](//packagist.org/packages/kmlaravel/api-generator) 
[![License](https://poser.pugx.org/kmlaravel/api-generator/license)](//packagist.org/packages/kmlaravel/api-generator)
# Notes 
> this document is still being written
>
api generator was developed for [laravel 5.8+](http://laravel.com/) to accelerate your
work by building for you new api just a few clicks .

What this package build ?
-------------------------
this package auto build :
- Model : with fillable.
- Request : with validations rules.
- Controller : with full crud based on base controller.
- Resource : to get resources data.
- Migration : with auto generate for your database columns.

Features
--------
- Friendly simple interface to create your api.
- Giving you the choice to choose what to build.
- The large development space in the future update and we will  add a lot features.

this package came with base controller which helps to handle some logic and generate response, and we will develop all functions that help developers to reduced development time.

this package came with simple interface to manage api creations and view all api you had made before based on credential json file. 

Installation
------------
#### 1 - Dependency
The first step is using composer to install the package and automatically update your composer.json file, you can do this by running:

```shell
composer require kmlaravel/api-generator
```
Laravel uses Package Auto-Discovery, so doesn't require you to manually add the ServiceProvider.

#### 2 - Copy the package config to your local config with the publish command:
```shell
php artisan vendor:publish --tag=apis-generator-config
```
In `apis_generator`.php configuration file you can determine the properties of the default values and some behaviors.
- choose database columns , we already take columns type from [Laravel Migrations](https://laravel.com/docs/6.x/migrations)

#### 3 - Copy the package assets to your local resource views with the publish command:
```shell
php artisan vendor:publish --tag=apis-generator-asset
```

Basic usage
-----------
#### 1 - Load your routes
As we said a little while ago we save your process result in `resource/views/ApiGenerator/credential.josn` 
this file contains an array that in turn contains an objects each one contains route , url , api title , and type for your api .

to run this route we have to add this facade class in your `routes/api.php` file.
```php 
\KMLaravel\ApiGenerator\Facade\ApisGeneratorRoutes::getRoutes();
```
now all your routes load automatically from routes in `credential.json` file.

What next :
-----------
- add seeder options with factory.
- add api actions which include edit and delete this api.
- add more migrations options to build columns.
