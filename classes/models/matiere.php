<?php

namespace Models;

class Matiere extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'sco_matieres';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Unite' => array(
			'fk' => 'Unite',
		),
		'Principale' => array(
			'type' => 'boolean',
		),
		'Label' => array(
			'type' => 'varchar',
		),
		'LabelAr' => array(
			'type' => 'varchar',
		),
		'IsRTL' => array(
			'type' => 'boolean',
		),
		'Intro' => array(
			'type' => 'varchar',
		),
		'Icone' => array(
			'type' => 'varchar',
		),
		'Enabled' => array(
			'type' => 'tinyint',
		),
		'Color' => array(
			'type' => 'varchar',
		),
		'TextColor' => array(
			'type' => 'varchar',
		),
		'MassarOrderLigne' => array(
			'type' => 'varchar',
		),
		'MassarOrderCells' => array(
			'type' => 'varchar',
		),
	);

	public function getCountEnseignants()
	{
		return null;
	}

	public function getCoefficient($niveau, $type = 'Coefficient_Ecole')
	{
		$coefficient = \DB::scallar('SELECT ' . static::wrapField($type) . ' FROM ' . static::wrapField('sco_niveaux_unites_matieres') . ' WHERE Matiere=? and Niveau=?', array($this->get('ID'), $niveau->get('ID')));

		if (!$coefficient)
			return null;

		return $coefficient;
	}

	public function _getCoefficient($niveau, $type = 'Coefficient_Ecole')
	{
		$query = 'SELECT ' . $type . ' FROM sco_niveaux_unites_matieres WHERE Matiere = ' . $this->get('ID') . ' AND Niveau = ' . $niveau->get('ID');
		$result = \DB::reader($query);

		$coefficient = isset($result[0]) ? ($result[0][$type] ? $result[0][$type] : 0) : 0;

		return $query;
	}

	public static function getByAlias($alias)
	{
		$idPost = \DB::scallar('SELECT `ID` FROM ' . static::wrapField(static::$table) . ' WHERE `Label`=?', array($alias));

		if (!$idPost)
			return null;

		return new self($idPost);
	}

	public static function matiereExist($label)
	{
		$idPost = \DB::scallar('SELECT `ID` FROM ' . static::wrapField(static::$table) . ' WHERE `Label`=?', array($label));

		if (!$idPost)
			return null;

		return true;
	}


	// public function getIcone($full_link = false)
	// {

	// 	$icon = $this->get('Icone');
	// 	if (!$icon) {
	// 		$icon = 'default1.png';
	// 	}

	// 	return ($full_link ? \URL::AbsoluteLink() : \URL::base('')) . \Config::get('path-matieres') . $icon;
	// }
	
	public function getIcone($full_link = false)
	{
		return $this->Unite ? $this->Unite->getIcone($full_link) : null;
	}
}
