<?php

namespace Models;

use Exception;

class Enseignantsactivities extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'enseignants_activities';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Enseignant' => array(
			'fk' => 'Enseignant',
		),
		'Groupe' => array(
			'fk' => 'Groupes',
		),

	);
}
