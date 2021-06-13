<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBorderPointsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('BorderPoints', function (Blueprint $table) {
			$table->integer('ID')->primary();
			$table->integer('OwnerID')->index();
			$table->string('Name')->index();
			$table->string('State')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('border_points');
	}
}
