<?php

namespace Models\FIN;

use \Models\Model;

class PaiementTpeDetail extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'paiementtpedetails';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'NumTransaction' => array(
			'type' => 'varchar',
		),
	);
}
