<?php
namespace Models;

class Periode extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'periodes';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Cycle' => array(
			'fk' => 'Cycle',
		),
		'Label' => array(
			'type' => 'varchar',
		),
		'JourDebut' => array(
			'type' => 'int',
		),
		'JourFin' => array(
			'type' => 'int',
		),
		'MoisDebut' => array(
			'type' => 'int',
		),
		'MoisFin' => array(
			'type' => 'int',
		),
	);
	
	public static function periodeActuelle() {
		
		// $periodes = Periode::getList(array('where' => array(
			// '(MoisDebut < MoisFin AND MoisDebut > 8 AND DATE(NOW()) BETWEEN CONCAT_WS('-', YEAR(CURDATE()),LPAD(`MoisDebut`, 2, 0),LPAD(`JourDebut`, 2, 0)) AND  CONCAT_WS('-', YEAR(CURDATE()),LPAD(`MoisFin`, 2, 0),LPAD(`JourFin`, 2, 0)))'.
			// ' OR (MoisDebut > MoisFin AND DATE(NOW()) BETWEEN CONCAT_WS('-', YEAR(CURDATE())+1,LPAD(`MoisDebut`, 2, 0),LPAD(`JourDebut`, 2, 0)) AND CONCAT_WS('-', YEAR(CURDATE())+1,LPAD(`MoisFin`, 2, 0),LPAD(`JourFin`, 2, 0)))'.
			// ' OR (MoisDebut > MoisFin AND DATE(NOW()) BETWEEN CONCAT_WS('-', YEAR(CURDATE()),LPAD(`MoisDebut`, 2, 0),LPAD(`JourDebut`, 2, 0)) AND CONCAT_WS('-', YEAR(CURDATE())+1,LPAD(`MoisFin`, 2, 0),LPAD(`JourFin`, 2, 0)))'
		// )));
		
		// if (!$periodes)
			// return null;
		
		// return $periodes[0];
		
		return new Periode(3);
	}
}
