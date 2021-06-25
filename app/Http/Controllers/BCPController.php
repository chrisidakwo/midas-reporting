<?php

namespace App\Http\Controllers;

class BCPController extends Controller {
	public function index() {
		return \Inertia::render('BCPRecords');
	}
}
