<?php

namespace Models;

class EleveSuiviScolaire extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'eleve_suivi_scolaire';

	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Eleve' => array(
			'fk' => 'Eleve',
		),
		'Inscription' => array(
			'fk' => 'Inscription',
		),
		'TypeSuiviEleve' => array(
			'fk' => 'TypeSuiviEleve',
		),
		'Suivi' => array(
			'type' => 'text',
		),
		'Details' => array(
			'type' => 'text',
		),
		'Files' => array(
			'type' => 'text',
		),
		'Flag' => array(
			'type' => 'boolean',
		),
		'SaisieBy' => array(
			'fk' => 'User',
		),
		'SaisieByName' => array(
			'type' => 'text',
		),
		'Date' => array(
			'type' => 'datetime',
		),
		'DeletedAt' => array(
			'type' => 'datetime',
		),
		'DeletedBy' => array(
			'fk' => 'User',
		),
		'EditHistory' => array(
			'type' => 'text',
		),
	);

	public function getFileLink()
	{
		return \GoogleStorage::getUrl( \Config::get('path-suivi-eleve')  . $this->get('Files'));
	}

	public static function getList($args = null, $query = null)
	{
		if (!is_array($args))
			$args = array();

		if (!$query)
			$query = static::sqlQuery();

		$args['where'][] = '`DeletedAt` IS NULL';
		return parent::getList($args, $query);
	}
}
