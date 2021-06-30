<?php

namespace App\Services;

use App\Repositories\DataRepository;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use stdClass;

class DataService implements DataRepository {
	/**
	 * @inheritDoc
	 */
	public function getMovementSummary(Carbon $startDate, Carbon $endDate, int $state = null, int $borderPoint = null): array {
		$cacheKey = "movement_{$startDate}_{$endDate}_{$state}_$borderPoint";
		$ttl = setCacheTTL($startDate, $endDate);

		$movementSummary = Cache::remember($cacheKey, $ttl, static function () use ($startDate, $endDate, $state, $borderPoint) {
			return DB::table('MovementSummary')
				->whereBetween('TravelDate', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
				->when($state, function ($builder) use ($state) {
					return $builder->where('ProvinceID', $state);
				})
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
	public function getDoubtablePersons(Carbon $startDate, Carbon $endDate, int $state = null, int $borderPoint = null): Collection {
		$cacheKey = "doubtable_{$startDate}_{$endDate}_{$state}_$borderPoint";
		$ttl = setCacheTTL($startDate, $endDate);

		return Cache::remember($cacheKey, $ttl, static function () use ($startDate, $endDate, $state, $borderPoint) {
			$border = ($borderPoint ? "@BorderPoint=$borderPoint" : "@BorderPoint=NULL");
			$province = ($state ? "@Province=$state" : "@Province=NULL");

			return collect(DB::select("EXEC GetDoubtablePersons @Culture='en', @FromDate='$startDate', @ToDate='$endDate', $border, $province"));
		});
	}

	/**
	 * @inheritDoc
	 */
	public function getTravellersReportStatistics(Carbon $startDate, Carbon $endDate, int $state = null, array $borderPoint = []): Collection {
		$cacheKey = "travellers_report_{$startDate}_{$endDate}_" . json_encode($borderPoint);
		$ttl = setCacheTTL($startDate, $endDate);

		return Cache::remember($cacheKey, $ttl, static function () use ($startDate, $endDate, $state, $borderPoint) {
			return DB::table('ReportsPassengersStatistics')->where(function ($query) use ($startDate, $endDate) {
				return $query->whereDate('TravelDate', '>=', $startDate)
					->whereDate('TravelDate', '<=', $endDate);
			})->when(!empty($borderPoint), function ($builder) use ($borderPoint) {
				return $builder->whereIn('BorderPointID', $borderPoint);
			})->when(!empty($state), function ($builder) use($state) {
				return $builder->where('ProvinceID', $state);
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

	/**
	 * @inheritDoc
	 */
	public function getCountryByName(string $name): object {
		$cacheKey = "countries123_$name";

		return Cache::remember($cacheKey, setCacheTTL(), static function () use ($name) {
			$country = DB::select("EXEC GetCountryByName @culture='en', @countryName='$name'");

			return Arr::get($country, 0, (object) []);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function getProvincesByCountryId(int $countryID): array {
		$cacheKey = "provinces_$countryID";

		return Cache::remember($cacheKey, setCacheTTL(), static function () use ($countryID) {
			$states =  DB::select("EXEC GetProvincesByCountry @Culture='en', @countryID='$countryID'");

			return Arr::sort($states, 'Name');
		});
	}
}
