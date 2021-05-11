<?php

namespace App\Services\Dashboard;

use App\Services\Dashboard\Http\Components\DashboardComponent;
use App\Services\Dashboard\Http\Components\DashboardTileComponent;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class DashboardServiceProvider extends ServiceProvider {
	/**
	 * @throws BindingResolutionException
	 */
	public function boot() {
		$this->app->make(Dashboard::class)
			->script(config('dashboard.scripts.alpinejs'));

		$this->registerBladeComponents();
	}

	/**
	 * @inheritDoc
	 */
	public function register() {
		$this->app->singleton(Dashboard::class);

		$this->app->alias(Dashboard::class, 'dashboard');
	}

	public function registerBladeComponents() {
		Blade::component('dashboard', DashboardComponent::class);
		Blade::component('dashboard-tile', DashboardTileComponent::class);
	}
}
