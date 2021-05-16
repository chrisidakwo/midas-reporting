<?php

namespace App\Console\Commands;

use Illuminate\Database\Console\Migrations\FreshCommand;

class MigrateFreshCommand extends FreshCommand {

	public function handle() {
		$this->info('Cannot wipe database. Don\'t even try it!');

		return null;
	}
}
