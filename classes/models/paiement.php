<?php
namespace Models;

class Paiement extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'paiements';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Inscription' => array(
			'fk' => 'Inscription',
		),
		'UserBy' => array(
			'fk' => 'User',
		),
		'PaiementMode' => array(
			'fk' => 'PaiementMode',
		),
		'ValideBy' => array(
			'type' => 'User',
		),
		'EncaisseBy' => array(
			'type' => 'User',
		),
		'RejetBy' => array(
			'type' => 'User',
		),
		'DetailMode' => array(
			'type' => 'int',
		),
		'Montant' => array(
			'type' => 'double',
		),
		'Label' => array(
			'type' => 'varchar',
		),
		'Reference' => array(
			'type' => 'varchar',
		),
		'NumRecu' => array(
			'type' => 'varchar',
		),
		'Note' => array(
			'type' => 'text',
		),
		'File' => array(
			'type' => 'varchar',
		),
		'Valide' => array(
			'type' => 'datetime',
		),
		'Encaisse' => array(
			'type' => 'datetime',
		),
		'Rejet' => array(
			'type' => 'datetime',
		),
		'DatePaiement' => array(
			'type' => 'date',
		),
	);
	
	
	
}
