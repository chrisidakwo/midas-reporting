<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SeedMovementSummaryTable extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'seed:movement-summary {startDate} {endDate}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Get movement summary for each day within the given date range and persist to new table';

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function handle() {
		$startDate = Carbon::parse($this->argument('startDate'));
		$endDate = Carbon::parse($this->argument('endDate'));

		$this->info('Started with date: ' . $startDate->format('Y-m-d'));

		$loopCount = 1;
		while ($startDate->lte($endDate)) {
			$this->info('Looping through');

			$start = $startDate->clone()->startOfDay();
			$end = $startDate->clone()->endOfDay();

			$this->info('Retrieving movement stats');

			$movementStatistics = DB::select("EXEC GetMovementStatistics @PeriodStart='$start', @PeriodEnd='$end', @RegionID=NULL, @borderPoint=NULL");

			$dataLen = count($movementStatistics);

			for ($i = 0; $i < $dataLen; $i++) {
				$this->info('Inserting to DB ' . $loopCount);

				DB::table('MovementSummary')->updateOrInsert([
					'BorderPointID' => $movementStatistics[$i]->BorderPointID,
					'TravelDate' => $startDate->format('Y-m-d')
				], [
					'BorderPoint' => $movementStatistics[$i]->BorderPoint,
					'RegionalSite' => $movementStatistics[$i]->RegionalSite,
					'TotalPassengers' => $movementStatistics[$i]->TotalPassangers,
					'TotalEntryPassengers' => $movementStatistics[$i]->TotalEntryPassangers,
					'TotalExitPassengers' => $movementStatistics[$i]->TotalExitPassangers,
					'TotalForeigners' => $movementStatistics[$i]->TotalForeigners,
					'TotalRegionCitizensWithoutCountry' => $movementStatistics[$i]->TotalRegionCitizensWithoutCounty,
					'TotalRegionCitizensWithoutCountryEntry' => $movementStatistics[$i]->TotalRegionCitizensWithoutCountyEntry,
					'TotalRegionCitizensWithoutCountryExit' => $movementStatistics[$i]->TotalRegionCitizensWithoutCountyExit,
					'TotalCitizens' => $movementStatistics[$i]->TotalCitizens,
					'TotalCitizensEntry' => $movementStatistics[$i]->TotalCitizensEntry,
					'TotalCitizensExit' => $movementStatistics[$i]->TotalCitizensExit,
					'TotalFlights' => $movementStatistics[$i]->TotalFlights,
					'TotalEntryFlights' => $movementStatistics[$i]->TotalEntryFlights,
					'TotalExitFlights' => $movementStatistics[$i]->TotalExitFlights,
					'TotalFlightPassengersEntry' => $movementStatistics[$i]->TotalFlightPassengersEntry,
					'TotalFlightPassengersExit' => $movementStatistics[$i]->TotalFlightPassengersExit,
					'TotalFlightForeigners' => $movementStatistics[$i]->TotalFlightForeigners,
					'TotalFlightRegionCitizensWithoutCounty' => $movementStatistics[$i]->TotalFlightRegionCitizensWithoutCounty,
					'TotalCountryFlight' => $movementStatistics[$i]->TotalCountryFlight
				]);

				$loopCount++;
			}

			$startDate->addDay();

			$this->info('New start date: ' . $startDate->format('Y-m-d'));
		}
	}
}
