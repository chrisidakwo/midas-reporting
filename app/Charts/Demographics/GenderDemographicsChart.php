<?php

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

		$travellers = $this->dataRepository->getTravellersReportStatistics($start, $end);

		$data = collect($travellers)->groupBy('Sex')->map(static function ($value) {
			return count($value);
		});

		return Chartisan::build()
			->labels(['Female', 'Male'])
			->dataset('Gender', $data->values()->toArray());

	}

	public function colors(): array {
		return [['#85CAC5', '#5CB8B2']];
	}

	public function type(): string {
		return 'doughnut';
	}

	public function options(): array {
		return [
			'responsive' => true,
			'maintainAspectRatio' => true,
			'cutoutPercentage' => 65,
			'legend' => [
				'display' => false
			],
			'hoverBorderWidth' => 0,
			'tooltip' => [
				'enabled' => true,
			],
			'animation' => [
				'animateRotate' => true,
				'animateScale' => true
			]
		];
	}
}
