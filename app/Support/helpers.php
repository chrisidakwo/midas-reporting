<?php

use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * @param Request|null $request
 * @return Carbon[]
 */
function getDefaultStartEndDates(Request $request = null): array {
	$request = $request ?? request();

	// Set start date
	if ($startDate = $request->get('start_date')) {
		$startDate = Carbon::parse($startDate);
	} else {
//		$startDate = today()->subDays(6);
		$startDate = Carbon::create(2019, 11);
	}

	// Set end date
	if ($endDate = $request->get('end_date')) {
		$endDate = Carbon::parse($endDate);
	} else if (!$endDate || $endDate->gt(today())) {
//		$endDate = today();
		$endDate = Carbon::create(2019, 11, 3);
	}

	if ($startDate->gt($endDate)) {
		$startDate = $endDate->clone()->subDays(6);
	}

	return [$startDate->startOfDay(), $endDate->endOfDay()];
}


/**
 * @param Carbon $startDate
 * @param Carbon $endDate
 * @return \Illuminate\Support\Carbon
 */
function setCacheTTL(Carbon $startDate, Carbon $endDate): \Illuminate\Support\Carbon {
	if ($startDate->isToday() || $endDate->isToday()) {
		return now()->addMinutes(5);
	}

	return now()->addMonths(3);
}
