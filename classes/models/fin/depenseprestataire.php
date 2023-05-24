<?php

namespace Models\FIN;

use \Models\Model;

class DepensePrestataire extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'fnc_depense_prestataires';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'RaisonSociale' => array(
			'type' => 'varchar',
		),
		'Rubriques' => array(
			'type' => 'text',
		),
		'Logo' => array(
			'type' => 'varchar',
		),
		'Adresse' => array(
			'type' => 'varchar',
		),
		'GSM' => array(
			'type' => 'varchar',
		),
		'Telephone' => array(
			'type' => 'varchar',
		),
		'Email' => array(
			'type' => 'varchar',
		),
		'ContactPrincipal' => array(
			'type' => 'varchar',
		),
		'Ville' => array(
			'type' => 'varchar',
		),
		'Activite' => array(
			'type' => 'text',
		),
		// 'ICE' => array(
		// 	'type' => 'varchar',
		// ),
		// 'Patente' => array(
		// 	'type' => 'varchar',
		// ),
		'Enabled' => array(
			'type' => 'boolean',
		),
		'EditHistory' => array(
			'type' => 'text',
		),
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

	public function getLogo()
	{
		return \URL::base() . \Config::get('path-depenses-types') . '/'  . $this->get('Logo');
	}

	public function rubriques()
	{
		return DepenseRubrique::getList(array('where' => 'ID IN(' . implode(",", $this->getArray('Rubriques')) . ')'));
	}
	public function rubriquesLabels()
	{
		return	implode(",", array_map(function ($it) {
			return $it->Label;
		}, $this->rubriques()));
	}
}
