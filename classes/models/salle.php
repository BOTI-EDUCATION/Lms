<?php

namespace Models;

class Salle extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'sco_salles';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Site' => array(
			'fk' => 'Site',
		),
		'Label' => array(
			'type' => 'varchar',
		),
		'Intro' => array(
			'type' => 'text',
		),
		'Operationnelle' => array(
			'type' => 'boolean',
		),
	);
}
