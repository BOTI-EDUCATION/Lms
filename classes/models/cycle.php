<?php

namespace Models;

class Cycle extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'cycles';
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
		'Icone' => array(
			'type' => 'varchar',
		),
	);


	public function getInscriptions($promotion = null)
	{
		if (!$promotion)
			$promotion = Promotion::promotion_actuelle();

		$inscriptions = array();

		if ($this->saved) {
			$inscriptions  = Inscription::all(
				array(
					'where' => array(
						'Depart IS Null',
						'`Validated` IS NOT NULL',
						'Promotion' => $promotion->get('ID'),
						'J1Cycle' => $this->ID
					),
					'order' => array('J2Nom' => true)
				),
				Inscription::sqlQuery() . <<<END
			JOIN (SELECT `ID` AS `J1ID`, `User` AS `J1User` FROM `gen_eleves`) AS `j1` ON `ins_inscriptions`.`Eleve` = `j1`.`J1ID`
			JOIN (SELECT `ID` AS `J2ID`, `Homme` AS `J2Homme`,`Nom` AS `J2Nom`,`Prenom` AS `J2Prenom` FROM `users`) AS `j2` ON `j1`.`J1User` = `j2`.`J2ID`
			JOIN (SELECT `ID` AS `J1ID`, `Cycle` AS `J1Cycle` FROM `gen_niveaux`) AS `j3` ON `ins_inscriptions`.`Niveau` = `j3`.`J1ID`
END
			);
		}
		return $inscriptions;
	}


	static function cyclesAlias($id)
	{
		$items =  [
			1 => 'maternelle',
			2 => 'primaire',
			3 => 'college',
			4 => 'lycee',
		];

		if ($id) {
			return isset($items[trim($id)]) ? $items[trim($id)] : null;
		}

		return  $items;
	}


	public static function getByAlias($alias) {
		$id = \DB::scallar('SELECT `ID` FROM '.static::wrapField(static::$table). ' WHERE `Alias`=?', array($alias));
		if (!$id)
			return null;
		return new self($id);
	}
}
