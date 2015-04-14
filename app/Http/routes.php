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

# Files

Route::get('files', ['as' => 'files', 'uses' => 'FileTransactionController@files']);
Route::get('fileUpload', ['as' => 'fileUpload', 'uses' => 'FileTransactionController@fileUpload']);
Route::post('fileUpload', ['uses' => 'FileTransactionController@fileUploadPost']);
Route::get('file/delete/{filename}', ['as' => 'fileDelete', 'uses' => 'FileTransactionController@deleteFile']);

Route::get('categories/file/{filename}', ['as' => 'categories_from_file', 'uses' => 'FileTransactionController@addToCategoriesLabelsFromFile']);
Route::post('categories/action/save', ['as' => 'category_labels_save', 'uses' => 'FileTransactionController@addToCategoriesLabelsFromFilePost']);

Route::get('report/file/{filename}', ['as' => 'transactions_from_file', 'uses' => 'FileTransactionController@transactionsFromFile']);
Route::get('import/{filename}', ['as' => 'import_from_file', 'uses' => 'FileTransactionController@import']);
Route::post('import/{filename}', ['uses' => 'FileTransactionController@importPost']);

# Categories

Route::get('categories', ['as' => 'categories', 'uses' => 'CategoriesController@index']);
Route::post('categories/{id}', ['as' => 'category_labels_update', 'uses' => 'CategoriesController@save']);
Route::get('categories/delete/{id}', ['as' => 'category_delete', 'uses' => 'CategoriesController@delete']);

# Transactions

Route::get('transaction/list', ['as' => 'transactions', 'uses' => 'TransactionController@index']);
Route::post('transaction/update/{id}', ['as' => 'transaction_update', 'uses' => 'TransactionController@update']);
Route::get('transaction/delete/{id}', ['as' => 'transactions_delete', 'uses' => 'TransactionController@delete']);
Route::get('report/storage', ['as' => 'transactions_from_storage', 'uses' => 'TransactionController@report']);

# Api

Route::get('api/sources', ['as' => 'api_sources_by_keyword', 'uses' => 'ApiController@getListOfSourcesThatMatch']);