<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ResetPasswordController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\FacilityController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\NewsCategoryController;
use App\Http\Controllers\Api\FinanceCategoryController;
use App\Http\Controllers\Api\FinanceExpenseController;
use App\Http\Controllers\Api\FinanceIncomeController;
use App\Http\Controllers\Api\FinanceRecapitulationController;
use App\Http\Controllers\Api\ForgotPasswordController;

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

    Route::post('/forgot-password', ForgotPasswordController::class)->name('api.auth.forgot_password');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('api.auth.reset_password');
});


Route::get('/news/published', [NewsController::class, 'expose'])->name('news.public');
Route::get('/facilities', [FacilityController::class, 'index'])->name('facilities.index');

Route::middleware(['auth:api', 'role:administrator'])->name('api.')->group(function() {
    Route::apiResource('/roles', RoleController::class)->names('roles');
    Route::apiResource('/users', UserController::class)->names('users');
    Route::prefix('finance')->name('finance.')->group(function() {
        Route::apiResource('/categories', FinanceCategoryController::class)
        ->names('categories');
        Route::apiResource('/incomes', FinanceIncomeController::class)
        ->names('incomes');
        Route::apiResource('/expenses', FinanceExpenseController::class)
        ->names('expenses');
        Route::get('/recapitulations', FinanceRecapitulationController::class)
        ->name('recapitulations.index');
    });
    Route::apiResource('/news-categories', NewsCategoryController::class)->names('news_categories');
    Route::apiResource('/news', NewsController::class)->names('news');
    Route::post('/news/{id}/publish', [NewsController::class, 'publish'])->name('news.publish');
    Route::post('/news/{id}/archive', [NewsController::class, 'archive'])->name('news.archive');
    Route::apiResource('/facilities', FacilityController::class)
    ->except('index')
    ->names('facilities');
});