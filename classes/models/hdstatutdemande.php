<?php
namespace Models;

class HDStatutDemande extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'hdstatutdemandes';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Label' => array(
			'type' => 'varchar',
		),
		'Couleur' => array(
			'type' => 'varchar',
		),
		'Icon' => array(
			'type' => 'varchar',
		),
		'Ordre' => array(
			'type' => 'int',
		),
	);
}
