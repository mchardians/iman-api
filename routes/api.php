<?php

use App\Http\Controllers\Api\ActivityScheduleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentController;
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
use App\Models\ActivitySchedule;

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

    Route::post('/forgot-password', ForgotPasswordController::class)
    ->name('api.auth.forgot_password')
    ->middleware("attempt.throttle:3,15");
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
    ->name('api.auth.reset_password');
});

Route::get('/public/news', [NewsController::class, 'publicIndex'])->name('public.news');
Route::get('/public/news/{slug}', [NewsController::class, 'showBySlug'])->name('public.news.show.slug');
Route::get('/public/news/{news}/comments', [CommentController::class, 'index'])->name('public.news.comment.index');
Route::get('/public/facilities', [FacilityController::class, 'publicIndex'])->name('public.facilities.index');

Route::middleware(['auth:api', 'role:administrator'])->name('api.')->group(function() {
    Route::apiResource('/roles', RoleController::class)->names('roles');
    Route::apiResource('/users', UserController::class)->names('users');
    Route::apiResource('/finance-categories', FinanceCategoryController::class)->names('finance_categories');
    Route::apiResource('/finance-incomes', FinanceIncomeController::class)->names('finance_incomes');
    Route::apiResource('/finance-expenses', FinanceExpenseController::class)->names('finance_expenses');
    Route::get('/finance-recapitulations', [FinanceRecapitulationController::class, 'index'])->name('finance_recapitulations.index');
    Route::get('/finance-recapitulations/export/preview', [FinanceRecapitulationController::class, 'preview'])->name('finance_recapitulations.export.preview');
    Route::get('/finance-recapitulations/export', [FinanceRecapitulationController::class, 'export'])->name('finance_recapitulations.export');
    Route::apiResource('/news-categories', NewsCategoryController::class)->names('news_categories');
    Route::apiResource('/news', NewsController::class)->names('news');
    Route::patch('/news/{news}/status', [NewsController::class, 'setStatus'])->name('news.status');
    Route::get('/news/{news}/comments', [CommentController::class, 'index'])->name('news.comment.index');
    Route::post('/news/{news}/comments', [CommentController::class, 'store'])->name('news.comment.store');
    Route::apiResource('/comments', CommentController::class)->only(['update', 'destroy'])->names('comments');
    Route::apiResource('/facilities', FacilityController::class)->names('facilities');
    // Route::get('/activity-schedules/upcomings', [ActivityScheduleController::class, 'comingUp'])->name('activity_schedule.upcomings');
    Route::apiResource('/activity-schedules', ActivityScheduleController::class)->names('activity_schedules');
    Route::patch('/activity-schedules/{activity_schedule}/status', [ActivityScheduleController::class, 'setStatus'])->name('activity_schedule.status');
});

Route::middleware(['auth:api', 'role:jamaah-umum'])->name('api.')->group(function() {
    Route::post('/news/{news}/comments', [CommentController::class, 'store'])->name('news.store');
});