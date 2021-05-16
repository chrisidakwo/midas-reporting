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
		$startDate = today()->subDays(29);
	}

	// Set end date
	if ($endDate = $request->get('end_date')) {
		$endDate = Carbon::parse($endDate);
	} else if (!$endDate || $endDate->gt(today())) {
		$endDate = today();
	}

	if ($startDate->gt($endDate)) {
		$startDate = $endDate->clone()->subDays(29);
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
