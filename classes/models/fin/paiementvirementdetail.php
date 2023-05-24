<?php
namespace Models\FIN;
use \Models\Model;

class PaiementVirementDetail extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'paiementvirementdetails';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'NumCompte' => array(
			'type' => 'varchar',
		),
		'NumPiece' => array(
			'type' => 'varchar',
		),
	);
	
	
	
}
