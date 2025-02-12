<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\Deliveries\DeliveryOrderHeaderController;
use App\Http\Controllers\Api\V1\Deliveries\OrderingCutoffScheduleConfigurationController;
use App\Http\Controllers\Api\V1\Deliveries\OrderingGroupController;
use App\Http\Controllers\Api\V1\Entities\CostCenterController;
use App\Http\Controllers\Api\V1\Entities\StoreController;
use App\Http\Controllers\Api\V1\Materials\CostTypeController;
use App\Http\Controllers\Api\V1\Materials\MaterialController;
use App\Http\Controllers\Api\V1\Materials\MaterialGroupController;
use App\Http\Controllers\Api\V1\Materials\MaterialGroupTypeController;
use App\Http\Controllers\Api\V1\Materials\PlantController;
use App\Http\Controllers\Api\V1\Materials\UnitController;
use App\Http\Controllers\Api\V1\Materials\UnitTypeController;
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

Route::post('/auth', [AuthController::class, 'login']);

Route::group(['prefix' => '/v1', 'middleware' => ['auth:api', 'scopes:delivery-module']], function () {

    /*--------------------------------------
                DELIVERIES SCHEMA
    ---------------------------------------*/
    Route::group(['prefix' => '/deliveries'], function () {

        // Order Schedule Configuration Delivery
        Route::group(['prefix' => 'ordering-cutoff-schedule-configs', 'controller' => OrderingCutoffScheduleConfigurationController::class], function () {
            Route::post('/', 'store');
            Route::get('/{storeCode}/{orderingGroupCode}', 'show');
        });

        // Deliver Order Saving
        Route::group(['prefix' => 'delivery-orders', 'controller' => DeliveryOrderHeaderController::class], function () {
            Route::post('/', 'store');
        });

        // Ordering Group Delivery
        Route::group(['prefix' => 'ordering-groups', 'controller' => OrderingGroupController::class], function () {
            Route::get('/store/{storeCode}', 'showByStoreCode');
            Route::get('/setup', 'index');
            Route::post('/setup', 'store');
            Route::get('/setup/{orderingGroupCode}', 'show');
            Route::match(['put', 'patch'], '/setup/{orderingGroupCode}', 'update');
        });
    });
    /*--------------------------------------
             END::DELIVERIES SCHEMA
    ---------------------------------------*/



    /*--------------------------------------
                ENTITIES SCHEMA
    ---------------------------------------*/
    Route::group(['prefix' => '/entities'], function () {

        // Store Entity
        Route::group(['prefix' => 'stores', 'controller' => StoreController::class], function () {
            Route::get('/cost-center/{costCenter}', 'showByCostCenterCode');
            Route::get('/', 'index');
        });

        // Cost Center Entity
        Route::group(['prefix' => 'cost-centers', 'controller' => CostCenterController::class], function () {
            Route::get('/user/{username}', 'showByUsername');
        });
    });
    /*--------------------------------------
              END::ENTITIES SCHEMA
    ---------------------------------------*/




    /*--------------------------------------
                MATERIALS SCHEMA
    ---------------------------------------*/
    Route::group(['prefix' => '/materials'], function () {

        // Material Materials
        Route::group(['prefix' => 'materials-table', 'controller' => MaterialController::class], function () {
            Route::get('/{storeCode}/{orderingGroupCode}/{materialGroupCode}', 'showMaterialsWithScheduleOrders');
        });

        // Material Group Materials
        Route::group(['prefix' => 'material-groups', 'controller' => MaterialGroupController::class], function () {
            Route::get('/{storeCode}/{orderingGroupCode}', 'showByStoreCodeOrderingGroupCode');
        });

        // Material Group Type Materials
        Route::group(['prefix' => 'units', 'controller' => MaterialGroupTypeController::class], function () {
            Route::get('/setup', 'index');
            Route::post('/setup', 'store');
            Route::get('/setup/{id}', 'show');
            Route::match(['put', 'patch'], '/setup/{id}', 'update');
        });

        // Unit Materials
        Route::group(['prefix' => 'units', 'controller' => UnitController::class], function () {
            Route::get('/setup', 'index');
            Route::post('/setup', 'store');
            Route::get('/setup/{unitCode}', 'show');
            Route::match(['put', 'patch'], '/setup/{unitCode}', 'update');
        });

        // Unit Type Materials
        Route::group(['prefix' => 'unit-types', 'controller' => UnitTypeController::class], function () {
            Route::get('/setup', 'index');
            Route::post('/setup', 'store');
            Route::get('/setup/{unitTypeCode}', 'show');
            Route::match(['put', 'patch'], '/setup/{unitTypeCode}', 'update');
        });

        // Cost Type Materials
        Route::group(['prefix' => 'cost-types', 'controller' => CostTypeController::class], function () {
            Route::get('/setup', 'index');
            Route::post('/setup', 'store');
            Route::get('/setup/{id}', 'show');
            Route::match(['put', 'patch'], '/setup/{id}', 'update');
        });

        // Plant Materials
        Route::group(['prefix' => 'plants', 'controller' => PlantController::class], function () {
            Route::get('/cost-center/{costCenterCode}', 'byCostCenter');
            Route::get('/setup', 'index');
            Route::post('/setup', 'store');
            Route::get('/setup/{plantCode}', 'show');
            Route::match(['put', 'patch'], '/setup/{plantCode}', 'update');
        });
    });
    /*--------------------------------------
               END::MATERIALS SCHEMA
    ---------------------------------------*/
});
