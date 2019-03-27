<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// })->middleware('auth.basic.once');

Route::redirect('/', 'vote');
Route::any('vote', 'UserController@vote_index');
Route::any('result/update', 'UserController@get_result_update');
Route::any('vote/{vote_id}', 'UserController@show_vote');
Route::any('ballot', 'UserController@ballot');
Route::any('ballot/cast', 'UserController@cast_ballot');
Route::any('batch', 'UserController@batch_index');
Route::any('batch/{batch_id}', 'UserController@batch');

Route::group(['middleware' => ['auth.basic.once']], function () {
    Route::prefix('admin')->group(function () {
        Route::redirect('/', 'admin/vote');
        Route::prefix('vote')->group(function () {
            Route::any('/', 'AdminController@vote_index');
            Route::any('create', 'AdminController@create_vote');
            Route::any('{vote_id}/delete', 'AdminController@delete_vote');
            Route::any('{vote_id}/update', 'AdminController@update_vote');
            Route::any('{vote_id}/ballot/add', 'AdminController@add_ballot');
        });
        Route::prefix('blockchain')->group(function () {
            Route::redirect('/', 'blockchain/batch');
            Route::any('batch', 'AdminController@batch_index');
            Route::any('batch/{batch_id}', 'AdminController@batch');
            Route::any('state', 'AdminController@state_index');
            Route::any('state/{address}', 'AdminController@state');
            Route::any('block', 'AdminController@block_index');
            Route::any('block/{block_id}', 'AdminController@block');
            Route::any('transaction', 'AdminController@transaction_index');
            Route::any('transaction/{transaction_id}', 'AdminController@transaction');
        });
    });
});

Route::any('ballot/count', 'AdminController@count_ballot');
Route::any('cron/result/update/{vote_id}', 'AdminController@update_result');
