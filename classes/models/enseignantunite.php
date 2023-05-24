<?php

namespace Models;

class EnseignantUnite extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'sco_enseignantunite';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Unite' => array(
			'fk' => 'Unite',
		),
		'Enseignant' => array(
			'fk' => 'Enseignant',
		),
	);
}
