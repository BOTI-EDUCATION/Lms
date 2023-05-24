<?php

namespace Models\LMS;


use \Models\Model;

class Ressource extends Model
{



	protected static $sqlQueries = [];

	protected static $table = 'lms_ressources';

	protected static $pk = [
		'ID' => [
			'auto' => true,
		],
	];

	protected static $fields = [
		'Lecon' => [
			'fk' => 'LMS\Lecons',
		],
		'Type' => [
			'fk' => 'LMS\EtapeType',
		],
		'Duree' => [
			'type' => 'int',
		],
		'Label' => [
			'type' => 'varchar',
		],
		'Introduction' => [
			'type' => 'text',
		],
		'Ordre' => [
			'type' => 'int',
		],
		'Date' => [
			'type' => 'datetime',
		]
	];
}
