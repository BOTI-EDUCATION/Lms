<?php
namespace Models;

class HDDemandeReponse extends Model {
	
	protected static $sqlQueries = array();
	
	protected static $table = '	dsk_demandesreponses';
	
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Demande' => array(
			'fk' => 'HDDemande',
			'type' => 'int',
		),
		'Question' => array(
			'fk' => 'HDNatureDemandeQuestion',
			'type' => 'int',
		),
		'Value' => array(
			'type' => 'text',
		),
	);
}
