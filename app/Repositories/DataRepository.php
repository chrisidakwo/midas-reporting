<?php

namespace App\Repositories;

use Carbon\Carbon;

interface DataRepository {
	/**
	 * Get the total number of doubtable persons within the provided start and end dates
	 *
	 * @param Carbon $startDate
	 * @param Carbon $endDate
	 * @param int|string|null $borderPoint
	 * @return array
	 */
	public function getCountOfDoubtablePersons(Carbon $startDate, Carbon $endDate, $borderPoint = null): array;
}
