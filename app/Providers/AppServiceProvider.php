<?php

namespace App\Providers;

use App\Charts\Demographics\AgeDemographicsChart;
use App\Charts\Demographics\GenderDemographicsChart;
use App\Charts\Demographics\TransportDemographicsChart;
use App\Console\Commands\MigrateFreshCommand;
use App\Http\Livewire\DateSelectorComponent;
use App\Http\Livewire\MovementStatisticsComponent;
use App\Repositories\DataRepository;
use App\Services\DataService;
use ConsoleTVs\Charts\Registrar as Charts;
use Illuminate\Database\Console\Migrations\FreshCommand;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

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
	 * @param Charts $charts
	 * @return void
	 */
	public function boot(Charts $charts) {
		// Replace default migrate:fresh command class
		$this->app->bind(FreshCommand::class, MigrateFreshCommand::class);

		// Bind repositories to services
		$this->app->bind(DataRepository::class, DataService::class);
	}
}
