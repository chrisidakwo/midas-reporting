<?php

namespace App\Charts;

use ConsoleTVs\Charts\BaseChart;
use Illuminate\Support\Str;

abstract class Chart extends BaseChart {
	abstract public function colors(): array;

	/**
	 * @param array $params
	 * @return string
	 */
	public function route(array $params = []): string {
		$prefix = config('dashboard.charts.route_name_prefix', '');

		$name = $this->routeName ?? $this->name ?? Str::snake(class_basename(static::class));

		return route(($prefix ? "$prefix." : '') . $name, $params);
	}

	abstract public function type(): string;

	abstract public function options(): array;
}
