<?php
namespace Models\TI;
use \Models\Model;

class TacheCollaborateur extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'ti_tachecollaborateurs';
	
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
	);
	
}
