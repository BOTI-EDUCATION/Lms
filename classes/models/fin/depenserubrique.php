<?php

namespace Models\FIN;

use \Models\Model;

class DepenseRubrique extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'fnc_depense_rubriques';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Parent' => array(
			'fk' => 'FIN\\DepenseRubrique',
		),
		'Alias' => array(
			'type' => 'varchar',
		),
		'Label' => array(
			'type' => 'varchar',
		),
		'Icone' => array(
			'type' => 'varchar',
		),
		'Color' => array(
			'type' => 'varchar',
		),
		'Ordre' => array(
			'type' => 'int',
		),
		'Enabled' => array(
			'type' => 'boolean',
		),
		'EditHistory' => array(
			'type' => 'text',
		),
	);


	public function getIcone()
	{
		if (!$this->Icone || !file_exists(_basepath . \Config::get('path-depenses-types') . '/'  . $this->get('Icone'))) {
			return \URL::base() . "assets/icons/no_services.png";
		}
		return \URL::base() . \Config::get('path-depenses-types') . '/'  . $this->get('Icone');
	}

	public  function getBudget($promotion)
	{
		$budgets = DepenseBudgets::getList(array('where' => array('Rubrique' => $this->ID, 'Promotion' => $promotion->get('ID'))));
		return isset($budgets[0]) ? $budgets[0] : null;
	}
}
