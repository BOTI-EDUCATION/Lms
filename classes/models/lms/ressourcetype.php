<?php

namespace Models\LMS;


use \Models\Model;

class RessourceType extends Model
{



	protected static $sqlQueries = [];

	protected static $table = 'lms_ressource_types';

	protected static $pk = [
		'ID' => [
			'auto' => true,
		],
	];

	protected static $fields = [
		'Label' => [
			'type' => 'varchar',
		],
		'Alias' => [
			'type' => 'varchar',
		],
		'Icon' => [
			'type' => 'varchar',
		],
		'Ordre' => [
			'type' => 'text',
		],
		'Enabled' => [
			'type' => 'bigint',
		],
		'Date' => [
			'type' => 'date',
		]
	];


	public function getImage()
	{
		if (!$this->Icon) {
			return 'https://via.placeholder.com/300/004c68/FFF?text=Image';
		}

		return \URL::base() . \Config::get('path-lms-files') . '/ressource_types/'  . $this->get('Icon');
	}
}
