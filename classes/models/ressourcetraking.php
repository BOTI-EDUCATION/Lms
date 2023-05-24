<?php

namespace Models;

class RessourceTraking extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'ressources_traking';

	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);

	protected static $fields = array(
		'Inscription' => array(
			'fk' => 'Inscription',
		),
		'Eleve' => array(
			'fk' => 'Eleve',
		),
		'Ressource' => array(
			'fk' => 'Ressource',
		),
		'Action' => array(
			'type' => 'varchar',
		),
		'Result' => array(
			'type' => 'text',
		),
		'Date' => array(
			'type' => 'datetime',
		),
	);
}
