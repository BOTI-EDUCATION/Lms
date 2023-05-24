<?php

namespace Models\RH;

use \Models\Model;

class Suivi extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'rh_suivi';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Collaborateur' => array(
			'fk' => 'RH\\Collaborateur',
		),
		'Label' => array(
			'type' => 'varchar',
		),
		'Details' => array(
			'type' => 'varchar',
		),
		'Flag' => array(
			'type' => 'boolean',
		),
		'Evaluation' => array(
			'type' => 'date',
		),
		'AllowCollaborateurToShow' => array(
			'type' => 'boolean',
		),
		'Date' => array(
			'type' => 'varchar',
		),
		'Creation' => array(
			'type' => 'text',
		),
		'EditHistory' => array(
			'type' => 'text',
		),
	);
}
