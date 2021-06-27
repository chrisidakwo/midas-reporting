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
		})->reduce(function ($carry, $value, $key) {
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
		return $travellers->where('MovementDirection', 'Departure')->where('Destination', '!=', 'Nigeria')
			->groupBy('Destination')
			->reduce(function ($carry, $records, $destination) {
				$destination = $this->_getGoogleChartCountryName($destination);


				$carry[] = [$destination, count($records)];

				return $carry;
			}, [['Country', 'Travellers']]);
	}

	/**
	 * Get nationalities statistics.
	 *
	 * @param Collection $travellers
	 * @return mixed
	 */
	private function _getNationalitiesStats(Collection $travellers): array {
		return $travellers->where('MovementDirection', 'Arrival')->groupBy('OfficialName')
			->reduce(function ($carry, $records, $nation) {
				$nation = $this->_getGoogleChartCountryName($nation);

				$carry[] = [$nation, count($records)];

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
		if ($borderPoint == null || $borderPoint == 'null') {
			$borderPoint = [];
		} else {
			$borderPoint = explode(',', $borderPoint);
		}

		$travellers = $this->dataRepository->getTravellersReportStatistics($start, $end, array_filter($borderPoint));

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
		} else if ($type === 'nationalities') {
			$data = $this->_getNationalitiesStats($travellers);
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

		$traffic = $travellers->groupBy(['ProvinceName'])->toArray();

		$statesArr = [];
		$series = [];

		foreach ($traffic as $state => $trafficStat) {
			$state = $state ?: 'Others';

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

	/**
	 * Get the google chart name for the provided country.
	 *
	 * @param string $country
	 * @return string
	 */
	private function _getGoogleChartCountryName(string $country): string {
		if ($country == 'United States of America') {
			$country = 'United States';
		} else if ($country === 'Russian Federation') {
			$country = 'Russia';
		} else if ($country === 'Congo, The Democratic Republic') {
			$country = 'Congo - Kinshasa';
		} else if ($country === 'Congo') {
			$country = 'Congo - Brazzaville';
		} else if ($country === 'DEMOCRATIC PEOPLES REPUBLIC OF') {
			$country = 'North Korea';
		} else if ($country === 'Côte d\'Ivoire') {
			$country = 'Ivory Coast';
		} else if ($country === 'Hong Kong Special Administrative Region of China') {
			$country = 'Hong Kong';
		} else if ($country === 'Iran (Islamic Republic of)') {
			$country = 'Iran';
		} else if ($country === 'Lao People\'s Democratic Republic') {
			$country = 'Laos';
		} else if ($country === 'Republic of Korea') {
			$country = 'South Korea';
		} else if ($country === 'Tanzania, United Republic of') {
			$country = 'Tanzania';
		} else if ($country === 'Viet Nam') {
			$country = 'Vietnam';
		}

		return $country;
	}
}
