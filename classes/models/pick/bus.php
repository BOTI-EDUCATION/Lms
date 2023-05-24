<?php

namespace Models\Pick;

use \Models\Model;

class Bus extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'pick_bus_picks';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);

	protected static $fields = array(
		'MainEleve' => array(
			'fk' => 'Eleve',
		),
		'MainInscription' => array(
			'fk' => 'Inscription',
		),
		'Trajet' => array(
			'fk' => 'Pick\\Itineraire',
		),
		'Eleves' => array(
			'type' => 'varchar',
		),
		'LastVoyageRef' => array(
			'type' => 'text',
		),
		'Inscriptions' => array(
			'type' => 'varchar',
		),
		'Flag' => array(
			'type' => 'varchar',
		),
		'Absent' => array(
			'type' => 'boolean',
		),
		'DateTime' => array(
			'type' => 'date',
		),
		'TrajetLabel' => array(
			'type' => 'varchar',
		),
		'VehiculeLabel' => array(
			'type' => 'varchar',
		),
		'ChauffeurName' => array(
			'type' => 'varchar',
		),
		'AssistantName' => array(
			'type' => 'varchar',
		),
		'ChauffeurID' => array(
			'type' => 'int',
		),
		'AssistantID' => array(
			'type' => 'int',
		),
		'ParentsNotifiedIDS' => array(
			'type' => 'text',
		),
		'ParentsNotifiedNames' => array(
			'type' => 'text',
		),
		'Views' => array(
			'type' => 'text',
		),
		'UserBy' => array(
			'fk' => 'User',
		),
	);
}
