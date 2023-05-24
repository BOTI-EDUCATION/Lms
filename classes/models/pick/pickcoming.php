<?php

namespace Models\Pick;

use \Models\Model;

class PickComing extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'pick_coming';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);

	protected static $fields = array(
		'Inscription' => array(
			'fk' => 'Inscription',
		),
		'Niveau' => array(
			'fk' => 'Niveau',
		),
		'Classe' => array(
			'fk' => 'Classe',
		),
		'Parent' => array(
			'fk' => 'Parentt',
		),
		'Personne' => array(
			'type' => 'varchar',
		),
		'Type' => array(
			'type' => 'varchar',
		),
		'DateTime' => array(
			'type' => 'datetime',
		),
		'Classes' => array(
			'type' => 'texte',
		),
		'Inscriptions' => array(
			'type' => 'text',
		),

		'PickedUp' => array(
			'type' => 'text',
		),
	);


	public function getInscriptions()
	{
		// return $this->Inscription ? [$this->Inscription] : \Models\Inscription::getList(array('where' => array('ID IN (' . $this->get('Inscriptions') . ')')));
		return $this->Inscription ? [$this->Inscription] : [];
	}

	public function getInscriptionsIds()
	{
		return $this->get('Inscriptions') ? explode(',', str_replace('"', '', $this->get('Inscriptions'))) : array();
	}

	public function getType()
	{
		if ($this->Type == 'coming') {
			return "En route";
		}
		return "Arriver";
	}


	public static function getPick($parent, $inscription)
	{
		$where = array();
		$where[] = '(PickedUp IS NULL AND (DateTime LIKE \'' . date('Y-m-d') . '%\')) ';
		$where['Parent'] = $parent->get('ID');
		$where['Parent'] = $parent->get('ID');

		return self::where($where)->first();
	}
}
