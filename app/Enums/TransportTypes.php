<?php

namespace App\Enums;

class TransportTypes {
	public const TYPES = ['Flight', 'Bus', 'Car', 'Ship', 'Truck'];

	public const GROUPS = ['Air', 'Land', 'Sea'];

	public const TYPE_GROUP = [
		'Bus' => 'Land',
		'Car' => 'Land',
		'Flight' => 'Air',
		'Ship' => 'Sea',
		'Truck' => 'Land'
	];
}
