<?php

namespace Models\MGMT\Team;

use Models\Model as Model;

class NiveauxMaitrise extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'mgmt_team_competences_niveaux';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(

		'Code' => array(
			'type' => 'varchar',
		),
		'Alias' => array(
			'type' => 'varchar',
		),
		'Label' => array(
			'type' => 'varchar',
		),
		'Points' => array(
			'type' => 'int',
		),
		'Color' => array(
			'type' => 'varchar',
		),
		'CanEdit' => array(
			'type' => 'boolean',
		),
	);

	public static function getByAlias($alias)
	{
		$id = \DB::scallar('SELECT `ID` FROM ' . static::wrapField(static::$table) . ' WHERE `Alias`=?', array($alias));
		if (!$id)
			return null;
		return new self($id);
	}
}
