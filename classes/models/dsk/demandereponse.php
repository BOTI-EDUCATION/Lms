<?php
namespace Models\DSK;
use \Models\Model;

class DemandeReponse extends Model {
	
	protected static $sqlQueries = array();
	
	protected static $table = 'dsk_demandesreponses';
	
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Demande' => array(
			'fk' => 'DSK\\Demande',
			'type' => 'int',
		),
		'Question' => array(
			'fk' => 'DSK\\NatureQuestion',
			'type' => 'int',
		),
		'Value' => array(
			'type' => 'text',
		),
	);
}
