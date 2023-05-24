<?php

namespace Models;

class CompetenceEvualionNotes extends Model
{
	protected static $sqlQueries = array();

	protected static $table = 'cptc_evaluations_notes';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Evaluation' => array(
			'fk' => 'CompetenceEvaluation',
		),
		'Competence' => array(
			'fk' => 'Competences',
		),
		'NiveauMaitrise' => array(
			'fk' => 'CPTCNiveauxMaitrise',
		),
		'Eleve' => array(
			'fk' => 'Eleve',
		),
		'Inscription' => array(
			'fk' => 'Inscription',
		),
		
		'Enseignant' => array(
			'fk' => 'User',
		),
		'Points' => array(
			'type' => 'int',
		),
		'Remarques' => array(
			'type' => 'varchar',
		),
		'Validated' => array(
			'type' => 'text',
		),
		'PublishedToParents' => array(
			'type' => 'text',
		),
		'Date' => array(
			'type' => 'date',
		)
	);

	public static function hasNote($evaluation, $competence, $inscription)
	{
		$notes = self::getList(array('where' => array('Evaluation' => $evaluation, 'Competence' => $competence, 'Inscription' => $inscription)));

		if (count($notes)) return $notes[0];

		return null;
	}

	static function getCode($inscription, $competence, $evaluation)
	{
		$note = CompetenceEvualionNotes::where(array( 'Inscription' => $inscription, 'Evaluation' => $evaluation, 'Competence' => $competence ))->first();
		
		return $note ? ['code' => $note->get('NiveauMaitrise')->get('Code'), 
						'color' => $note->get('NiveauMaitrise')->get('Color'),
						'niveau' => $note->get('NiveauMaitrise')->get('Label'),
						] : ['code' => '----', 'color' => '', 'niveau' => '----']  ;
	}

}
