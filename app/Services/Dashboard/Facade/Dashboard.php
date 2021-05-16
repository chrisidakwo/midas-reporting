<?php

namespace App\Services\Dashboard\Facade;

use Illuminate\Support\Facades\Facade;

class Dashboard extends Facade {
	/**
	 * @inheritDoc
	 */
	protected static function getFacadeAccessor() {
		return 'dashboard';
	}
}
