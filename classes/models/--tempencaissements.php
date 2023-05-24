<?php

namespace Models\FIN;

use Models\Model;

class TempEncaissements extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'fnc_temp_encaissements';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Massar' => array(
			'type' => 'varchar',
		),
		'Promotion' => array(
			'fk' => 'Promotion',
		),
		'Nom' => array(
			'type' => 'varchar',
		),
		'NumRecu' => array(
			'type' => 'varchar',
		),
		'Montant' => array(
			'type' => 'double',
		),
		'PaiementMode' => array(
			'type' => 'varchar',
		),
		'NumCheque' => array(
			'type' => 'varchar',
		),
		'Details' => array(
			'type' => 'text',
		),
		'Date' => array(
			'type' => 'date',
		),
	);
}
