<?php

namespace App\Http\Controllers;

class InsightController extends Controller {

	public function index() {
		return \Inertia::render('Insights');
	}
}
