<?php

use App\Http\Controllers\BCPController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InsightController;
use App\Http\Controllers\MovementController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VisaController;
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
	Route::get('/bcp-records', [BCPController::class, 'index'])->name('bcp.records');
	Route::get('/visas', [VisaController::class, 'index'])->name('visas');
	Route::get('/insights', [InsightController::class, 'index'])->name('insights');
	Route::get('/reports', [ReportController::class, 'index'])->name('reports');
	Route::get('/border-points', [DashboardController::class, 'borderPoints'])->name('border_points');

	Route::group(['prefix' => 'movement', 'as' => 'movement.'], static function () {
		Route::get('/', [MovementController::class, 'index'])->name('index');
		Route::get('/summary', [MovementController::class, 'getSummary'])->name('summary');

		Route::get('/traffic', [MovementController::class, 'trafficByState']);

		Route::group(['prefix' => 'demographics'], static function () {
			Route::get('/{type}', [MovementController::class, 'demographics'])->name('demographics');
		});
	});
});
