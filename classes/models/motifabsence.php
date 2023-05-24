<?php

namespace Models;

class MotifAbsence extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'motif_absences';

	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Alias' => array(
			'type' => 'varchar',
		),
		'Label' => array(
			'type' => 'varchar',
		),
		'Color' => array(
			'type' => 'varchar',
		),
		'Icone' => array(
			'type' => 'varchar',
		),
		'EditHistory' => array(
			'type' => 'text',
		),
	);

	public function getIcone()
	{
		return \GoogleStorage::getUrl(\Config::get('path-type-abs') . $this->get('Icone'));
	}


	public static function getByAlias($alias) {
		$idPost = \DB::scallar('SELECT `ID` FROM '.static::wrapField(static::$table). ' WHERE `Alias`= ?', array($alias));
		if (!$idPost)
			return null;

		return new self($idPost);
	}
}
