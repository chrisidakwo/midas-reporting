<?php

return [
	/*
     * These scripts will be loaded when the dashboard is displayed.
     */
	'scripts' => [
		'alpinejs' => '/js/alpine.min.js',
	],

	/*
	 * These stylesheets will be loaded when the dashboard is displayed.
	 */
	'stylesheets' => [
		'inter' => '/fonts/Inter/inter.css'
	],

	'charts' => [
		'refresh_interval_in_seconds' => 300,

		'route_name_prefix' => 'charts',

		'scripts' => [
			'chart' => '/js/chart.min.js',
			'chartisan' => '/js/chartisan_chartjs.umd.min.js'
		]
	]
];
