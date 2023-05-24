<?php

namespace Models;

class TypeEvaluation extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'sco_types_evaluations';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Code' => array(
			'type' => 'varchar',
		),
		'ByUnite' => array(
			'type' => 'boolean',
		),
		'Label' => array(
			'type' => 'varchar',
		),
		'LabelAr' => array(
			'type' => 'varchar',
		),
		'Icone' => array(
			'type' => 'varchar',
		),
		'Ordre' => array(
			'type' => 'int',
		),
		'CanBePlannedbyTeacher' => array(
			'type' => 'boolen',
		),
		'Enabled' => array(
			'type' => 'boolean',
		),
	);


	public static function getByCode($code)
	{
		$id = \DB::scallar('SELECT `ID` FROM ' . static::wrapField(static::$table) . ' WHERE `Code`=?', array($code));
		if (!$id)
			return null;
		return new self($id);
	}
}
