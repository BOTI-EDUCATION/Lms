<?php

namespace Models\Pick;

use \Models\Model;

class Itineraire extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'pick_itineraires';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);

	protected static $fields = array(

		'Vehicule' => array(
			'fk' => 'Pick\\Vehicule',
		),
		'Etablissement' => array(
			'fk' => 'Etablissement',
		),
		'Chauffeur' => array(
			'fk' => 'RH\\Collaborateur',
		),
		'Responsable' => array(
			'fk' => 'User',
		),
		'LastVoyageRef' => array(
			'type' => 'text',
		),
		// 'Chauffeur' => array(
		// 	'type' => 'int',
		// ),
		'Aide' => array(
			'fk' => 'RH\\Collaborateur',
		),

		'Label' => array(
			'type' => 'varchar',
		),
		'Eleves' => array(
			'type' => 'text',
		),
		'AffectationElevesHistory' => array(
			'type' => 'text',
		),
		'Enabled' => array(
			'type' => 'boolean',
		),
		'DeleteAt' => array(
			'type' => 'text',
		),

	);


	public function eleves()
	{
		return ($this->get('Eleves') ? $this->getArray('Eleves', false, true) : []);
	}


	public function arrets()
	{
		if (!$this->eleves())
			return null;

		return count(\DB::reader('SELECT DISTINCT(Parent) FROM `parrainages` WHERE Eleve IN (' . implode(',', $this->eleves()) . ') GROUP By Eleve'));
	}

	public static function getList($args = null, $query = null)
	{
		if (!is_array($args))
			$args = array();

		$args['where'][] = '`DeleteAt` IS  NULL';

		return parent::getList($args, $query);
	}
}
