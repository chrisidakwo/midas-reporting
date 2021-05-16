<?php

namespace App\Http\Controllers;

use App\Repositories\DataRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MovementController extends Controller {
	/**
	 * @var DataRepository
	 */
	private DataRepository $dataRepository;

	public function __construct(DataRepository $dataRepository) {
		$this->dataRepository = $dataRepository;
	}

	public function index(Request $request) {
		return view('movement.index');
	}

	/**
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function getSummary(Request $request): JsonResponse {
		[$start, $end] = getDefaultStartEndDates($request);
		$ttl = setCacheTTL($start, $end);

		$movementSummary = Cache::remember("movement_" . "$start$end", $ttl, static function () use ($start, $end) {
			return DB::select("EXEC GetMovementStatistics @PeriodStart='$start', @PeriodEnd='$end', @RegionID=NULL, @borderPoint=NULL");
		});

		$alertedPersons = count($this->dataRepository->getCountOfDoubtablePersons($start, $end));

		if (!empty($movementSummary)) {
			$movementSummary = collect($movementSummary)->reduce(function ($carry, $value, $key) {
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

		return response()->json(array_merge($movementSummary, [
			'Alerts' => $alertedPersons
		]));
	}
}
