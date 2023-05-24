<?php
namespace Models;

class HDNatureDemande extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'dsk_natures';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Label' => array(
			'type' => 'varchar',
		),
		'Ordre' => array(
			'type' => 'int',
		),
		'Interne' => array(
			'type' => 'boolean',
		),
		'DureeTraitement' => array(
			'type' => 'int',
		),
	);
}
