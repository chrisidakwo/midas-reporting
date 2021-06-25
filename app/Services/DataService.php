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
		$cacheKey = "movement_{$startDate}_{$endDate}_$borderPoint";
		$ttl = setCacheTTL($startDate, $endDate);

		$movementSummary = Cache::remember($cacheKey, $ttl, static function () use ($startDate, $endDate, $borderPoint) {
			return DB::table('MovementSummary')->whereBetween('TravelDate', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
				->when($borderPoint, function ($builder) use ($borderPoint) {
					return $builder->where('BorderPointID', $borderPoint);
				})->get();
		});

		if (!empty($movementSummary)) {
			$movementSummary = $movementSummary->reduce(function ($carry, $value) {
				if (empty($carry)) {
					return [
						'Inbound' => $value->TotalEntryPassengers,
						'Outbound' => $value->TotalExitPassengers
					];
				} else {
					return [
						'Inbound' => $carry['Inbound'] + $value->TotalEntryPassengers,
						'Outbound' => $carry['Outbound'] + $value->TotalExitPassengers
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
	public function getDoubtablePersons(Carbon $startDate, Carbon $endDate, int $borderPoint = null): Collection {
		$cacheKey = "doubtable_{$startDate}_{$endDate}_$borderPoint";
		$ttl = setCacheTTL($startDate, $endDate);

		return Cache::remember($cacheKey, $ttl, static function () use ($startDate, $endDate, $borderPoint) {
			return collect(DB::select("EXEC GetDoubtablePersons @Culture='en', @FromDate='$startDate', @ToDate='$endDate', " . ($borderPoint ? "@BorderPoint=$borderPoint" : "@BorderPoint=NULL")));
		});
	}

	/**
	 * @inheritDoc
	 */
	public function getTravellersReportStatistics(Carbon $startDate, Carbon $endDate, int $borderPoint = null) {
		$cacheKey = "travellers_report_{$startDate}_{$endDate}_$borderPoint";
		$ttl = setCacheTTL($startDate, $endDate);

		return Cache::remember($cacheKey, $ttl, static function () use ($startDate, $endDate, $borderPoint) {
			return DB::table('ReportsPassengersStatistics')->where(function ($query) use ($startDate, $endDate) {
				return $query->whereDate('TravelDate', '>=', $startDate)
					->whereDate('TravelDate', '<=', $endDate);
			})->when(!empty($borderPoint), function ($builder) use ($borderPoint) {
				return $builder->where('BorderPointID', $borderPoint);
			})->get();
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
