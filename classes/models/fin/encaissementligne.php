<?php

namespace Models\FIN;

use Models\Model;

class EncaissementLigne extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'fnc_encaissementlignes';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);

	protected static $fields = array(
		'Encaissement' => array(
			'fk' => 'FIN\\Encaissement',
		),
		'Inscription' => array(
			'fk' => 'Inscription',
		),
		'Mois' => array(
			'type' => 'int',
		),
		'EncaissementRubrique' => array(
			'fk' => 'FIN\\EncaissementRubrique',
		),
		'Montant' => array(
			'type' => 'double',
		),
		'Date' => array(
			'type' => 'varchar',
		),
		'Comment' => array(
			'type' => 'varchar',
		),
		'Canceled' => array(
			'type' => 'text',
		),
	);


	public static function paiements($inscription)
	{

		$query = <<<ENDD
		SELECT `J2Inscription`, ( 
		CASE  
			WHEN `Mois` IS NOT NULL THEN `Mois`
			ELSE  `J1Mois`
		END 
		) AS Month , `EncaissementRubrique`, SUM(`Montant`) AS Montant FROM `fnc_encaissementlignes`

		JOIN (SELECT `ID` AS `J1ID`, `Label` AS `J1Label`, `Mois` AS `J1Mois` FROM `fnc_encaissementrubriques`) AS `j1` ON `fnc_encaissementlignes`.`EncaissementRubrique` = `j1`.`J1ID`
		
		JOIN (SELECT `ID` AS `J2ID`, `Inscription` AS `J2Inscription` FROM `fnc_encaissements`) AS `j2` ON `fnc_encaissementlignes`.`Encaissement` = `j2`.`J2ID`

		WHERE J2Inscription = ?

		GROUP BY `J2Inscription`, Month, `EncaissementRubrique`

ENDD;



		$params = array($inscription->get('ID'));



		$result = \DB::reader($query, $params);



		$response = array();



		foreach ($result as $data) {



			$response[$data['Month'] ? $data['Month'] : '9'][$data['EncaissementRubrique']] = array(

				'Montant' => $data['Montant'],

			);
		}



		return ($response);
	}


	public static function getECMontant($where)
	{
		$args = array();
		$query = '';

		if (isset($where['Promotion'])) {
			$query .= "(Inscription IN (SELECT `ID` FROM `ins_inscriptions` where  `Promotion`=" . $where['Promotion'] . ")) AND ";
			unset($where['Promotion']);
		}

		if (isset($where['Cycle'])) {
			$query .= "(Inscription IN (SELECT ID FROM ins_inscriptions where  Niveau IN (SELECT ID FROM gen_niveaux WHERE Cycle=" . $where['Cycle'] . " ))) AND ";
			unset($where['Cycle']);
		}

		if (isset($where['Niveau'])) {
			$query .= "(Inscription IN (SELECT ID FROM ins_inscriptions where  Niveau = " . $where['Niveau'] . " )) AND ";
			unset($where['Niveau']);
		}


		if (isset($where['Classe'])) {
			if ($where['Classe']) {
				$query .= "(Inscription IN (SELECT ID FROM ins_inscriptions where Classe = " . $where['Classe'] . " )) AND ";
			} else {
				$query .= "(Inscription IN (SELECT ID FROM ins_inscriptions where `Classe` IS NULL )) AND ";
			}
			unset($where['Classe']);
		}


		foreach ($where as $key => $value) {

			if ($value) {
				$args[] = $value;
				$query .= '`' . $key . '`= ? AND';
			}
		}

		$query = substr($query, 0, -4);
		$amount = \DB::scallar('SELECT SUM(Montant) as Amount FROM ' . static::wrapField(static::$table) . ' WHERE `Canceled` IS NULL AND ' . $query, $args);

		return $amount;
	}

	public static function getECNombre($where)
	{
		$args = array();
		$query = '';

		if (isset($where['Promotion'])) {
			$query .= "(Inscription IN (SELECT `ID` FROM `ins_inscriptions` where `Promotion`=" . $where['Promotion'] . ")) AND ";
			unset($where['Promotion']);
		}

		if (isset($where['Cycle'])) {
			$query .= "(Inscription IN (SELECT ID FROM ins_inscriptions where Niveau IN (SELECT ID FROM gen_niveaux WHERE Cycle=" . $where['Cycle'] . " ))) AND ";
			unset($where['Cycle']);
		}

		if (isset($where['Niveau'])) {
			$query .= "(Inscription IN (SELECT ID FROM ins_inscriptions where Niveau = " . $where['Niveau'] . " )) AND ";
			unset($where['Niveau']);
		}

		if (isset($where['Classe'])) {
			if ($where['Classe']) {
				$query .= "(Inscription IN (SELECT ID FROM ins_inscriptions where Classe = " . $where['Classe'] . " )) AND ";
			} else {
				$query .= "(Inscription IN (SELECT ID FROM ins_inscriptions where Classe is NULL )) AND ";
			}
			unset($where['Classe']);
		}

		foreach ($where as $key => $value) {
			if ($value) {
				$args[] = $value;
				$query .= '`' . $key . '`= ? AND ';
			}
		}

		$query = substr($query, 0, -4);
		$count = \DB::scallar('SELECT COUNT(DISTINCT Inscription) as Nombre  FROM ' . static::wrapField(static::$table) . ' WHERE ' . $query, $args);

		return $count;
	}

	public static function  getLigne($inscription, $service, $month = null, $canceled = true)
	{

		$where = [
			'Inscription' => $inscription->ID,
			'EncaissementRubrique' => $service->ID,
		];

		if ($month) {
			$where['Mois'] = $month;
		}

		if (!$canceled) {
			$where[] = 'Canceled IS NULL';
		}

		$lignes = self::getList(['where' => $where]);

		return isset($lignes[0]) ? $lignes[0] : null;
	}
}
