<?php

namespace App\Enums;

class TransportTypes {
	public const TYPES = ['Flight', 'Bus', 'Car', 'Ship', 'Truck', 'Pedestrian', 'Motorcycle', 'Lorry'];

	public const GROUPS = ['Air', 'Land', 'Sea'];

	public const TYPE_GROUP = [
		'Bus' => 'Land',
		'Car' => 'Land',
		'Flight' => 'Air',
		'Ship' => 'Sea',
		'Truck' => 'Land',
		'Pedestrian' => 'Land',
		'Motorcycle' => 'Land',
		'Lorry' => 'Land'
	];

	public const GROUP_TYPES = [
		'Air' => ['Flight'],
		'Land' => ['Car', 'Bus', 'Truck', 'Pedestrian', 'Motorcycle', 'Lorry'],
		'Sea' => ['Ship', 'Ferry', 'Boat']
	];
}
