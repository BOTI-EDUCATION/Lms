<?php

namespace Models\MGMT\Team;

use Models\Model as Model;

class EvaluationCompetence extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'mgmt_type_competence';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Competence' => array(
			'fk' => 'MGMT\Team\Competences',
		),
		'TypeEvaluation' => array(
			'fk' => 'MGMT\Team\EvaluationType',
		),
		'Ordre' => array(
			'type' => 'int',
		),
	);
}
