<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovementSummaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('MovementSummary', function (Blueprint $table) {
        	$table->smallInteger('BorderPointID')->index();
        	$table->string('BorderPoint');
        	$table->string('RegionalSite');
        	$table->integer('TotalPassengers');
        	$table->integer('TotalEntryPassengers');
        	$table->integer('TotalExitPassengers');
        	$table->integer('TotalForeigners');
        	$table->integer('TotalRegionCitizensWithoutCountry');
        	$table->integer('TotalRegionCitizensWithoutCountryEntry');
        	$table->integer('TotalRegionCitizensWithoutCountryExit');
        	$table->integer('TotalCitizens');
        	$table->integer('TotalCitizensEntry');
        	$table->integer('TotalCitizensExit');
        	$table->integer('TotalFlights');
        	$table->integer('TotalEntryFlights');
        	$table->integer('TotalExitFlights');
        	$table->integer('TotalFlightPassengersEntry');
        	$table->integer('TotalFlightPassengersExit');
        	$table->integer('TotalFlightForeigners');
        	$table->integer('TotalFlightRegionCitizensWithoutCounty');
        	$table->integer('TotalCountryFlight');
        	$table->date('TravelDate')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('MovementSummary');
    }
}
