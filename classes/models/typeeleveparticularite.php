<?php

namespace Models;

class TypeEleveParticularite extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'type_eleve_particularite';

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
		'Icone' => array(
			'type' => 'varchar',
		),
		'EditHistory' => array(
			'type' => 'text',
		)
	);


	public function beforeSave()
	{
		$history = $this->getArray('EditHistory') ?: array();
		$history[] = array(
			'user' => \Session::getInstance()->getCurUser()->ID,
			'action' => $this->saved ? 'update' : 'add',
			'date' => date('Y-m-d H:i:s'),
		);
		$this->setJson('EditHistory', $history);
		
	}


	public function getIcone()
	{
		

		return \GoogleStorage::getUrl(\Config::get('path-type-suivi') . '/'  . $this->get('Icone'));
	}
}
