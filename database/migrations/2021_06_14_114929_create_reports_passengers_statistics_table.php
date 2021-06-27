<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsPassengersStatisticsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('ReportsPassengersStatistics', function (Blueprint $table) {
			$table->string('OfficialName');
			$table->string('Nation');
			$table->string('Destination')->nullable();
			$table->string('DocumentNumber');
			$table->string('Surname');
			$table->string('GivenName');
			$table->string('DateOfBirth');
			$table->enum('Sex', ['M', 'F']);
			$table->timestamp('TravelDate');
			$table->string('TransportNumber');
			$table->string('TransportType');
			$table->tinyInteger('MovementDirectionID');
			$table->string('MovementDirection');
			$table->integer('BorderPointID');
			$table->string('BorderPoint');
			$table->integer('ProvinceID');
			$table->string('ProvinceName');
			$table->integer('DocumentTypeID');
			$table->string('Inspector');
			$table->longText('Note');
			$table->longText('AccomodationAddress');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('reports_passengers_statistics');
	}
}
