<?php
namespace Models;

class DisciplineActionView extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'disciplineactionviews';
	
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'DisciplineAction' => array(
			'fk' => 'DisciplineAction',
		),
		'User' => array(
			'fk' => 'User',
		),
		'Date' => array(
			'type' => 'datetime',
		),
	);
	
}
