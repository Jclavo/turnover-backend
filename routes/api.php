<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', 'UserController@login');
Route::post('users', 'UserController@store');

    //here your api under authentication
Route::middleware(['auth:sanctum'])->group(function () {

    // Route::resource('deposit-statuses', 'DepositStatusController');

    //deposit-statuses
    Route::get('deposit-statuses', 'DepositStatusController@index'); 
    
    //deposits
    Route::post('deposits', 'DepositController@store'); 
    Route::post('deposits/update-status', 'DepositController@updatedStatus'); 
    Route::post('deposits/pagination', 'DepositController@pagination'); 

    //purchases
    Route::post('purchases', 'PurchaseController@store'); 
});




