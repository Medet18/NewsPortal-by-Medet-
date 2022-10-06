<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\SubadminController;
use App\Http\Controllers\SubUsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


//Route only for admin
Route::group(['prefix'=>'{lang}','where' => ['lang' => '[a-zA-Z]{2}'], 'middleware' =>['setLanguage']], function () {

    Route::get('num/{num}', function($num){
        dd($num);
    });
    Route::group(['prefix' => 'admin'], function ($router) {
        Route::post('/login', [AdminController::class, 'login']);
        Route::post('/register', [AdminController::class, 'register']);
    });
    Route::group(['middleware' => ['auth:admin-api', 'jwt.auth'], 'prefix' => 'admin'], function ($router) {
        Route::post('/', [AdminController::class, 'logout']);
        Route::get('/', [AdminController::class, 'userProfile']);
        Route::post('/refresh', [AdminController::class, 'refresh']);

        //edit subadmins
        Route::group(['prefix' => 'edit/subadmins'], function ($router) {
            Route::get('/', [SubUsController::class, 'index_subadmins']);
            Route::get('/{id}', [SubUsController::class, 'show_subadmin']);
            Route::delete('/{id}', [SubUsController::class, 'destroy_subadmin']);
        });

        //edit users
        Route::group(['prefix' => 'edit/users'], function ($router) {
            Route::get('/', [SubUsController::class, 'index_users']);
            Route::get('/{id}', [SubUsController::class, 'show_user']);
            Route::delete('/{id}', [SubUsController::class, 'destroy_user']);
        });
    });


//Routes for subadmins
    Route::group(['prefix' => 'subadmin'], function ($router) {
        Route::post('/login', [SubadminController::class, 'login']);
        Route::post('/register', [SubadminController::class, 'register']);

    });
    Route::group(['middleware' => ['auth:subadmin-api', 'jwt.auth'], 'prefix' => 'subadmin'], function ($router) {
        Route::post('/', [SubadminController::class, 'logout']);
        Route::get('/', [SubadminController::class, 'userProfile']);
        Route::post('/refresh', [SubadminController::class, 'refresh']);


        Route::group(['prefix' => 'edit/news'], function ($router) {
            Route::get('/', [NewsController::class, 'index_for_subadmin']);
            Route::get('/{id}', [NewsController::class, 'show_for_subadmin']);
            Route::post('/', [NewsController::class, 'store']);
            Route::put('/{id}', [NewsController::class, 'update']);
            Route::delete('/{id}', [NewsController::class, 'destroy']);
        });
    });


//Routes for users
    Route::group(['prefix' => 'user'], function ($router) {
        Route::post('/register', [UserController::class, 'register']);
        Route::post('/login', [UserController::class, 'login']);
    });
    Route::group(['middleware' => ['auth:user-api', 'jwt.auth'], 'prefix' => 'user'], function ($router) {
        Route::post('/', [UserController::class, 'logout']);
        Route::get('/', [UserController::class, 'userProfile']);
        Route::post('/refresh', [UserController::class, 'refresh']);


        Route::group(['prefix' => 'show/news'], function () {
            Route::get('/', [NewsController::class, 'index_for_user']);
            Route::get('/{id}', [NewsController::class, 'show_for_user']);
        });
    });
});
