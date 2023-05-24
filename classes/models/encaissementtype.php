<?php
namespace Models;

class EncaissementType extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'fnc_encaissementtypes';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Label' => array(
			'type' => 'varchar',
		),
		'Icone' => array(
			'type' => 'varchar',
		),
	);
	
	
	
}
