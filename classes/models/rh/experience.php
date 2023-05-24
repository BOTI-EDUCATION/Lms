<?php

namespace Models\RH;

use \Models\Model;

class Experience extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'rh_experiences';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Collaborateur' => array(
			'fk' => 'RH\\Collaborateur',
		),
		'Organisme' => array(
			'type' => 'varchar',
		),
		'Fonction' => array(
			'type' => 'varchar',
		),
		'Mission' => array(
			'type' => 'varchar',
		),
		'dateDebut' => array(
			'type' => 'date',
		),
		'dateFin' => array(
			'type' => 'date',
		),
		'TypeExperience' => array(
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
