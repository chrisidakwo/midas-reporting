<?php

namespace App\Http\Livewire\Charts;

use App\Http\Livewire\ChartComponent;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class GenderChartComponent extends ChartComponent {
	/**
	 * @var array|int[] $genderStats
	 */
	public array $genderStats = [
		'maleCount' => 0,
		'femaleCount' => 0,
		'maleDiff' => 0,
		'femaleDiff' => 0
	];

//	protected $listeners = ['genderStatsUpdated' => 'updateGenderStats'];
//
//	public function updateGenderStats(array $value) {
//		$male = $value['male'];
//		$female = $value['female'];
//
//		$this->genderStats = [
//			'maleCount' => number_format($male),
//			'femaleCount' => number_format($female),
//			'maleDiff' => number_format(($male / ($female + $male)) * 100, 2),
//			'femaleDiff' => number_format(($female / ($female + $male)) * 100, 2)
//		];
//	}

	/**
	 * @return Application|Factory|View
	 */
	public function render() {
		return view('livewire.charts.gender_chart', [
			'wireId' => $this->id,
			'chart' => app($this->chartClass)
		]);
	}
}
