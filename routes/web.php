<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MovementController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::domain(config('app.domain'))->group(function () {
	Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
	Route::get('/reports', [ReportController::class, 'index'])->name('reports');

	Route::group(['prefix' => 'movement', 'as' => 'movement.'], static function () {
		Route::get('/', [MovementController::class, 'index'])->name('index');
		Route::get('/summary', [MovementController::class, 'getSummary'])->name('summary');

		Route::group(['prefix' => 'demographics'], static function () {
			Route::get('/', [MovementController::class, 'demographics'])->name('demographics');
		});
	});
});
