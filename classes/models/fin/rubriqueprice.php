<?php

namespace Models\FIN;

use Models\Model;
use Models\Promotion;

class RubriquePrice extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'fnc_rubriqueprices';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);

	protected static $fields = array(
		'Niveau' => array(
			'fk' => 'Niveau',
		),
		'NiveauAlias' => array(
			'type' => 'varchar',
		),
		'Site' => array(
			'fk' => 'Site',
		),
		'EncaissementRubrique' => array(
			'fk' => 'FIN\\EncaissementRubrique',
		),
		'EncaissementRubriqueAlias' => array(
			'type' => 'varchar',
		),
		'Promotion' => array(
			'fk' => 'Promotion'
		),
		'Frais' => array(
			'type' => 'double',
		),

		'Remarques' => array(
			'type' => 'text',
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

	public static function rubriquePrice($niveau, $type, $promotion  = null, $site =  null)
	{

		if (!$promotion) {
			$promotion =  Promotion::actuelle();
		}

		if (!$site) {
			$site = \Models\Site::query()->first();
		}

		$rubriquePrices = RubriquePrice::getList(array('where' => array(
			'Niveau' => $niveau->get('ID'),
			'Site' => $site->getPk(true),
			'EncaissementRubrique' => $type->get('ID'),
			'Promotion' => $promotion->get('ID')
		)));

		if ($rubriquePrices)
			$rubriquePrice = $rubriquePrices[0];
		else {
			$rubriquePrice = new RubriquePrice();
			$rubriquePrice->set('Frais', $type->get('MontantDefaut'))
				->set('Niveau', $niveau)
				->set('Site', $site)
				->set('NiveauAlias', $niveau->Code)
				->set('EncaissementRubrique', $type)
				->set('EncaissementRubriqueAlias', $type->Alias)
				->set('Promotion', $promotion->get('ID'))
				->save();
		}

		return $rubriquePrice;
	}

	public static function grille($where = array())
	{

		$grille = array();
		$rubriquePrices = RubriquePrice::getList(array('where' => $where));

		foreach ($rubriquePrices as $item) {
			try {
				if ($item->get('Niveau') && $item->get('EncaissementRubrique'))
					$grille[$item->get('Niveau')->get('ID')][$item->get('EncaissementRubrique')->get('ID')] = $item;
			} catch (\Exception $th) {
				continue;
			}
		}

		return $grille;
	}
}
