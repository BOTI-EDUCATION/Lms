<?php
namespace Models;

class HDDegreUrgence extends Model {

	protected static $sqlQueries = array();

	protected static $table = ' hddegreurgences';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Label' => array(
			'type' => 'varchar',
		),
		'Color' => array(
			'type' => 'varchar',
		),
		'Ordre' => array(
			'type' => 'int',
		),
	);
}
