<?php

declare(strict_types=1);

namespace App\Charts;

use Carbon\Carbon;
use Chartisan\PHP\Chartisan;
use Illuminate\Http\Request;

class TestChart extends Chart {
	/**
	 * @param Request $request
	 * @return Chartisan
	 */
	public function handler(Request $request): Chartisan {
		$date = Carbon::now()->subMonth()->startOfDay();

		$data = collect(range(0, 12))->map(function ($days) use ($date) {
			return [
				'x' => $date->clone()->addDays($days)->toDateString(),
				'y' => rand(100, 500),
			];
		});

		return Chartisan::build()
			->labels($data->pluck('x')->toArray())
			->dataset('Example Data', $data->toArray());
	}

	public function colors(): array {
		return ['#0033a0', '#FF5A1F'];
	}

	public function type(): string {
		return 'bar';
	}

	public function options(): array {
		return [
			'responsive' => true,
			'maintainAspectRatio' => false
		];
	}
}
