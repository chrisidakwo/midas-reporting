<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MovementController extends Controller {
	public function index(Request $request) {
		return view('movement.index');
	}

	/**
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function getSummary(Request $request): JsonResponse {
		[$start, $end] = getDefaultStartEndDates($request);

		$start = $start->startOfDay();
		$end = $end->endOfDay();

		$movementSummary = Cache::remember("movement_" . "$start$end", now()->addYears(2), static function () use ($start, $end) {
			return DB::select("EXEC GetMovementStatistics @PeriodStart='$start', @PeriodEnd='$end', @RegionID=NULL, @borderPoint=NULL");
		});

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

		return response()->json($movementSummary);
	}
}
