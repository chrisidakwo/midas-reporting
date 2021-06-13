<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BorderPointsTableSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$borderPoints = DB::select("EXEC GetBorderPointsForCombo @culture='en'");

		foreach ($borderPoints as $borderPoint) {
			DB::table('BorderPoints')->updateOrInsert([
				'Name' => $borderPoint->Name
			], [
				'ID' => $borderPoint->ID,
				'OwnerID' => $borderPoint->OwnerID,
				'Name' => Str::title($borderPoint->Name)
			]);
		}
	}
}
