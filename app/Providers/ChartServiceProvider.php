<?php

namespace App\Providers;

use App\Http\Livewire\Charts\GenderChartComponent;
use App\Services\Dashboard\Facade\Dashboard;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class ChartServiceProvider extends ServiceProvider {
	/**
	 * Bootstrap services.
	 *
	 * @return void
	 */
	public function boot() {
		Livewire::component('gender-chart-tile', GenderChartComponent::class);

		Dashboard::script(config('dashboard.charts.scripts.chart'));

		Dashboard::script(config('dashboard.charts.scripts.chartisan'));
	}
}
