<?php

namespace App\Providers;

use App\Http\Livewire\ChartComponent;
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
		Livewire::component('chart-tile', ChartComponent::class);
		Livewire::component('chart-tile', ChartComponent::class);

		Dashboard::script(config('dashboard.charts.scripts.chart'));

		Dashboard::script(config('dashboard.charts.scripts.chartisan'));
	}
}
