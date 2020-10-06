## Apis Generator
[![Latest Stable Version](https://poser.pugx.org/kmlaravel/api-generator/v)](//packagist.org/packages/kmlaravel/api-generator) 
[![License](https://poser.pugx.org/kmlaravel/api-generator/license)](//packagist.org/packages/kmlaravel/api-generator)

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
- Friendly simple interface.
- Giving you the choice to choose what to build.
- The large development space in the future.

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
#### 3 - Copy the package assets to your local resource views with the publish command:
```shell
php artisan vendor:publish --tag=apis-generator-asset
```
