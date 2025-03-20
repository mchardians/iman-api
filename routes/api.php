<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\LogoutController;
use App\Http\Controllers\Api\FacilityController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\InfaqTypeController;
use App\Http\Controllers\Api\NewsCategoryController;
use App\Http\Controllers\Api\EventScheduleController;
use App\Http\Controllers\Api\ExpenseTransactionController;
use App\Http\Controllers\Api\FacilityReservationController;
use App\Http\Controllers\Api\IncomeInfaqTransactionController;
use App\Http\Controllers\Api\InventoryTransactionController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register', RegisterController::class)->name('register');
Route::post('/login', LoginController::class)->name('login');

Route::middleware('auth:api')->group(function () {
    Route::apiResource('/roles', RoleController::class);
    Route::apiResource('/users', UserController::class);
    Route::apiResource('/items', ItemController::class);
    Route::apiResource('/facilities', FacilityController::class);
    Route::apiResource('/infaq-types', InfaqTypeController::class);
    Route::apiResource('/income-infaq-transactions', IncomeInfaqTransactionController::class);
    Route::apiResource('/expense-transactions', ExpenseTransactionController::class);
    Route::apiResource('/inventory-transactions', InventoryTransactionController::class);
    Route::apiResource('/events', EventController::class);
    Route::apiResource('/event-schedules', EventScheduleController::class);
    Route::apiResource('/news-categories', NewsCategoryController::class);
    Route::apiResource('/news', NewsController::class);
    Route::apiResource('/facility-reservations', FacilityReservationController::class);

    Route::post('/logout', LogoutController::class)->name('logout');
});

