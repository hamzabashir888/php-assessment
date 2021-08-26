<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\ToDoController;

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

////////////////////////User Route///////////////////////
 Route::post('/register',[RegisterController::class, 'register']);
 Route::post('/verify-user',[RegisterController::class, 'verifyUser']);
 //////////////////////User Route Ends//////////////////
 Route::post('/login',[AuthController::class, 'login']);


 Route::group([
    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers\API',
    'prefix' => 'auth'

], function ($router) {

    /////////////////////////////To DO List Routes////////////////////////////
    Route::post('/create-list',[ToDoController::class, 'create']);
    Route::get('/view-users-to-do-list',[ToDoController::class, 'viewList']);
    Route::post('/search-to-do-list',[ToDoController::class, 'searchItems']);
    Route::post('/update-to-do-list',[ToDoController::class, 'updateItems']);
    Route::delete('/delete-to-do-list/{id}',[ToDoController::class, 'delete']);
    /////////////////////////////To DO List Routes End////////////////////////////
    Route::get('logout',[AuthController::class, 'logout']);
    // Route::post('refresh', 'AuthController@refresh');

});
