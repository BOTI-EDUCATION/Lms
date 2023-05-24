<?php

namespace Models\LMS;


use \Models\Model;

class EtapeType extends Model
{



	protected static $sqlQueries = [];

	protected static $table = 'lms_etape_types';

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
		'Color' => [
			'type' => 'varchar',
		],
		'Ordre' => [
			'type' => 'text',
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

		return \URL::base() . \Config::get('path-lms-files') . '/etape_types/'  . $this->get('Icon');
	}
}
