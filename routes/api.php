<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\RegistrationController;
use App\Http\Controllers\API\StoreController;
use App\Http\Controllers\API\StatusOrderController;
use App\Http\Controllers\API\CourierStatusActiveController;
use App\Http\Controllers\API\CourierStatusAvailableController;
use App\Http\Controllers\API\ProductCategoryController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\LogoutController;
use App\Http\Controllers\API\CourierController;
use App\Http\Controllers\API\TemporaryCartController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\UserProfileController;
use App\Http\Controllers\API\ReportController;




Route::controller(RegistrationController::class)->group(function(){
    Route::post('register', 'register');
});

Route::controller(LoginController::class)->group(function(){
    Route::post('login', 'login');
});

Route::middleware('auth:sanctum')->group( callback: function () {
    Route::resource('store', StoreController::class);
    Route::resource('role', RoleController::class);
    Route::resource('statusorder', StatusOrderController::class);
    Route::resource('courierstatusactive', CourierStatusActiveController::class);
    Route::resource('courierstatusavailable', CourierStatusAvailableController::class);
    Route::resource('productcategory', ProductCategoryController::class);
    Route::resource('product', ProductController::class);
    Route::post('/logout', [LogoutController::class, 'logout']);
    
    Route::get('/courier', [CourierController::class, 'index']);

    Route::get('/productuser', [ProductController::class, 'getProductFromuser']);

    Route::post('/cart', [TemporaryCartController::class, 'cartConstructor']);
    Route::post('/getcart', [TemporaryCartController::class, 'getCart']);

    Route::post('/order', [OrderController::class, 'storeOrder']);
    Route::post('/getuserorder', [OrderController::class, 'getUserOrder']);
    Route::post('/doneuserorder', [OrderController::class, 'doneOrderUser']);
    Route::post('/canceluserorder', [OrderController::class, 'cancelOrderUser']);
    Route::get('/getadminorder', [OrderController::class, 'getAdminOrder']);
    Route::get('/getadmincountorder', [OrderController::class, 'getCountOrdersByMonth']);
    

    Route::post('/getuserprofile', [UserProfileController::class, 'getUserProfile']);
    Route::post('/updateuseraddress', [UserProfileController::class, 'updateUserAddress']);
    Route::get('/getuser', [UserProfileController::class, 'getUser']);
    Route::post('/updateuser', [UserProfileController::class, 'updateUser']);
    
    
    Route::post('/getcourier', [CourierController::class, 'getCourierData']);
    Route::post('/updatecourier', [CourierController::class, 'updateCourierData']);
    Route::get('/getlistallordercourier', [CourierController::class, 'getAllListOrderCourier']);
    
    Route::post('/updatestatusavailablecourier', [CourierController::class, 'updateStatusAvailableCourier']);
    Route::post('/assignordercourier', [CourierController::class, 'assignOrderCourier']);
    Route::post('/takenlistordercourier', [CourierController::class, 'takenListOrderCourier']);
    Route::post('/deliverordercourier', [CourierController::class, 'deliverOrderCourier']);
    Route::post('/cancelordercourier', [CourierController::class, 'cancelOrderCourier']);
    Route::post('/donelistordercourier', [CourierController::class, 'doneListOrderCourier']);


    Route::get('/reportuserall', [ReportController::class, 'reportAllUser']);
    Route::get('/reportcourierall', [ReportController::class, 'reportAllCourier']);
    Route::get('/reportorderall', [ReportController::class, 'reportAllOrder']);
    Route::get('/reportrecapallsuccessorder', [ReportController::class, 'recapAllSuccessOrder']);
    Route::get('/reportrecapallcancelledorder', [ReportController::class, 'recapAllCancelledOrder']);
    Route::post('/reportrecapsuccessbydate', [ReportController::class, 'recapByDateSuccessOrder']);
    Route::post('/reportrecapcancelledbydate', [ReportController::class, 'recapByDateCancelledOrder']);
    Route::get('/reportstoreall', [ReportController::class, 'reportAllStore']);
    Route::get('/reportproductall', [ReportController::class, 'reportAllProduct']);
    
    
});
