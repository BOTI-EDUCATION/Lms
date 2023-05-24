<?php

namespace Models\FIN;

use \Models\Model;

class DiscountBase extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'fnc_discounts_base';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Label' => array(
			'type' => 'varchar',
		),
		'Alias' => array(
			'type' => 'varchar',
		),
		'Icon' => array(
			'type' => 'varchar',
		),
		'TypeValue' => array(
			'type' => 'varchar',
		),
		'Value' => array(
			'type' => 'int',
		),
		'DefaultPercent' => array(
			'type' => 'int',
		),
		'Autorized' => array(
			'type' => 'boolean',
		),
		'Remarques' => array(
			'type' => 'text',
		),
		'EditHistory' => array(
			'type' => 'text',
		),
	);


	public function getIcone()
	{
		if (!$this->get('Icon')) {
			return 'https://cdn-icons-png.flaticon.com/512/7514/7514355.png';
		}
		return \GoogleStorage::getUrl(\Config::get('path-encaissements') . 'discounts/'  . $this->get('Icon'));
	}


	public static  function typesValue($item_alias = null)
	{
		$items  =  [
			'percent' => [
				'label' => 'Percent',
				'short' => '%',
			],
			'amount' => [
				'label' => 'Montant',
				'short' => 'M',
			],
		];

		if ($item_alias) {
			return $items[$item_alias] ?? 'Mensuel';
		}
		return $items;
	}

	
	public function calculateDiscount($amount)
	{
		if ($this->TypeValue == 'amount') {
			return ($this->Value);
		}
		
		return ($amount  * ($this->Value / 100));
	}
}
