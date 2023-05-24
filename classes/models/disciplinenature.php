<?php
namespace Models;

class DisciplineNature extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'disciplinenatures';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Label' => array(
			'type' => 'varchar',
		),
		'Flag' => array(
			'type' => 'boolean',
		),
	);
}
