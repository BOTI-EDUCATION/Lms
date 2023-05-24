<?php
namespace Models;

class HDStatutDemandeLog extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'hdstatutdemandeslog';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Demande' => array(
			'fk' => 'HDDemande',
		),
		'Statut' => array(
			'fk' => 'HDStatutDemande',
		),
		'User' => array(
			'fk' => 'User',
		),
		'Date' => array(
			'type' => 'datetime',
		),
	);
}
