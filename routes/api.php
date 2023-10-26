<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FrameController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//    "(♥◠‿◠)ﾉﾞ  FisrstsnowLucky   ლ(´ڡ`ლ)ﾞ  \n" +
//    " ..------------   |--|\n" +
//    " ||------------   |--|\n" +
//    " ||   | .. |      |--|\n" +
//    " ||------------   |--|\n" +
//    " ||------------   |--|\n" +
//    " ||               |--|\n" +
//    " ||               |--|\n" +
//    " ||               |--|-------------|\n" +
//    " ||               |--|-------------|\n"+
//    "(♥◠‿◠)ﾉﾞ  FirstSnowLucky   ლ(´ڡ`ლ)ﾞ"


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::prefix('admin')->group(function () {
        Route::post('/login', [AuthController::class, 'update']);
        Route::middleware("auth:api")->group(function (){
            Route::post('/logout', [AuthController::class, 'destroy']);
            /**
             * size
             */
            Route::prefix('size')->group(function () {
                Route::get('/', [SizeController::class,'index']);
                Route::patch("/{id}",[SizeController::class,'update']);
            });
            /**
             * Frame
             */
            Route::prefix('frame')->group(function () {
                Route::get('/', [FrameController::class,'index']);
                Route::patch("/{id}",[FrameController::class,'update']);
            });
            /**
             * Order
             */
            Route::prefix('order')->group(function () {
                Route::get('/', [OrderController::class, 'index']);
                Route::post('/cancel/{order_id}', [OrderController::class, 'CancelOrder']);
                Route::post('/complete/{order_id}', [OrderController::class, 'CompleteOrder']);
            });
            /**
             * User
             */
            Route::prefix('user')->group(function () {
                Route::get('/', [AuthController::class, 'index']);
                Route::post('/reset/{user_id}', [AuthController::class, 'reset']);
                Route::delete('/{user_id}', [AuthController::class, 'delete']);
            });

            /**
             * Reset User Cart
             */
            Route::post('/cart/reset/{user_id}', [CartController::class, 'resetUser']);

            /**
             * Admin
             */
            Route::prefix('admin')->group(function () {
                Route::get('/', [AdminController::class, 'index']);
                Route::post('/', [AdminController::class, 'update']);
                Route::post('/reset/{admin_id}', [AdminController::class, 'store']);
                Route::delete('/{admin_id}', [AdminController::class, 'destroy']);
            });
        });
    });

    Route::prefix('client')->group(function (){
        Route::post('/login', [AuthController::class, 'update']);
        Route::post('/register', [AuthController::class, 'store']);
        Route::middleware('auth:api')->group(function (){
            Route::post('/logout', [AuthController::class, 'destroy']);
            /**
             * reset password
             */
            Route::post('/user/reset', [AdminController::class, 'resetUser']);

            Route::get('/size', [SizeController::class, 'index']);

            Route::get('/frame',[FrameController::class,'index']);

            Route::prefix('photo')->group(function () {
                Route::get('/', [PhotoController::class, 'index']);
                Route::post('/', [PhotoController::class, 'create']);
                Route::delete('/{photo_id}', [PhotoController::class, 'destroy']);
                Route::get('/size/{size_id}',[PhotoController::class,'sizePhoto']);
                Route::post('/frame/{photo_id}/{frame_id}',[PhotoController::class,'framePhoto']);
            });

            Route::resource('/cart', CartController::class);
            Route::resource('/order', OrderController::class);
            Route::post('/order/cancel/{order_id}', [OrderController::class, 'cancel']);
        });
    });
});

