<?php

namespace Models\RH;

use \Models\Model;

class Absence extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'rh_absences';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Collaborateur' => array(
			'fk' => 'RH\\Collaborateur',
		),
		'Seances' => array(
			'type' => 'text',
		),
		'Absence' => array(
			'type' => 'text',
		),
		'Retard' => array(
			'type' => 'int',
		),
		'Details' => array(
			'type' => 'varchar',
		),
		'Date' => array(
			'type' => 'date',
		),
		'Creation' => array(
			'type' => 'text',
		),
		'Validation' => array(
			'type' => 'text',
		),
	);
}
