<?php
namespace Models;

class EnseignantNiveau extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'sco_enseignantniveaux';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Niveau' => array(
			'fk' => 'Niveau',
		),
		'Enseignant' => array(
			'fk' => 'Enseignant',
		),
	);
	
}
