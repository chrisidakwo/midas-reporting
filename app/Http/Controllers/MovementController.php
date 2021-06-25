<?php

namespace App\Http\Controllers;

use App\Enums\TransportTypes;
use App\Repositories\DataRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Inertia;

class MovementController extends Controller {
	private DataRepository $dataRepository;

	/**
	 * MovementController constructor.
	 *
	 * @param DataRepository $dataRepository
	 */
	public function __construct(DataRepository $dataRepository) {
		$this->dataRepository = $dataRepository;
	}

	/**
	 * @param Request $request
	 * @return Inertia\Response
	 */
	public function index(Request $request): Inertia\Response {
		[$start, $end] = getDefaultStartEndDates();

		return Inertia::render('Movement', compact('start', 'end'));
	}

	/**
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function getSummary(Request $request): JsonResponse {
		[$start, $end] = getDefaultStartEndDates($request);

		$borderPoint = $request->get('border');
		if ($borderPoint === 'null') {
			$borderPoint = null;
		}

		$movementSummary = $this->dataRepository->getMovementSummary($start, $end, $borderPoint);

		$alertedPersons = $this->dataRepository->getDoubtablePersons($start, $end);

		$deniedPersons = $alertedPersons->filter(function ($value) {
			return $value->PerformedAction != 'Permit';
		});

		return response()->json(array_merge($movementSummary, [
			'Alerts' => count($alertedPersons),
			'Denied' => count($deniedPersons)
		]));
	}

	/**
	 * Get gender statistics.
	 *
	 * @param Collection $travellers
	 * @return array
	 */
	private function _getGenderStats(Collection $travellers): array {
		return $travellers->groupBy('Sex')->map(static function ($value) {
			return count($value);
		})->values()->toArray();
	}

	/**
	 * Get age group statistics.
	 *
	 * @param Collection $travellers
	 * @return array
	 */
	private function _getAgeGroupStats(Collection $travellers): array {
		return $travellers->map(function ($value) {
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
	}

	/**
	 * Get transport mode statistics.
	 *
	 * @param Collection $travellers
	 * @return array
	 */
	private function _getTransportModeStats(Collection $travellers): array {
		return $travellers->groupBy('TransportType')->map(static function ($value) {
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
	}

	/**
	 * Get travel destinations statistics.
	 *
	 * @param Collection $travellers
	 * @return array
	 */
	private function _getTravelDestinationStats(Collection $travellers): array {
		return $travellers->where('MovementDirection', 'Departure')->groupBy('Destination')
			->reduce(function ($carry, $item, $key) {
				$carry[] = [$key, count($item)];

				return $carry;
			}, [['Country', 'Travellers']]);
	}

	/**
	 * @param Request $request
	 * @param $type
	 * @return JsonResponse
	 */
	public function demographics(Request $request, $type): JsonResponse {
		[$start, $end] = getDefaultStartEndDates();

		$direction = $request->get('direction');
		if ($direction && ($direction == 'entry')) {
			$direction = 'Arrival';
		} else if ($direction && ($direction == 'exit')) {
			$direction = 'Departure';
		}

		$borderPoint = $request->get('border');
		if ($borderPoint === 'null') {
			$borderPoint = null;
		}

		$travellers = collect($this->dataRepository->getTravellersReportStatistics($start, $end, $borderPoint));

		if ($direction) {
			$travellers = $travellers->filter(function ($value) use ($direction) {
				return $value->MovementDirection == $direction;
			});
		}

		$data = [];

		if ($type === 'gender') {
			// Get gender count
			$data = $this->_getGenderStats($travellers);
		} else if ($type === 'transport_mode') {
			// Get transport mode count
			$data = $this->_getTransportModeStats($travellers);
		} else if ($type === 'age') {
			// Get age group count
			$data = $this->_getAgeGroupStats($travellers);
		} else if ($type === 'destination') {
			// Get count of exit travellers grouped by destination country
			$data = $this->_getTravelDestinationStats($travellers);
		}

		return response()->json($data);
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
