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

		$borderPoint = $request->get('border');

		$movementSummary = $this->dataRepository->getMovementSummary($start, $end, $borderPoint);

		$alertedPersons = count($this->dataRepository->getDoubtablePersons($start, $end));

		return response()->json(array_merge($movementSummary, [
			'Alerts' => $alertedPersons
		]));
	}

	public function demographics(Request $request): JsonResponse {
		[$start, $end] = getDefaultStartEndDates();

		$borderPoint = $request->get('border');

		$travellers = $this->dataRepository->getTravellersReportStatistics($start, $end, $borderPoint);

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

	/**
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function trafficByState(Request $request): JsonResponse {
		$states = $request->get('states');
		if ($states) {
			$states = explode(',', $states);
		}

		[$start, $end] = getDefaultStartEndDates();

		$travellers = $this->dataRepository->getTravellersReportStatistics($start, $end);

		$traffic = collect($travellers)->groupBy(['BorderPointID'])->toArray();

		$statesArr = [];
		$series = [];

		foreach ($traffic as $borderPointID => $trafficStat) {
			$_state = $this->dataRepository->getBorderPoints([['OwnerID', $borderPointID]])
				->when(!empty($states), function ($query) use ($states) {
					return $query->filter(function ($value) use ($states) {
						return in_array($value->State, $states);
					});
				})->first();

			if ($_state) {
				// Get the state for the border and assign to states array
				$state = $_state->State;

				// Get the transport group for the border point
				$transportType = $trafficStat[0]->TransportType; // Considering all records within this border point will always have the same TransportType value
				$transportGroup = TransportTypes::TYPE_GROUP[$transportType];

				$borderTrafficCount = count($trafficStat);

				if (array_key_exists($state, $statesArr)) {
					$stateIndex = array_search($state, $statesArr);

					// At the point, $series[$transportGroup][$stateIndex] must have a value because we set a default series for the state (in the else block when we added the state to the states array)
					$series[$transportGroup][$stateIndex] += $borderTrafficCount;
				} else {
					// Set default values for transport groups
					$series['Air'][] = 0;
					$series['Land'][] = 0;
					$series['Sea'][] = 0;

					// Add state to state array
					$statesArr[] = $state;

					// update the series array with actual border transport count
					$stateIndex = array_search($state, $statesArr);
					$series[$transportGroup][$stateIndex] = $borderTrafficCount;
				}
			}
		}

		$series = array_map(function ($value, $key) {
			return [
				'name' => $key,
				'data' => $value
			];
		}, $series, array_keys($series));

		return response()->json([
			'traffic' => $series,
			'states' => $statesArr
		]);
	}
}
