<?php
namespace Models;

class Parrainage extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'parrainages';
	
	protected static $pk = array(
		'Parent' => array(
			'fk' => 'Parentt',
			'type' => 'int',
		),
		'Eleve' => array(
			'fk' => 'Eleve',
			'type' => 'int',
		),
	);
	protected static $fields = array(
		'Type' => array(
			'fk' => 'ParrainageType',
		),
	);
	
	public static function checkParrainage($parent, $eleve) {
		if(!$parent)
			return false;
		if(!$eleve)
			return false;
		
		$parrainages = Parrainage::getCount(array('where' => array('Parent'=> $parent->get('ID'), 'Eleve'=> $eleve->get('ID'))));
		
		if (!$parrainages || $parrainages == 0)
			return false;
		
		return true;
	}
}
