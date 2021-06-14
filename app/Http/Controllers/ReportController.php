<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia;
use Inertia\Response;

class ReportController extends Controller {
	/**
	 * @param Request $request
	 * @return Response
	 */
	public function index(Request $request): Response {
		return Inertia::render('Report');
	}
}
