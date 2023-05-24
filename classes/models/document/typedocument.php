<?php

namespace Models\Document;

use Models\Model;

class TypeDocument extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'gen_documents';

	protected static $pk = array(
		'Alias' => array(
			'auto' => false,
		),
	);
	protected static $fields = array(
		// 'Alias' => array(
		// 	'type' => 'varchar',
		// ),
		'Label' => array(
			'type' => 'varchar',
		),
		'Icon' => array(
			'type' => 'varchar',
		),
		'Presentation' => array(
			'type' => 'varchar',
		),
		'Niveaux' => array(
			'type' => 'varchar',
		),
		'Public' => array(
			'type' => 'boolean',
		),
		'Enabled' => array(
			'type' => 'boolean',
		),
		'Date' => array(
			'type' => 'date',
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
		if (!$this->get('Icon') || !file_exists(_basepath . \Config::get('path-repository-icons') . 'documents/'  . $this->get('Icon'))) {
			return 'https://cdn-icons-png.flaticon.com/512/7514/7514355.png';
		}
		return \URL::base() . \Config::get('path-repository-icons') . 'documents/'  . $this->get('Icon');
	}

	public function getNiveaux()
	{
		return \Models\Niveau::query()->whereIn('ID', $this->getArray('Niveaux'))->get();
	}
}
