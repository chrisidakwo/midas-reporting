<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SeedPassengerReportStats extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'seed:passenger-stats {startDate} {endDate}';

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function handle() {
		$startDate = Carbon::parse($this->argument('startDate'));
		$endDate = Carbon::parse($this->argument('endDate'));

		$this->info('Started with date: ' . $startDate->format('Y-m-d'));

		while ($startDate->lte($endDate)) {
			$this->info('Looping through');
			$start = $startDate->clone()->startOfDay();
			$end = $startDate->clone()->endOfDay();

			$this->info('Retrieving passengers stats');

			$passengerStats = DB::select("EXEC GetPassengersStatistics @culture='en', @FromDate='$start', @ToDate='$end', @BorderPoint=NULL");

			$dataCount = count($passengerStats);

			for ($i = 0; $i < $dataCount; $i++) {
				$this->info('Persisting to DB');

				DB::table('ReportsPassengersStatistics')->updateOrInsert([
					'DocumentNumber' => $passengerStats[$i]->DocumentNumber,
					'TravelDate' => $passengerStats[$i]->TravelDate,
					'TransportNumber' => $passengerStats[$i]->TransportNumber,
					'BorderPointID' => $passengerStats[$i]->BorderPointID,
				], [
					'OfficialName' => $passengerStats[$i]->OfficialName,
					'Nation' => $passengerStats[$i]->Nation,
					'Destination' => $passengerStats[$i]->Destination,
					'Surname' => $passengerStats[$i]->Surname,
					'GivenName' => $passengerStats[$i]->GivenName,
					'DateOfBirth' => Carbon::parse($passengerStats[$i]->DateOfBirth)->format('Y-m-d'),
					'Sex' => $passengerStats[$i]->Sex,
					'TransportType' => $passengerStats[$i]->TransportType,
					'MovementDirectionID' => $passengerStats[$i]->MovementDirectionID,
					'MovementDirection' => $passengerStats[$i]->MovementDirection,
					'BorderPoint' => $passengerStats[$i]->BorderPoint,
					'DocumentTypeID' => $passengerStats[$i]->DocumentTypeID,
					'Inspector' => $passengerStats[$i]->Inspector,
					'Note' => $passengerStats[$i]->Note,
					'AccomodationAddress' => $passengerStats[$i]->AccomodationAddress,
				]);
			}

			$startDate->addDay();

			$this->info('New start date: ' . $startDate->format('Y-m-d'));
		}
	}
}
