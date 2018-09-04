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
    $router->resource('street', 'StreetController');

});

Route::middleware(['web','admin'])->name('admin.api.')->prefix('admin/api')->namespace('App\Admin\Apis')->group(function(){
    Route::get('countries_column','CountryApiController@countriesColumn')->name('countries_column');
    Route::get('countries_column_table_select','CountryApiController@countriesColumnTableSelect')->name('countries_column_table_select');
    Route::get('provinces_column','ProvinceApiController@provincesColumn')->name('provinces_column');
    Route::get('provinces_column_table_select','ProvinceApiController@provincesColumnTableSelect')->name('provinces_column_table_select');
    Route::get('cities_column','CityApiController@citiesColumn')->name('cities_column');
    Route::get('cities_column_table_select','CityApiController@citiesColumnTableSelect')->name('cities_column_table_select');
    Route::get('counties_column','CountyApiController@countiesColumn')->name('counties_column');
    Route::get('counties_column_table_select','CountyApiController@countiesColumnTableSelect')->name('counties_column_table_select');
});
