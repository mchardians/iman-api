<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ResetPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\FacilityController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\InfaqTypeController;
use App\Http\Controllers\Api\NewsCategoryController;
use App\Http\Controllers\Api\EventScheduleController;
use App\Http\Controllers\Api\ExpenseTransactionController;
use App\Http\Controllers\Api\FacilityReservationController;
use App\Http\Controllers\Api\FinanceCategoryController;
use App\Http\Controllers\Api\FinanceExpenseController;
use App\Http\Controllers\Api\FinanceIncomeController;
use App\Http\Controllers\Api\FinanceRecapitulationController;
use App\Http\Controllers\Api\ForgotPasswordController;
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

Route::post('/register', RegisterController::class)->name('api.register');

Route::prefix('auth')->group(function() {
    Route::post('/login', [AuthController::class, 'login'])->name('api.auth.login');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('api.auth.refresh');

    Route::middleware('auth:api')->group(function() {
        Route::post('/me', [AuthController::class, 'me'])->name('api.auth.me');
        Route::post('/logout', [AuthController::class, 'logout'])->name('api.auth.logout');
    });
});

Route::post('/forgot-password', ForgotPasswordController::class)
->middleware('throttle:3,60')->name('api.forgot_password');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
->name('api.reset_password');

Route::middleware(['auth:api', 'role:administrator'])->name('api.')->group(function() {
    Route::apiResource('/roles', RoleController::class)->names('users');
    Route::apiResource('/users', UserController::class)->names('roles');
    Route::prefix('finance')->name('finance.')->group(function() {
        Route::apiResource('/categories', FinanceCategoryController::class)
        ->names('categories');
        Route::apiResource('/incomes', FinanceIncomeController::class)
        ->names('incomes');
        Route::apiResource('/expenses', FinanceExpenseController::class)
        ->names('expenses');
        Route::get('/recapitulations', FinanceRecapitulationController::class)
        ->name('recapitulations');
    });
    Route::apiResource('/news-categories', NewsCategoryController::class)
    ->names('news_categories');
    Route::apiResource('/news', NewsController::class)->names('news');
    Route::post('/news/{id}/publish', [NewsController::class, 'publish'])->name('news.publish');
    Route::post('/news/{id}/archive', [NewsController::class, 'archive'])->name('news.archive');
});

Route::prefix('unimportant')->group(function() {
    Route::apiResource('/events', EventController::class);
    Route::apiResource('/event-schedules', EventScheduleController::class);
    Route::apiResource('/infaq-types', InfaqTypeController::class);
    Route::apiResource('/facilities', FacilityController::class);
    Route::apiResource('/facility-reservations', FacilityReservationController::class);
    Route::apiResource('/items', ItemController::class);
    Route::apiResource('/income-infaq-transactions', IncomeInfaqTransactionController::class);
    Route::apiResource('/expense-transactions', ExpenseTransactionController::class);
    Route::apiResource('/inventory-transactions', InventoryTransactionController::class);
});