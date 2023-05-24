<?php
namespace Models;

class Holiday extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'holidays';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Label' => array(
			'type' => 'varchar',
		),
		'Promotion' => array(
			'fk' => 'Promotion',
		),
		'DateDebut' => array(
			'type' => 'date',
		),
		'DateFin' => array(
			'type' => 'date',
		),
		'Remarques' => array(
			'type' => 'text',
		),
	);


}
