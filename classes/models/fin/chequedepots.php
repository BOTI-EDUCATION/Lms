<?php

namespace Models\FIN;

use \Models\Model;

class ChequeDepots extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'fnc_cheques_depots';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Encaissement' => array(
			'fk' => 'FIN\\Encaissement',
		),
		'Promotion' => array(
			'fk' => 'Promotion',
		),
		'User' => array(
			'fk' => 'User',
		),
		'Banque' => array(
			'fk' => 'FIN\\Banque',
		),
		'ReferenceDepot' => array(
			'type' => 'varchar',
		),
		'DateDepot' => array(
			'type' => 'date',
		),
		'Commentaire' => array(
			'type' => 'varchar',
		),
		'NumeroCheque' => array(
			'type' => 'varchar',
		),
		'Tireur' => array(
			'type' => 'varchar',
		),
		'Montant' => array(
			'type' => 'double',
		),
		'Impayes' => array(
			'type' => 'text',
		),
		'Encaisse' => array(
			'type' => 'text',
		),
		'Date' => array(
			'type' => 'date',
		)
	);
}
