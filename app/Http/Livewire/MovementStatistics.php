<?php

namespace App\Http\Livewire;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class MovementStatistics extends Component {
	public array $movementSummary;

	public string $startDate;

	public string $endDate;

	public bool $loading;

	/**
	 * @param string $startDate
	 * @param string $endDate
	 */
	public function mount(string $startDate, string $endDate): void {
		$this->startDate = $startDate;
		$this->endDate = $endDate;

		$this->loading = true;

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
