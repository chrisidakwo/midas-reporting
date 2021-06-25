<?php

namespace App\Http\Controllers;

class VisaController extends Controller {
	public function index() {
		return \Inertia::render('Visas');
	}
}
