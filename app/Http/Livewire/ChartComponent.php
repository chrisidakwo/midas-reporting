<?php

namespace App\Http\Livewire;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

class ChartComponent extends Component {
	public string $chartClass;

	public array $chartFilters;

	public string $height;

	public int $refreshIntervalInSeconds;

	/**
	 * @param string $chartClass
	 * @param string $height
	 * @param array $chartFilters
	 * @param int|null $refreshIntervalInSeconds
	 */
	public function mount(
		string $chartClass,
		string $height = '100%',
		array $chartFilters = [],
		int $refreshIntervalInSeconds = null
	): void {
		$this->chartClass = $chartClass;
		$this->height = $height;
		$this->chartFilters = $chartFilters;

		Request::merge($this->chartFilters);

		$this->refreshIntervalInSeconds = $refreshIntervalInSeconds ?? config('dashboard.charts.refresh_interval_in_seconds', 300);
	}

	/**
	 * @return Application|Factory|View
	 */
	public function render() {
		return view('livewire.charts.chart', [
			'wireId' => $this->id,
			'chart' => app($this->chartClass)
		]);
	}
}
