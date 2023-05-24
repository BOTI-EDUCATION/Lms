<?php

namespace Models;

class CompetenceEvaluation extends Model
{
	protected static $sqlQueries = array();

	protected static $table = 'cptc_evaluations';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Label' => array(
			'type' => 'varchar',
		),
		'DateEvaluation' => array(
			'type' => 'date',
		),
		'Remarque' => array(
			'type' => 'varchar',
		),
		'Format' => array(
			'type' => 'int',
		),
		'ComptencesVisees' => array(
			'type' => 'text',
		),
		'Classes' => array(
			'type' => 'text',
		),
		'Coefficient' => array(
			'type' => 'int',
		),
		'Semestre' => array(
			'type' => 'int',
		),
		'CreationMeta' => array(
			'type' => 'text',
		),
		'EditHistory' => array(
			'type' => 'text',
		),
		'PublishedToTeachers' => array(
			'type' => 'text',
		),
		'PublishedToParents' => array(
			'type' => 'text',
		),
		'BlockChangeNotes' => array(
			'type' => 'text',
		),
	);
}