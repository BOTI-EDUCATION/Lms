<?php

namespace Models\FIN;

use \Models\Model;

class Discount extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'fnc_discounts';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Promotion' => array(
			'fk' => 'promotion',
		),
		'Discount' => array(
			'fk' => 'DiscountBase',
		),
		'DiscountAlias' => array(
			'type' => 'varchar',
		),
		'DiscountLabel' => array(
			'type' => 'varchar',
		),
		'Percent' => array(
			'type' => 'int',
		),
		'Autorized' => array(
			'type' => 'boolean',
		),
		'EditHistory' => array(
			'type' => 'text',
		),
	);
}
