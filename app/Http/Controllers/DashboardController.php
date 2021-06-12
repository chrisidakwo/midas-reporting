<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia;

class DashboardController extends Controller {
	/**
	 * @param Request $request
	 * @return Inertia\Response
	 */
	public function index(Request $request): Inertia\Response {
		[$startDate, $endDate] = getDefaultStartEndDates();

		return Inertia::render('Dashboard', compact('startDate', 'endDate'));
	}
}
