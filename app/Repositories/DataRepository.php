<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Collection;

interface DataRepository {
	/**
	 * Get summary of passengers' movements within the provided start and end dates, and border point.
	 *
	 * @param Carbon $startDate
	 * @param Carbon $endDate
	 * @param int|null $borderPoint
	 * @return array
	 */
	public function getMovementSummary(Carbon $startDate, Carbon $endDate, int $borderPoint = null): array;

	/**
	 * Get the total number of doubtable persons within the provided start and end dates.
	 *
	 * @param Carbon $startDate
	 * @param Carbon $endDate
	 * @param int|null $borderPoint
	 * @return array
	 */
	public function getDoubtablePersons(Carbon $startDate, Carbon $endDate, int $borderPoint = null): array;

	/**
	 * Get a full report of travellers (including personal and border data) within the provided start and end dates.
	 *
	 * @param Carbon $startDate
	 * @param Carbon $endDate
	 * @param int|null $borderPoint
	 * @return array
	 */
	public function getTravellersReportStatistics(Carbon $startDate, Carbon $endDate, int $borderPoint = null): array;

	/**
	 * @param array $filters
	 * @return Collection
	 */
	public function getBorderPoints(array $filters = []): Collection;
}
