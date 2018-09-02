<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('country', 'CountryController');
    $router->resource('province', 'ProvinceController');
    $router->resource('city', 'CityController');
    $router->resource('county', 'CountyController');

});

Route::middleware(['web','admin'])->name('admin.api.')->prefix('admin/api')->namespace('App\Admin\Apis')->group(function(){
    Route::get('countries_column','CountryApiController@countriesColumn')->name('countries_column');
    Route::get('provinces_column','ProvinceApiController@provincesColumn')->name('provinces_column');
    Route::get('cities_column','CityApiController@citiesColumn')->name('cities_column');
});
