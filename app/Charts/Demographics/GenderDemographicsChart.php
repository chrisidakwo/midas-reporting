<?php

declare(strict_types=1);

namespace App\Charts\Demographics;

use App\Charts\Chart;
use App\Repositories\DataRepository;
use Chartisan\PHP\Chartisan;
use Illuminate\Http\Request;

class GenderDemographicsChart extends Chart {
	private DataRepository $dataRepository;

	public function __construct(DataRepository $dataRepository) {
		$this->dataRepository = $dataRepository;
	}

	public function handler(Request $request): Chartisan {
		[$start, $end] = getDefaultStartEndDates();
	}

	public function colors(): array {
		return ['#85CAC5', '#5CB8B2'];
	}

	public function type(): string {
		return 'pie';
	}

	public function options(): array {
		return [
			'responsive' => true,
			'maintainAspectRatio' => true,
			'legend' => [
				'display' => true,
				'position' => 'right',
				'align' => 'center'
			],
			'tooltip' => [
				'enabled' => true,
			]
//			'scales' => [
//				'xAxes' => ['display' => false],
//				'yAxes' => ['display' => false],
//			],
		];
	}
}
