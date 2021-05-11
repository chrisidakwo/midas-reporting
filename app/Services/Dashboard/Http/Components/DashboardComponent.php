<?php

namespace App\Services\Dashboard\Http\Components;

use App\Services\Dashboard\Dashboard;
use Illuminate\Support\HtmlString;
use Illuminate\View\Component;

class DashboardComponent extends Component {
	public HtmlString $assets;

	public function __construct(Dashboard $dashboard) {
		$this->assets = $dashboard->assets();
	}

	/**
	 * @inheritDoc
	 */
	public function render() {
		return view('livewire.dashboard.dashboard');
	}
}
