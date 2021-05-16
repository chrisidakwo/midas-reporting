<?php

namespace App\Providers;

use App\Console\Commands\MigrateFreshCommand;
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
	public function boot() {
		$this->app->bind(FreshCommand::class, MigrateFreshCommand::class);

		// Register charts
		app(\ConsoleTVs\Charts\Registrar::class)->register([
			\App\Charts\TestChart::class
		]);
	}
}
