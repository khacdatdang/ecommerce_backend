<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\OrderController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::resource('customer', CustomersController::class);
// Route::resource('order', OrderController::class);

Route::post('order/store', [OrderController::class, 'store']);

Route::post('validate_user', [UserController::class, 'validate_user']);
Route::post('register', [UserController::class, 'register']);
Route::post('find_user', [UserController::class, 'find_user']);
Route::post('set_info', [UserController::class, 'set_info']);
Route::post('set_password', [UserController::class, 'set_password']);
Route::post('reset_password', [UserController::class, 'reset_password']);
Route::post('set_payment', [BankAccountController::class, 'set_payment']);
Route::get('get_orders/{id}', [UserController::class, 'get_orders']);
