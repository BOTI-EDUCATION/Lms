<?php
namespace Models;

class EnseignantClasse extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'sco_enseignantclasses';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Classe' => array(
			'fk' => 'Classe',
		),
		'Enseignant' => array(
			'fk' => 'Enseignant',
		),
	);
	
}
