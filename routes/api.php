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
//user authentication




Route::group(['prefix'=>'v1'], function () {
    

    Route::post('/login',          [App\Http\Controllers\API\UserController::class,'login']);

    Route::post('/register',       [App\Http\Controllers\API\UserController::class,'register']);
       
   
    //forget password 
    
    Route::post('/forget',[App\Http\Controllers\API\UserController::class,'forgot']);
    
    Route::post('/verifyResetPassword',[App\Http\Controllers\API\UserController::class,'verifyResetPassword']);
    
    Route::post('/reset',[App\Http\Controllers\API\UserController::class,'reset']);

    //get otp 
    Route::post('/getOtpRegister',         [App\Http\Controllers\API\UserController::class,'getOtpRegister']);

    Route::post('/sendOtpAgain',         [App\Http\Controllers\API\UserController::class,'sendOtpAgain']);


    //medical centers
    Route::get('/allCenters',[App\Http\Controllers\API\CenterController::class,'index']);
    Route::get('/showCenter/{center}',[App\Http\Controllers\API\CenterController::class,'show']);

    // doctors
    Route::get('/allDoctors',[App\Http\Controllers\API\DoctorController::class,'index']);
    Route::get('/showDoctor/{doctor}',[App\Http\Controllers\API\DoctorController::class,'show']);

    Route::group(['middleware' => ['auth:sanctum']],function () {
        
        Route::prefix('user')->group(function(){

            Route::post('/update',[App\Http\Controllers\API\UserController::class, 'update']);
            
            Route::get('/show',[App\Http\Controllers\API\UserController::class,'show']);
            
            Route::post('/logOut',[App\Http\Controllers\API\UserController::class,'signOut']);  

            Route::post('/changePassword',[App\Http\Controllers\API\UserController::class,'changePassword']);
           
            
        });


    });

         
});


