<?php
namespace Models;

class ParrainageType extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'parrainagetypes';
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
