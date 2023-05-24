<?php

namespace Models\MGMT\Team;

use Models\Model as Model;

class EvaluationNotes extends Model
{
	protected static $sqlQueries = array();

	protected static $table = 'mgmt_evaluations_notes';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);

	protected static $fields = array(
		'Evaluation' => array(
			'fk' => 'MGMT\Team\Evaluation',
		),
		'Competence' => array(
			'fk' => 'MGMT\Team\Competences',
		),
		'NiveauMaitrise' => array(
			'fk' => 'MGMT\Team\NiveauxMaitrise',
		),
		'User' => array(
			'fk' => 'User',
		),
		'UserBY' => array(
			'fk' => 'User',
		),
		'Points' => array(
			'type' => 'int',
		),
		'Remarques' => array(
			'type' => 'varchar',
		),
		'Actions' => array(
			'type' => 'text',
		),
		'Validated' => array(
			'type' => 'text',
		),
		'DateTime' => array(
			'type' => 'datetime',
		)
	);

	public static function hasNote($evaluation, $competence, $user)
	{
		$notes = self::getList(array('where' => array('Evaluation' => $evaluation, 'Competence' => $competence, 'User' => $user)));

		if (count($notes)) return $notes[0];

		return null;
	}
}
