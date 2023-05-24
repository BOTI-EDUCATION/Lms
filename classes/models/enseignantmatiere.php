<?php
namespace Models;

class EnseignantMatiere extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'sco_enseignantsmatieres';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Matiere' => array(
			'fk' => 'Matiere',
		),
		'Enseignant' => array(
			'fk' => 'Enseignant',
		),
	);
	
}
