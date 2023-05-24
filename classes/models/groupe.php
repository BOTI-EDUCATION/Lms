<?php
namespace Models;

class Groupe extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'sco_groupes';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Label' => array(
			'type' => 'varchar',
		),
		'LabelAr' => array(
			'type' => 'varchar',
		),
		'Ordre' => array(
			'type' => 'int',
		),
	);
}
