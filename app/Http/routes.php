<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'TransactionController@files');

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::get('files', ['as' => 'files', 'uses' => 'TransactionController@files']);
Route::get('fileUpload', ['as' => 'fileUpload', 'uses' => 'TransactionController@fileUpload']);
Route::post('fileUpload', ['uses' => 'TransactionController@fileUploadPost']);

Route::get('categories/file/{filename}', ['as' => 'categories_from_file', 'uses' => 'TransactionController@addToCategoriesLabelsFromFile']);
Route::post('categories/save/{category}/{label}', ['as' => 'category_labels_save', 'uses' => 'TransactionController@addToCategoriesLabelsFromFilePost']);

Route::get('report/{filename}', ['as' => 'transactions_from_file', 'uses' => 'TransactionController@transactionsFromFile']);

Route::get('categories', ['as' => 'categories', 'uses' => 'CategoriesController@index']);
Route::post('categories/{id}', ['as' => 'category_labels_update', 'uses' => 'CategoriesController@save']);
Route::get('categories/delete/{id}', ['as' => 'category_delete', 'uses' => 'CategoriesController@delete']);