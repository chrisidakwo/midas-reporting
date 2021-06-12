<?php

namespace App\Http\Controllers;

use App\Repositories\DataRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MovementController extends Controller {
	/**
	 * @var DataRepository
	 */
	private DataRepository $dataRepository;

	public function __construct(DataRepository $dataRepository) {
		$this->dataRepository = $dataRepository;
	}

	public function index(Request $request) {
		[$start, $end] = getDefaultStartEndDates();

//		$travellers = $this->dataRepository->getTravellersReportStatistics($start, $end);
//
//		dd(collect($travellers)->groupBy('Sex')->map(static function ($value) {
//			return count($value);
//		}));

		return view('movement.index');
	}

	/**
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function getSummary(Request $request): JsonResponse {
		[$start, $end] = getDefaultStartEndDates($request);

		$movementSummary = $this->dataRepository->getMovementSummary($start, $end);

		$alertedPersons = count($this->dataRepository->getDoubtablePersons($start, $end));

		return response()->json(array_merge($movementSummary, [
			'Alerts' => $alertedPersons
		]));
	}

	/**
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function genderDemographics(Request $request): JsonResponse {
		[$start, $end] = getDefaultStartEndDates();

		$travellers = $this->dataRepository->getTravellersReportStatistics($start, $end);

		$data = collect($travellers)->groupBy('Sex')->map(static function ($value) {
			return count($value);
		})->values()->toArray();

		\Log::info('Gender Data', $data);

		return response()->json($data);
	}
}
