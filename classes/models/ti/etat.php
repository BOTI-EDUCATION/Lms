<?php
namespace Models\TI;
use \Models\Model;

class Etat extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'ti_etats';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	
	protected static $fields = array(
		'Label' => array(
			'type' => 'varchar',
		),
		'Action' => array(
			'type' => 'varchar',
		),
		'Couleur' => array(
			'type' => 'varchar',
		),
	);

}
