<?php

namespace App\Http\Controllers;

use App\Repositories\DataRepository;
use Illuminate\Http\JsonResponse;
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

		$selectedState = $request->get('state');

		$states = array_values($this->dataRepository->getProvincesByCountryId(290));

		return Inertia::render('Dashboard', compact('startDate', 'endDate', 'states', 'selectedState'));
	}

	/**
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function borderPoints(Request $request): JsonResponse {
		$search = $request->get('search');

		$borderPoints = $this->dataRepository->getBorderPoints([
			['Name', 'LIKE', "%{$search}%"]
		])->toArray();

		return response()->json($borderPoints);
	}
}
