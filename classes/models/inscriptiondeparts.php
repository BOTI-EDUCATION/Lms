<?php

namespace Models;

use Models\ETD\SeanceTracking;

class InscriptionDeparts extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'ins_inscripitons_departs';

	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);

	protected static $fields = array(
		'Promotion' => array(
			'fk' => 'Promotion',
		),
		'Inscription' => array(
			'fk' => 'Inscription',
		),
		'Eleve' => array(
			'type' => 'varchar',
		),
		'Classe' => array(
			'fk' => 'Classe',
		),
		'Depart' => array(
			'type' => 'boolean',
		),
		'DepartDate' => array(
			'type' => 'date',
		),
		'DepartBy' => array(
			'fk' => 'User',
		),
		'DepartMotif' => array(
			'type' => 'varchar',
		),
		'DepartCommentaire' => array(
			'type' => 'varchar',
		),
		'NotificationViews' => array(
			'type' => 'text',
		),
		'NotificationMessage' => array(
			'type' => 'text',
		),
		'Canceled' => array(
			'type' => 'text',
		),
	);
}
