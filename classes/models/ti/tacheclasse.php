<?php
namespace Models\TI;
use \Models\Model;

class TacheClasse extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'ti_tacheclasses';
	
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Tache' => array(
			'fk' => 'TI\\Tache',
		),
		'Classe' => array(
			'fk' => 'Classe',
		),
	);
	
}
