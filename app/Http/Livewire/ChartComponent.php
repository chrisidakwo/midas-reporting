<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Request;
use Livewire\Component;

abstract class ChartComponent extends Component {
	public string $title;

	public string $chartClass;

	public array $chartFilters;

	public string $height;

	public int $refreshIntervalInSeconds;

	/**
	 * @param string $chartClass
	 * @param string $title
	 * @param string $height
	 * @param array $chartFilters
	 * @param int|null $refreshIntervalInSeconds
	 */
	public function mount(
		string $chartClass,
		string $title,
		string $height = '100%',
		array $chartFilters = [],
		int $refreshIntervalInSeconds = null
	): void {
		$this->chartClass = $chartClass;
		$this->title = $title;
		$this->height = $height;
		$this->chartFilters = $chartFilters;

		Request::merge($this->chartFilters);

		$this->refreshIntervalInSeconds = $refreshIntervalInSeconds ?? config('dashboard.charts.refresh_interval_in_seconds', 300);
	}
}
