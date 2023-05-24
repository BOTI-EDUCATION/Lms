<?php

namespace Models\MGMT\Team;

use Models\Model as Model;

class Evaluation extends Model
{
	protected static $sqlQueries = array();

	protected static $table = 'mgmt_team_competences_evaluations';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Label' => array(
			'type' => 'varchar',
		),
		'Evaluator' => array(
			'type' => 'varchar',
		),
		'DateEvaluation' => array(
			'type' => 'date',
		),
		'Remarque' => array(
			'type' => 'varchar',
		),
		'Seance' => array(
			'type' => 'varchar',
		),
		'Format' => array(
			'type' => 'varchar',
		),
		'EvaluationType' => array(
			'fk' => 'MGMT\Team\EvaluationType',
		),
		'ComptencesToEvaluate' => array(
			'type' => 'text',
		),
		'UsersToEvaluate' => array(
			'type' => 'text',
		),
		'UsersInfo' => array(
			'type' => 'text',
		),
		'Coefficient' => array(
			'type' => 'int',
		),
		'CreationMeta' => array(
			'type' => 'text',
		),
		'EditHistory' => array(
			'type' => 'text',
		),
		'BlockChangeNotes' => array(
			'type' => 'text',
		),
	);
}
