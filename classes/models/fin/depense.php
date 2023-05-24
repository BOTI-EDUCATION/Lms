<?php

namespace Models\FIN;

use \Models\Model;

class Depense extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'fnc_depenses';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'DepenseType' => array(
			'fk' => 'FIN\\DepenseType',
		),
		'DepenseRubrique' => array(
			'fk' => 'FIN\\DepenseRubrique',
		),
		'Prestataire' => array(
			'fk' => 'FIN\\DepensePrestataire',
		),
		'Request' => array(
			'fk' => 'FIN\\DepenseRequest',
		),
		'Promotion' => array(
			'fk' => 'Promotion',
		),
		'PaiementMode' => array(
			'fk' => 'FIN\\PaiementMode',
		),
		'ClassesVises' => array(
			'type' => 'text',
		),
		'DetailMode' => array(
			'type' => 'int',
		),
		'Label' => array(
			'type' => 'varchar',
		),
		'Note' => array(
			'type' => 'text',
		),
		'Montant' => array(
			'type' => 'double',
		),
		'Date' => array(
			'type' => 'datetime',
		),
		'DateDepense' => array(
			'type' => 'datetime',
		),

		'ModePaiement' => array(
			'type' => 'varchar',
		),
		'NumeroFacture' => array(
			'type' => 'text',
		),
		'DateFacture' => array(
			'type' => 'date',
		),
		'PrestataireRaisonSociale' => array(
			'type' => 'varchar',
		),
		'Cancelled' => array(
			'type' => 'text',
		),
		'Files' => array(
			'type' => 'text',
		),
		'EditHistory' => array(
			'type' => 'text',
		),
		'UserBy' => array(
			'fk' => 'User',
		),
		'Charged' => array(
			'type' => 'text',
		),

	);

	public static function LastMonth()
	{

		$promotions = Promotion::getList(array('where' => array('Actuelle' => true)));

		$encaissements = \DB::scallar('SELECT SUM(`Montant`) FROM `depenses` WHERE MONTH(DateFacture) =' . date('m') . ' AND Promotion = ' . $promotions[0]->get('ID'));
		if (!$encaissements)
			$encaissements = 0;
		return $encaissements;
	}

	public function getDetailPaeiement()
	{

		if ($this->get('PaiementMode') && $this->get('PaiementMode')->get('Alias') == 'cheque') {

			$items = PaiementchequeDetail::getList(array('where' => array('ID' => $this->get('DetailMode'))));
		} elseif ($this->get('PaiementMode') && $this->get('PaiementMode')->get('Alias') == 'virement') {

			$items = PaiementVirementDetail::getList(array('where' => array('ID' => $this->get('DetailMode'))));
		}
		if ($items)
			return $items[0];

		return null;
	}


	public function total_depense($mois)
	{

		$promotions = \Models\Promotion::getList(array('where' => array('Actuelle' => true)));

		$encaissements = \DB::scallar('SELECT SUM(`Montant`) FROM `fnc_depenses` WHERE MONTH(DateFacture) =' . $mois . ' AND Promotion = ' . $promotions[0]->get('ID'));
		if (!$encaissements)
			$encaissements = 0;
		return $encaissements;
	}
	

	public function getFiles()
	{
		return \GoogleStorage::getUrl(\Config::get('path-encaissements') . '/depenses/'  . $this->get('Files'));
	}
}
