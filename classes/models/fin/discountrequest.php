<?php

namespace Models\FIN;

use \Models\Model;

class DiscountRequest extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'fnc_discount_requests';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);

	protected static $fields = array(
		'Promotion' => array(
			'fk' => 'Promotion',
		),
		'Inscription' => array(
			'fk' => 'Inscription',
		),
		'Rubrique' => array(
			'fk' => 'FIN\EncaissementRubrique',
		),
		'DefaultPrice' => array(
			'type' => 'double',
		),
		'Price' => array(
			'type' => 'double',
		),
		'Discounts' => array(
			'type' => 'varchar',
		),
		'DiscountsAmount' => array(
			'type' => 'varchar',
		),
		'Motif' => array(
			'type' => 'text',
		),
		'Validation' => array(
			'type' => 'text',
		),
		'Refus' => array(
			'type' => 'text',
		),
		'Date' => array(
			'type' => 'date',
		),
		'UserBy' => array(
			'fk' => 'User',
		),
	);
}
