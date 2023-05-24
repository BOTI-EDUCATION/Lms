<?php

namespace Models\FIN;

use \Models\Model;

class DepenseBudgets extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'fnc_depense_budgets';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Rubrique' => array(
			'fk' => 'FIN\\DepenseRubrique',
		),
		'RubriqueAlias' => array(
			'type' => 'varchar',
		),
		'RubriqueLabel' => array(
			'type' => 'varchar',
		),
		'Promotion' => array(
			'fk' => 'Promotion',
		),
		'Remarques' => array(
			'type' => 'text',
		),
		'Budget' => array(
			'type' => 'double',
		),
		'Validation' => array(
			'type' => 'text',
		),
		'EditHistory' => array(
			'type' => 'text',
		),
	);


	public function getDepensesMontant()
	{

		$montant = \DB::scallar('SELECT (SUM(`Montant`)) FROM `fnc_depenses` WHERE `DepenseRubrique` = ?  AND `Promotion` = ? ', array($this->Rubrique->ID, $this->Promotion->ID));

		if (!$montant)
			$montant = 0;

		return $montant;
	}
}
