<?php
namespace Models;

class HDNatureDemandeReponse extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'dsk_naturesquestionsreponses';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Question' => array(
			'fk' => 'HDNatureDemandeQuestion',
			'type' => 'int',
		),
		'Label' => array(
			'type' => 'varchar',
		),
	);
	
}
