<?php
namespace Models\TI;
use \Models\Model;

class TacheEtat extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'ti_tacheetats';
	
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Tache' => array(
			'fk' => 'TI\\Tache',
		),
		'User' => array(
			'fk' => 'User',
		),
		'Etat' => array(
			'fk' => 'TI\\Etat',
		),
		'Commentaire' => array(
			'type' => 'text',
		),
		'Date' => array(
			'type' => 'datetime',
		),
	);
	
}
