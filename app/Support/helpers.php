<?php

use Carbon\Carbon;

function getDefaultStartEndDates(): array {
	$request = request();

	// Set start date
	if ($startDate = $request->get('start_date')) {
		$startDate = Carbon::parse($startDate);
	} else {
		$startDate = today()->subDays(29);
	}

	// Set end date
	if ($endDate = $request->get('end_date')) {
		$endDate = Carbon::parse($endDate);
	} else {
		$endDate = today();
	}

	if ($endDate->gt(today())) {
		$endDate = today();
	}

	if ($startDate->gt($endDate)) {
		$startDate = $endDate->clone()->subDays(29);
	}

	return [$startDate, $endDate];
}
