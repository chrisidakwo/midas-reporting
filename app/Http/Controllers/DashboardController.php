<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller {
	/**
	 * @param Request $request
	 * @return Application|Factory|View
	 */
	public function index(Request $request) {
		return view('home');
	}
}
