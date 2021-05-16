<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class MovementStatisticsComponent extends Component {
	public Carbon $endDate;

	public array $movementSummary;

	public string $alertedPersons;

	public bool $loading;

	public int $refreshIntervalInSeconds;

	/**
	 * @param Carbon $endDate
	 * @param int|null $refreshIntervalInSeconds
	 */
	public function mount(Carbon $endDate, int $refreshIntervalInSeconds = null): void {
		$this->endDate = $endDate;

		$this->loading = false;

		$this->refreshIntervalInSeconds = $refreshIntervalInSeconds ?? config('dashboard.charts.refresh_interval_in_seconds', 300);

		$this->alertedPersons = '0';

		$this->movementSummary = [
			'Inbound' => 0,
			'Outbound' => 0
		];
	}

	/**
	 * @return Application|Factory|View
	 */
	public function render() {
		return view('livewire.movement-statistics');
	}
}
