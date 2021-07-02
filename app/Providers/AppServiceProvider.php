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
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider {
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {
		Collection::macro('sortByDate', function (string $column = 'created_at', bool $descending = false) {
			/* @var $this Collection */
			return $this->sortBy(function ($datum) use ($column) {
				return strtotime(((object)$datum)->$column);
			}, SORT_REGULAR, $descending);
		});
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot() {
		// Replace default migrate:fresh command class
		$this->app->bind(FreshCommand::class, MigrateFreshCommand::class);

		// Bind repositories to services
		$this->app->bind(DataRepository::class, DataService::class);
	}
}
