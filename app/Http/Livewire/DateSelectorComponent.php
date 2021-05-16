<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;

class DateSelectorComponent extends Component {
	public Carbon $startDate;

	public Carbon $endDate;

	public function mount(Carbon $startDate, Carbon $endDate) {
		$this->startDate = $startDate;
		$this->endDate = $endDate;
	}

	public function render() {
		return view('livewire.date-selector');
	}
}
