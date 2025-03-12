<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\LogoutController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\RoleController;

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
Route::post('/logout', LogoutController::class)->name('logout');

Route::middleware('auth:api')->group(function () {
    Route::apiResource('/roles', RoleController::class);
    // Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    // Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    // Route::put('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');
    // Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');
});

