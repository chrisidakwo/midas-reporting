<?php

namespace App\Http\Controllers;

use App\Enums\TransportTypes;
use App\Repositories\DataRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia;
use Inertia\Response;

class ReportController extends Controller {
	private DataRepository $dataRepository;

	/**
	 * ReportController constructor.
	 *
	 * @param DataRepository $dataRepository
	 */
	public function __construct(DataRepository $dataRepository) {
		$this->dataRepository = $dataRepository;
	}

	/**
	 * @param Request $request
	 * @return Response
	 */
	public function index(Request $request): Response {
		$borderPoints = array_values($this->dataRepository->getBorderPoints()->sortBy('Name')->toArray());

		[$startDate, $endDate] = getDefaultStartEndDates($request);

		$series = [];
		$stats = [];

		$filters = $request->all();
		$reportType = \Arr::get($filters, 'report_type');

		if (!empty($filters) && $reportType) {
			$borderPoint = $filters['border'] ? [$filters['border']]: [];

			if ($reportType == '1') {
				// Get travellers statistics
				$columns = ['OfficialName', 'DocumentNumber', 'Surname', 'GivenName', 'DateOfBirth', 'Sex', 'TravelDate', 'TransportType', 'MovementDirection', 'BorderPoint'];

//				dd($startDate);

				// Get series for chart
				$series = $this->dataRepository->getTravellersReportStatistics($startDate, $endDate, null, $borderPoint, $columns, true);

				// Get statistics count
				$result = $this->dataRepository->getTravellersReportStatistics($startDate, $endDate, null, $borderPoint);

				$totalTravellers = $result->count();
				$totalEntry = $result->where('MovementDirection', 'Arrival')->count();
				$totalExit = $result->where('MovementDirection', 'Departure')->count();
				$totalMaleTravellers = $result->where('Sex', 'M')->count();
				$totalFemaleTravellers = $result->where('Sex', 'F')->count();
				$totalAirTravellers = $result->whereIn('TransportType', TransportTypes::GROUP_TYPES['Air'])->count();
				$totalLandTravellers = $result->whereIn('TransportType', TransportTypes::GROUP_TYPES['Land'])->count();
				$totalSeaTravellers = $result->whereIn('TransportType', TransportTypes::GROUP_TYPES['Sea'])->count();

				$stats = [
					'total_travellers' => $totalTravellers,
					'total_entry' => $totalEntry,
					'total_exit' => $totalExit,
					'total_male_travellers' => $totalMaleTravellers,
					'total_female_travellers' => $totalFemaleTravellers,
					'total_air_travellers' => $totalAirTravellers,
					'total_land_travellers' => $totalLandTravellers,
					'total_sea_travellers' => $totalSeaTravellers
				];
			} else if ($reportType == '2') {
				// Get statistics by nationality
			} else if ($reportType == '3') {
				// Get movement statistics
			} else if ($reportType == '4') {
				// Get daily movement statistics
			}
		}

		return Inertia::render('Report', [
			'borderPoints' => $borderPoints,
			'startDate' => $startDate->format('Y-m-d'),
			'endDate' => $endDate->format('Y-m-d'),
			'reportType' => $reportType,
			'series' => $series->items(),
			'stats' => $stats,
			'paginate' => (string) $series->appends(request()->all())->links()
		]);
	}
}
