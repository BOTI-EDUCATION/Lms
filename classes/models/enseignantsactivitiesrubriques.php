<?php

namespace Models;

use Exception;

class Enseignantsactivitiesrubriques extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'fnc_enseignants_activities_rubriques';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Enseignant' => array(
			'fk' => 'Enseignant',
		),
		'Rubrique' => array(
			'fk' => 'FIN\EncaissementRubrique',
		),

	);
}
