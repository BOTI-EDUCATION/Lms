<?php

namespace Models\FIN;

use \Models\Model;

class Modalite extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'fnc_modalite';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);

	protected static $fields = array(
		'Promotion' => array(
			'fk' => 'Promotion',
		),
		'Modalite' => array(
			'type' => 'text',
		),
		'Enabled' => array(
			'type' => 'text',
		),
		'Details' => array(
			'type' => 'text',
		),
	);


	
	public static  function listOptions($item_alias = null)
	{
		$items  =  [
			'yearly' => 'Paiement Annuel',
			'quartly' => 'Paiement trimestrie',
			'monthly' => 'Paiement Mensuel',
		];

		if ($item_alias) {
			return $items[$item_alias] ?? 'Paiement Mensuel';
		}
		return $items;
	}

	public static function getModalite($promotion, $modalite)
	{

		return self::where(['Promotion' => $promotion->ID, 'Modalite' => $modalite])->first();
	}


}
