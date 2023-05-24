<?php
namespace Models;

class PeriodeScore extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'periodescores';
	
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Inscription' => array(
			'fk' => 'Inscription',
		),
		'Periode' => array(
			'fk' => 'Periode',
		),
		'Promotion' => array(
			'fk' => 'Promotion',
		),
		'Score' => array(
			'type' => 'decimal',
		),
	);
	
}
