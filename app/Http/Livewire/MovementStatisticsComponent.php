<?php

namespace App\Http\Livewire;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class MovementStatisticsComponent extends Component {
	public array $movementSummary;

	public string $alertedPersons;

	public string $startDate;

	public string $endDate;

	public bool $loading;

	public int $refreshIntervalInSeconds;

	/**
	 * @param string $startDate
	 * @param string $endDate
	 * @param int|null $refreshIntervalInSeconds
	 */
	public function mount(string $startDate, string $endDate, int $refreshIntervalInSeconds = null): void {
		$this->startDate = $startDate;
		$this->endDate = $endDate;

		$this->loading = true;

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
