<?php

namespace App\Providers;

use App\Charts\TestChart;
use App\Console\Commands\MigrateFreshCommand;
use ConsoleTVs\Charts\Registrar as Charts;
use Illuminate\Database\Console\Migrations\FreshCommand;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {
		//
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot(Charts $charts) {
		$this->app->bind(FreshCommand::class, MigrateFreshCommand::class);

		// Register charts
		$charts->register([
			TestChart::class
		]);
	}
}
