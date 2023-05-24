<?php

namespace Models;

class TypeFonction extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'type_fonctions';

	protected static $pk = array(
		'Alias' => array(
			'type' => 'varchar',
		),
	);

	protected static $fields = array(
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
		return \URL::base() . \Config::get('path-repository-icons') . '/fonctions/'  . $this->get('Icone');
	}

	public static function getByAlias($alias) {
		$idPost = \DB::scallar('SELECT `ID` FROM '.static::wrapField(static::$table). ' WHERE `Alias`= ?', array($alias));
		if (!$idPost)
			return null;

		return new self($idPost);
	}
}
