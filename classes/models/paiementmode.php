<?php
namespace Models;

class PaiementMode extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'paiementmodes';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Label' => array(
			'type' => 'varchar',
		),
		'Alias' => array(
			'type' => 'varchar',
		),
	);
}
