<?php

namespace Models\LMS;


use \Models\Model;

class Tracking extends Model
{



	protected static $sqlQueries = [];

	protected static $table = '	lms_teacher_tracking';

	protected static $pk = [
		'ID' => [
			'auto' => true,
		],
	];

	protected static $fields = [
		'Classe' => [
			'fk' => 'Classe',
		],
		'Date' => [
			'type' => 'datetime',
		],
		'Lecon' => [
			'fk' => 'LMS\Lecons',
		],
		'Ressource' => [
			'fk' => 'LMS\Ressource',
		],
		'Step' => [
			'fk' => 'LMS\RessourceContent',
		],
		'Teacher' => [
			// 'fk' => 'Enseignant',
			'fk' => 'User',
		],
		'Percent' => [
			'type' => 'int',
		],

	];
}
