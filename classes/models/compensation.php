<?php

namespace Models;

class Compensation extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'compensations';

	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'DisciplineAction' => array(
			'fk' => 'DisciplineAction',
		),
		'Inscription' => array(
			'fk' => 'Inscription',
		),
		'Cadeau' => array(
			'fk' => 'Challenge',
		),
		'Valeur' => array(
			'type' => 'decimal',
		),
		'Icone' => array(
			'type' => 'varchar',
		),
		'Consumed' => array(
			'fk' => 'Buying',
		),
		'ConsumedDate' => array(
			'type' => 'datetime',
		),
		'ConsumedBy' => array(
			'fk' => 'User',
		),
	);

	/*
	ALTER TABLE  `compensations` ADD  `Cadeau` INT NULL AFTER  `Inscription`
		
	ALTER TABLE compensations 
		DROP FOREIGN KEY fk_compensations_inscription
	*/
}
