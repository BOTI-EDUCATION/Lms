<?php
namespace Models\DSK;
use \Models\Model;

class Statut extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'dsk_statuts';
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
		'Icon' => array(
			'type' => 'varchar',
		),
		'Ordre' => array(
			'type' => 'int',
		),
	);
}
