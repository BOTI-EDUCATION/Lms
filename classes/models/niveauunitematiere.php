<?php

namespace Models;

class NiveauUniteMatiere extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'sco_niveaux_unites_matieres';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Niveau' => array(
			'fk' => 'Niveau',
			'type' => 'int',
		),
		'Unite' => array(
			'fk' => 'Unite',
			'type' => 'int',
		),
		'Matiere' => array(
			'fk' => 'Matiere',
		),
		'Coefficient_Ecole' => array(
			'type' => 'int',
		),
		'Coefficient_Massar' => array(
			'type' => 'int',
		),
		'Ordre' => array(
			'type' => 'int',
		)
	);
}
