<?php

namespace App\Http\Controllers;

use App\Enums\ReportTypes;
use App\Enums\TransportTypes;
use App\Repositories\DataRepository;
use Arr;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia;
use Inertia\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportController extends Controller {
	public const ALPHABETS = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T'];

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
		$reportType = Arr::get($filters, 'report_type');

		if (!empty($filters) && $reportType) {
			$borderPoint = $filters['border'] ? [$filters['border']] : [];

			if ($reportType == '1') {
				// Get travellers statistics
				$columns = ['OfficialName', 'DocumentNumber', 'Surname', 'GivenName', 'DateOfBirth', 'Sex', 'TravelDate', 'TransportType', 'MovementDirection', 'BorderPoint'];

				// Get series for chart
				$series = $this->dataRepository->getTravellersReportStatistics($startDate, $endDate, null, $borderPoint, $columns, true);

				// Get statistics count
				$result = $this->dataRepository->getTravellersReportStatistics($startDate, $endDate, null, $borderPoint);
				$stats = $this->_getGenericTravelStatistics($result);

			} else if ($reportType == '2') {
				$series = $this->_buildStatisticsByNationalitySeries($startDate, $endDate, $borderPoint);

				// Get statistical count
				$result = $this->dataRepository->getTravellersReportStatistics($startDate, $endDate, null, $borderPoint);
				$stats = $this->_getGenericTravelStatistics($result);
			} else if ($reportType == '3') {
				$series = $this->_buildDailyMovementStatisticsSeries($startDate, $endDate, $borderPoint);

				// Get statistical count
				$result = $this->dataRepository->getTravellersReportStatistics($startDate, $endDate, null, $borderPoint);
				$stats = $this->_getGenericTravelStatistics($result);
			}
		}

		$paginate = '';
		if (is_a($series, Paginator::class)) {
			$dataSeries = $series->items();
			$paginate = (string) $series->appends(request()->all())->links();
		} else {
			$dataSeries = $series;
		}

		return Inertia::render('Report', [
			'borderPoints' => $borderPoints,
			'startDate' => $startDate->format('Y-m-d'),
			'endDate' => $endDate->format('Y-m-d'),
			'reportType' => $reportType,
			'series' => $dataSeries,
			'stats' => $stats,
			'paginate' => $paginate
		]);
	}

	/**
	 * @param Request $request
	 * @return void
	 * @throws Exception
	 */
	public function download(Request $request) {
		// If type is not handled, just return
		$type = $request->get('type');

		if (empty($type) || !in_array($type, ['statistics', 'data'])) {
			return;
		}

		[$startDate, $endDate] = getDefaultStartEndDates($request);
		$filters = $request->all();
		$reportType = Arr::get($filters, 'report_type');

		$spreadsheet = new Spreadsheet();
		$activeSheet = $spreadsheet->getActiveSheet();

		// Create header and data row

		if (!empty($filters) && $reportType) {
			$borderPoint = $filters['border'] ? [$filters['border']] : [];

			if ($reportType == '1') {
				// Get statistics count
				if ($type == 'statistics') {
					$result = $this->dataRepository->getTravellersReportStatistics($startDate, $endDate, null, $borderPoint);
					$stats = $this->_getGenericTravelStatistics($result);

					// Build spreadsheet object
					$alphabets = self::ALPHABETS;
					$this->_buildSpreadsheetForGenericTravelStat($activeSheet, $startDate, $endDate, $alphabets, $stats);
				}

				if ($type == 'data') {
					$columns = ['OfficialName', 'DocumentNumber', 'Surname', 'GivenName', 'DateOfBirth', 'Sex', 'TravelDate', 'TransportType', 'MovementDirection', 'BorderPoint', 'ProvinceName'];
					$series = $this->dataRepository->getTravellersReportStatistics($startDate, $endDate, null, $borderPoint, $columns);

					$header = ['Travel Date', 'Nationality', 'Doc No', 'Surname', 'Given Name', 'DOB', 'Sex', 'Transport Mode', 'Direction', 'BorderPoint', 'State'];

					// Set header row
					$headerLen = count($header);
					$alphabets = self::ALPHABETS;

					for ($i = 0; $i < $headerLen; $i++) {
						$activeSheet->setCellValue("$alphabets[$i]1", $header[$i]);
					}

					// Append data
					$dataLen = $series->count();
					for ($i = 0; $i < $dataLen; $i++) {
						$rowNumber = $i + 2;
						$activeSheet->setCellValue("A$rowNumber", Carbon::parse($series[$i]->TravelDate)->format('d-m-Y'))
							->setCellValue("B$rowNumber", $series[$i]->OfficialName)
							->setCellValue("C$rowNumber", $series[$i]->DocumentNumber)
							->setCellValue("D$rowNumber", $series[$i]->Surname)
							->setCellValue("E$rowNumber", $series[$i]->GivenName)
							->setCellValue("F$rowNumber", $series[$i]->DateOfBirth)
							->setCellValue("G$rowNumber", $series[$i]->Sex)
							->setCellValue("H$rowNumber", $series[$i]->TransportType)
							->setCellValue("I$rowNumber", $series[$i]->MovementDirection)
							->setCellValue("J$rowNumber", $series[$i]->BorderPoint)
							->setCellValue("K$rowNumber", $series[$i]->ProvinceName);
					}

					// Auto Set the Size of Columns
					foreach (range('A', 'K') as $columnID) {
						$activeSheet->getColumnDimension($columnID)->setAutoSize(true);
					}
				}
			} else if ($reportType == '2') {
				$series = $this->_buildStatisticsByNationalitySeries($startDate, $endDate, $borderPoint);

				$header = ['Country', 'Total Travellers', 'Total Entry', 'Total Exit'];

				// Set header row
				$headerLen = count($header);
				$alphabets = self::ALPHABETS;

				for ($i = 0; $i < $headerLen; $i++) {
					$activeSheet->setCellValue("$alphabets[$i]1", $header[$i]);
				}

				// Append data
				$dataLen = count($series);
				for ($i = 0; $i < $dataLen; $i++) {
					$rowNumber = $i + 2;
					$activeSheet->setCellValue("A$rowNumber", $series[$i][0])
						->setCellValue("B$rowNumber", $series[$i][1] + $series[$i][2])
						->setCellValue("C$rowNumber", $series[$i][1])
						->setCellValue("D$rowNumber", $series[$i][2]);
				}

				// Format number column
				$activeSheet->getStyle('B2:D' . ($dataLen + 1))->getNumberFormat()
					->setFormatCode('#,##0');

				// Auto Set the Size of Columns
				foreach (range('A', 'D') as $columnID) {
					$activeSheet->getColumnDimension($columnID)->setAutoSize(true);
				}
			} else if ($reportType == '3') {
				$series = $this->_buildDailyMovementStatisticsSeries($startDate, $endDate, $borderPoint);

				$header = array_slice($series, 0, 1)[0];

				// Set header row
				$headerLen = count($header);
				$alphabets = self::ALPHABETS;

				for ($i = 0; $i < $headerLen; $i++) {
					$activeSheet->setCellValue("$alphabets[$i]1", $header[$i]);
				}

				// Append data
				$dataLen = count($series);
				for ($i = 1; $i < $dataLen; $i++) { // Start $i at 1 so we can omit the header
					$rowNumber = $i + 1;

					$activeSheet->setCellValue("A$rowNumber", $series[$i][0])
						->setCellValue("B$rowNumber", $series[$i][1])
						->setCellValue("C$rowNumber", $series[$i][2])
						->setCellValue("D$rowNumber", $series[$i][3])
						->setCellValue("E$rowNumber", $series[$i][4]);
				}

				// Format number column
				$activeSheet->getStyle("B2:E$dataLen")->getNumberFormat()
					->setFormatCode('#,##0');

				// Auto Set the Size of Columns
				foreach (range('A', 'E') as $columnID) {
					$activeSheet->getColumnDimension($columnID)->setAutoSize(true);
				}
			}

			$reportTypeName = Str::slug(ReportTypes::VALUES[$reportType]);
			header('Content-Type: application/vnd.ms-excel');
			header("Content-Disposition: attachment;filename=$reportTypeName-$type-report.xlsx");
			header('Cache-Control: max-age=0');

			$writer = new Xlsx($spreadsheet);
			$writer->save('php://output');
		}
	}

	/**
	 * @param Worksheet $activeSheet
	 * @param Carbon $startDate
	 * @param Carbon $endDate
	 * @param array $alphabets
	 * @param array $statistics
	 */
	private function _buildSpreadsheetForGenericTravelStat(Worksheet &$activeSheet, Carbon $startDate, Carbon $endDate, array $alphabets, array $statistics) {
		$header = ['Total Travellers', 'Total Entry', 'Total Exit', 'Total Male Travellers',
			'Total Female Travellers', 'Total Air Travellers', 'Total Land Travellers', 'Total Sea Travellers'];

		$headerLen = count($header);

		// Set travel date header and data row
		$activeSheet = $activeSheet->setCellValue('A1', 'Travel Date');
		$activeSheet = $activeSheet->setCellValue('A2', sprintf('%s to %s', $startDate->format('Y-m-d'), $endDate->format('Y-m-d')));

		for ($i = 0; $i < $headerLen; $i++) {
			$headerAlphabet = $alphabets[$i +1];

			$activeSheet = $activeSheet->setCellValue("{$headerAlphabet}1", $header[$i]);
			$activeSheet = $activeSheet->setCellValue("{$headerAlphabet}2", $statistics[Str::slug($header[$i], '_')]);
		}

		// Format number column
		$activeSheet->getStyle('A2:T2')->getNumberFormat()
			->setFormatCode('#,##0');

		// Auto Set the Size of Columns
		foreach (range('A', 'I') as $columnID) {
			$activeSheet->getColumnDimension($columnID)->setAutoSize(true);
		}
	}

	/**
	 * @param Collection $data
	 * @return array
	 */
	private function _getGenericTravelStatistics(Collection $data): array {
		$totalTravellers = $data->count();
		$totalEntry = $data->where('MovementDirection', 'Arrival')->count();
		$totalExit = $data->where('MovementDirection', 'Departure')->count();
		$totalMaleTravellers = $data->where('Sex', 'M')->count();
		$totalFemaleTravellers = $data->where('Sex', 'F')->count();
		$totalAirTravellers = $data->whereIn('TransportType', TransportTypes::GROUP_TYPES['Air'])->count();
		$totalLandTravellers = $data->whereIn('TransportType', TransportTypes::GROUP_TYPES['Land'])->count();
		$totalSeaTravellers = $data->whereIn('TransportType', TransportTypes::GROUP_TYPES['Sea'])->count();

		return [
			'total_travellers' => $totalTravellers,
			'total_entry' => $totalEntry,
			'total_exit' => $totalExit,
			'total_male_travellers' => $totalMaleTravellers,
			'total_female_travellers' => $totalFemaleTravellers,
			'total_air_travellers' => $totalAirTravellers,
			'total_land_travellers' => $totalLandTravellers,
			'total_sea_travellers' => $totalSeaTravellers
		];
	}

	/**
	 * @param Carbon $startDate
	 * @param Carbon $endDate
	 * @param array $borderPoint
	 * @return array
	 */
	private function _buildStatisticsByNationalitySeries(Carbon $startDate, Carbon $endDate, array $borderPoint): array {
		$columns = ['OfficialName', 'Sex', 'TransportType', 'MovementDirection'];

		$series = $this->dataRepository->getTravellersReportStatistics($startDate, $endDate, null, $borderPoint, $columns)
			->reduce(function (&$carry, $item) {

				if (array_key_exists($item->OfficialName, $carry)) {
					if (array_key_exists($item->MovementDirection, $carry[$item->OfficialName])) {
						$carry[$item->OfficialName][$item->MovementDirection] += 1;
					} else {
						$carry[$item->OfficialName][$item->MovementDirection] = 1;
					}
				} else {
					// First set both arrival and departure key
					$carry[$item->OfficialName]['Arrival'] = 0;
					$carry[$item->OfficialName]['Departure'] = 0;

					// Then update the one available in the current item
					$carry[$item->OfficialName][$item->MovementDirection] = 1;
				}

				return $carry;
			}, []);

		return collect($series)->reduce(function ($carry, $item, $key) {
			$carry[] = [$key, $item['Arrival'], $item['Departure']];

			return $carry;
		}, []);
	}

	/**
	 * @param Carbon $startDate
	 * @param Carbon $endDate
	 * @param array $borderPoint
	 * @return array
	 */
	private function _buildDailyMovementStatisticsSeries(Carbon $startDate, Carbon $endDate, array $borderPoint): array {
		// @see: https://tableplus.com/blog/2018/09/ms-sql-server-how-to-get-date-only-from-datetime-value.html
		$columns = [DB::raw('CONVERT(VARCHAR(10), TravelDate, 105) as TravelDate'), 'OfficialName', 'MovementDirection'];

		$series = $this->dataRepository->getTravellersReportStatistics($startDate, $endDate, null, $borderPoint, $columns)
			->sortByDate('TravelDate')->groupBy(['TravelDate', 'MovementDirection'])->reduce(function ($carry, $item, $date) {
				$arrivalCount = $item['Arrival']->count();
				$departureCount = $item['Departure']->count();

				$residentsCount = $item['Arrival']->where('OfficialName', 'Nigeria')->count() + $item['Departure']->where('OfficialName', 'Nigeria')->count();
				$nonResidentsCount = $item['Arrival']->where('OfficialName', '!=', 'Nigeria')->count() + $item['Departure']->where('OfficialName', '!=', 'Nigeria')->count();

				$carry[] = [$date, $arrivalCount, $departureCount, $residentsCount, $nonResidentsCount];

				return $carry;
			}, []);

		array_unshift($series, ['Travel Date', 'Total Entry', 'Total Exit', 'Residents', 'Non-Residents']);

		return $series;
	}
}
