<?php

namespace Models\RH;

use \Models\Model;

class Diplome extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'rh_diplomes';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Collaborateur' => array(
			'fk' => 'RH\\Collaborateur',
		),
		'Etablissement' => array(
			'type' => 'varchar',
		),
		'AnneeObtention' => array(
			'type' => 'varchar',
		),
		'TypeDiplome' => array(
			'type' => 'varchar',
		),
		'Mention' => array(
			'type' => 'varchar',
		),
		'Specialite' => array(
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
