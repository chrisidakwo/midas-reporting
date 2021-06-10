<?php

declare(strict_types=1);

namespace App\Charts\Demographics;

use App\Charts\Chart;
use App\Enums\TransportTypes;
use App\Repositories\DataRepository;
use Chartisan\PHP\Chartisan;
use Illuminate\Http\Request;

class TransportDemographicsChart extends Chart {
	private DataRepository $dataRepository;

	public function __construct(DataRepository $dataRepository) {
		$this->dataRepository = $dataRepository;
	}

	public function handler(Request $request): Chartisan {
		[$start, $end] = getDefaultStartEndDates();

		$travellers = $this->dataRepository->getTravellersReportStatistics($start, $end);

		$data = collect($travellers)->groupBy('TransportType')->map(static function ($value) {
			return count($value);
		})->reduce(function (&$carry, $value, $key) {
			// Get the group for the said type
			$group = TransportTypes::TYPE_GROUP[$key];

			if (array_key_exists($group, $carry)) {
				$carry[$group] += $value;
			} else {
				$carry[$group] = $value;
			}

			return $carry;
		}, []);

		return Chartisan::build()
			->labels(TransportTypes::GROUPS)
			->dataset('Transportation Mode', array_values(array_merge($data, [
				'Sea' => 12790,
				'Land' => 23180
			])));
	}

	public function colors(): array {
		return [['rgba(91,146,229,0.85)', 'rgba(132,174,236,0.85)', 'rgba(173,201,242,0.85)']];
	}

	public function type(): string {
		return 'horizontalBar';
	}

	public function options(): array {
		return [
			'responsive' => true,
			'maintainAspectRatio' => true,
			'legend' => [
				'display' => false
			],
			'tooltips' => [
				'displayColors' => true
			],
			'indexAxis' => 'y',
			'scales' => [
				'xAxes' => [[
					'stacked' => true,
					'gridLines' => [
						'display' => false
					],
					'ticks' => [
						'display' => false
					]
				]],
				'yAxes' => [[
					'stacked' => true,
					'gridLines' => [
						'display' => false
					],
					'ticks' => [
						'display' => false
					]
				]]
			]
		];
	}
}
