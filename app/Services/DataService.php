<?php

namespace App\Services;

use App\Repositories\DataRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DataService implements DataRepository {
	/**
	 * @inheritDoc
	 */
	public function getMovementSummary(Carbon $startDate, Carbon $endDate, int $borderPoint = null): array {
		$cacheKey = "movement_$startDate$endDate$borderPoint";
		$ttl = setCacheTTL($startDate, $endDate);

		$movementSummary = Cache::remember($cacheKey, $ttl, static function () use ($startDate, $endDate, $borderPoint) {
			return DB::select("EXEC GetMovementStatistics @PeriodStart='$startDate', @PeriodEnd='$endDate', @RegionID=NULL, " . ($borderPoint ? "@BorderPoint='$borderPoint'" : "@BorderPoint=NULL"));
		});

		if (!empty($movementSummary)) {
			$movementSummary = collect($movementSummary)->reduce(function ($carry, $value) {
				if (empty($carry)) {
					return [
						'Inbound' => $value->TotalEntryPassangers,
						'Outbound' => $value->TotalExitPassangers
					];
				} else {
					return [
						'Inbound' => $carry['Inbound'] + $value->TotalEntryPassangers,
						'Outbound' => $carry['Outbound'] + $value->TotalExitPassangers
					];
				}
			}, []);
		} else {
			$movementSummary = [
				'Inbound' => 0,
				'Outbound' => 0
			];
		}

		return $movementSummary;
	}

	/**
	 * @inheritDoc
	 */
	public function getDoubtablePersons(Carbon $startDate, Carbon $endDate, int $borderPoint = null): array {
		$cacheKey = "doubtable_$startDate$endDate$borderPoint";
		$ttl = setCacheTTL($startDate, $endDate);

		return Cache::remember($cacheKey, $ttl, static function () use ($startDate, $endDate, $borderPoint) {
			return DB::select("EXEC GetDoubtablePersons @Culture='en', @FromDate='$startDate', @ToDate='$endDate', " . ($borderPoint ? "@BorderPoint='$borderPoint'" : "@BorderPoint=NULL"));
		});
	}

	/**
	 * @inheritDoc
	 */
	public function getTravellersReportStatistics(Carbon $startDate, Carbon $endDate, int $borderPoint = null): array {
		$cacheKey = "travellers_report_$startDate$endDate$borderPoint";
		$ttl = setCacheTTL($startDate, $endDate);

		return Cache::remember($cacheKey, $ttl, static function () use ($startDate, $endDate, $borderPoint) {
			return DB::select("EXEC GetReportsPassengersStatistics @culture='en', @FromDate='$startDate', @ToDate='$endDate', " . ($borderPoint ? "@BorderPoint='$borderPoint'" : "@BorderPoint=NULL"));
		});
	}

	/**
	 * @inheritDoc
	 */
	public function getBorderPoints(array $filters = []): Collection {
		$cacheKey = "border_points_" . json_encode($filters);

		return Cache::remember($cacheKey, setCacheTTL(), static function () use ($filters) {
			return DB::table('BorderPoints')->when(!empty($filters), function ($query) use ($filters) {
				$query->where($filters);
			})->get();
		});
	}
}
