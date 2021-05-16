<?php

namespace App\Services;

use App\Repositories\DataRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DataService implements DataRepository {
	/**
	 * @param Carbon $startDate
	 * @param Carbon $endDate
	 * @param null $borderPoint
	 * @return array
	 */
	public function getCountOfDoubtablePersons(Carbon $startDate, Carbon $endDate, $borderPoint = null): array {
		$cacheKey = "doubtable_$startDate$endDate";
		$ttl = setCacheTTL($startDate, $endDate);

		return Cache::remember($cacheKey, $ttl, static function () use ($startDate, $endDate, $borderPoint) {
			return DB::select("EXEC GetDoubtablePersons @Culture='en', @FromDate='$startDate', @ToDate='$endDate', " . ($borderPoint ? "@BorderPoint='$borderPoint'" : "@BorderPoint=NULL"));
		});
	}
}
