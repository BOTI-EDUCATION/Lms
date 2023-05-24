<?php

namespace Models;

class RequestInscription extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'requests_inscriptions';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Promotion' => array(
			'fk' => 'Promotion',
		),
		'Parent' => array(
			'fk' => 'Parentt',
		),
		'Site' => array(
			'fk' => 'Site',
		),
		'Nom' => array(
			'type' => 'varchar',
		),
		'Prenom' => array(
			'type' => 'varchar',
		),
		'NiveauEnseignement' => array(
			'fk' => 'Niveau',
		),
		'Ville' => array(
			'type' => 'text',
		),
		'GSM' => array(
			'type' => 'varchar',
		),
		'Email' => array(
			'type' => 'text',
		),
		'Adresse' => array(
			'type' => 'text',
		),
		'ReInscriptionEleve' => array(
			'fk' => 'Eleve',
		),
		'ReInscriptionPromotion' => array(
			'fk' => 'Promotion',
		),
		'CreatedAt' => array(
			'type' => 'datetime',
		),
		'ValidateBy' => array(
			'fk' => 'User',
		),
		'ValidateAt' => array(
			'type' => 'datetime',
		),
	);
}
