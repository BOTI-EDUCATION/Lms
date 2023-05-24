<?php
namespace Models\FIN;
use \Models\Model;

class DepenseType extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'fnc_depensetypes';
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
		'Color' => array(
			'type' => 'varchar',
		),
	);
	
}
