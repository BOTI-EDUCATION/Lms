<?php
namespace Models\TI;
use \Models\Model;

class Type extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'ti_types';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	
	protected static $fields = array(
		'Label' => array(
			'type' => 'varchar',
		),
	);

}
