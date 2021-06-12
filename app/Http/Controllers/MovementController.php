<?php

namespace App\Http\Controllers;

use App\Enums\TransportTypes;
use App\Repositories\DataRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

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

	public function demographics(Request $request): JsonResponse {
		[$start, $end] = getDefaultStartEndDates();

		$travellers = $this->dataRepository->getTravellersReportStatistics($start, $end);

		$gender = collect($travellers)->groupBy('Sex')->map(static function ($value) {
			return count($value);
		})->values()->toArray();

		$transport = collect($travellers)->groupBy('TransportType')->map(static function ($value) {
			return count($value);
		})->reduce(function (&$carry, $value, $key) {
			// Get the group for the said type
			$group = TransportTypes::TYPE_GROUP[$key];

			if (array_key_exists($group, $carry)) {
				$carry[$group] += $value;
			} else {
				$carry[$group] = $value;
			}

			return $carry;
		}, []);

		$age = collect($travellers)->map(function ($value) {
			// get current age
			$value->Age = date_diff(Carbon::parse($value->DateOfBirth), today())->y;

			return $value;
		})->reduce(function (&$carry, $value, $key) {
			$age = $value->Age;

			// Under 10
			if ($age <= 10) {
				$carry['10'] = Arr::get($carry, '10', 0) + 1;
			} else if ($age > 10 && $age <= 17) {
				// 11 to 17
				$carry['17'] = Arr::get($carry, '17', 0) + 1;
			} else if ($age > 17 && $age <= 40) {
				// 18 to 40
				$carry['40'] = Arr::get($carry, '40', 0) + 1;
			} else if ($age > 40 && $age <= 65) {
				// 41 to 65
				$carry['65'] = Arr::get($carry, '65', 0) + 1;
			} else if ($age > 65) {
				$carry['65+'] = Arr::get($carry, '65+', 0) + 1;
			}

			return $carry;
		}, []);

		return response()->json([
			'gender' => $gender,
			'transport' => $transport,
			'age' => $age
		]);
	}
}
