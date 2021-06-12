<?php

namespace App\Http\Controllers;

use App\Enums\TransportTypes;
use App\Repositories\DataRepository;
use Illuminate\Http\Request;
use Inertia;

class DashboardController extends Controller {
	/**
	 * @var DataRepository
	 */
	private DataRepository $dataRepository;

	public function __construct(DataRepository $dataRepository) {
		$this->dataRepository = $dataRepository;
	}

	/**
	 * @param Request $request
	 * @return Inertia\Response
	 */
	public function index(Request $request): Inertia\Response {
		[$startDate, $endDate] = getDefaultStartEndDates();

//		$travellers = $this->dataRepository->getTravellersReportStatistics($startDate, $endDate);

//		$data = collect($travellers)->groupBy('TransportType')->map(static function ($value) {
//			return count($value);
//		})->reduce(function (&$carry, $value, $key) {
//			// Get the group for the said type
//			$group = TransportTypes::TYPE_GROUP[$key];
//
//			if (array_key_exists($group, $carry)) {
//				$carry[$group] += $value;
//			} else {
//				$carry[$group] = $value;
//			}
//
//			return $carry;
//		}, []);

//		dd($travellers);

		return Inertia::render('Dashboard', compact('startDate', 'endDate'));
	}
}
