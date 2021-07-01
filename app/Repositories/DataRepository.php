<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Collection;
use stdClass;

interface DataRepository {
	/**
	 * Get summary of passengers' movements within the provided start and end dates, and border point.
	 *
	 * @param Carbon $startDate
	 * @param Carbon $endDate
	 * @param int|null $state
	 * @param int|null $borderPoint
	 * @return array
	 */
	public function getMovementSummary(Carbon $startDate, Carbon $endDate, int $state = null, int $borderPoint = null): array;

	/**
	 * Get the total number of doubtable persons within the provided start and end dates.
	 *
	 * @param Carbon $startDate
	 * @param Carbon $endDate
	 * @param int|null $state
	 * @param int|null $borderPoint
	 * @return Collection
	 */
	public function getDoubtablePersons(Carbon $startDate, Carbon $endDate, int $state = null, int $borderPoint = null): Collection;

	/**
	 * Get a full report of travellers (including personal and border data) within the provided start and end dates.
	 *
	 * @param Carbon $startDate
	 * @param Carbon $endDate
	 * @param int|null $state
	 * @param array $borderPoint
	 * @param array $columns
	 * @param bool $paginate
	 * @return Collection|Paginator
	 */
	public function getTravellersReportStatistics(Carbon $startDate, Carbon $endDate, int $state = null,
	                                              array $borderPoint = [], array $columns = [],
	                                              bool $paginate = false);

	/**
	 * @param array $filters
	 * @return Collection
	 */
	public function getBorderPoints(array $filters = []): Collection;

	/**
	 * Get a country by the given name.
	 *
	 * @param string $name
	 * @return object|stdClass
	 */
	public function getCountryByName(string $name): object;

	/**
	 * Get a list of ll provinces associated to the given country ID.
	 *
	 * @param int $countryID
	 * @return array
	 */
	public function getProvincesByCountryId(int $countryID): array;
}
