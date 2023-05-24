<?php

namespace Models\FIN;

use \Models\Model;

class Banque extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'banques';
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
	);



	function trans()
	{
		return PaiementchequeDetail::getList(array('where' => array('Banque' => $this->ID)));
	}
}
