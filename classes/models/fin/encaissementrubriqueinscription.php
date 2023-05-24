<?php

namespace Models\FIN;

use Models\Model;

class EncaissementRubriqueInscription extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'fnc_encaissementinscriptions';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Inscription' => array(
			'fk' => 'Inscription',
		),
		'EncaissementRubrique' => array(
			'fk' => 'FIN\\EncaissementRubrique',
		),
		'Montant' => array(
			'type' => 'double',
		),
		'Discounts' => array(
			'type' => 'Discounts',
		),
		'DiscountsAmount' => array(
			'type' => 'double',
		),
		'Remarques' => array(
			'type' => 'text',
		),
		'DateAjout' => array(
			'type' => 'datetime',
		),
		'User' => array(
			'fk' => 'User',
		),
		'Mois' => array(
			'type' => 'int'
		),
		// added
		'Eleve' => array(
			'fk' => 'Eleve'
		),
		'Promotion' => array(
			'fk' => 'Promotion'
		),
		'Classe' => array(
			'type' => 'varchar'
		),
		'Niveau' => array(
			'type' => 'varchar'
		),
		'ChangeHistory' => array(
			'type' => 'text'
		),
		'MontantEncaisse' => array(
			'type' => 'text'
		),
		'Encaissements' => array(
			'type' => 'text'
		),
		'DetailsEncaissements' => array(
			'type' => 'text'
		),

	);


	public function beforeSave()
	{
		$history = $this->getArray('ChangeHistory') ?: array();
		$history[] = array(
			'user' => \Session::getInstance()->getCurUser()->ID,
			'action' => $this->saved ? 'update' : 'add',
			'date' => date('Y-m-d H:i:s'),
		);
		$this->setJson('ChangeHistory', $history);
	}



	public static function getCAMontant($where)
	{
		$args = array();
		$query = '';

		if (isset($where['Promotion'])) {
			$query .= "(Inscription IN (SELECT `ID` FROM `ins_inscriptions` where  `Promotion`=" . $where['Promotion'] . ")) AND ";
			unset($where['Promotion']);
		}

		if (isset($where['Cycle'])) {
			$query .= "(Inscription IN (SELECT `ID` FROM `ins_inscriptions` where  `Niveau` IN (SELECT ID FROM gen_niveaux WHERE Cycle=" . $where['Cycle'] . " ))) AND ";
			unset($where['Cycle']);
		}

		if (isset($where['Niveau'])) {
			$query .= "(Inscription IN (SELECT `ID` FROM `ins_inscriptions` where  `Niveau` = " . $where['Niveau'] . " )) AND ";
			unset($where['Niveau']);
		}

		if (isset($where['Classe'])) {
			if ($where['Classe']) {
				$query .= "(`Inscription` IN (SELECT `ID` FROM `ins_inscriptions` where `Classe` = " . $where['Classe'] . " )) AND ";
			} else {
				$query .= "(`Inscription` IN (SELECT `ID` FROM `ins_inscriptions` where `Classe` is NULL )) AND ";
			}
			unset($where['Classe']);
		}

		foreach ($where as $key => $value) {
			if ($value) {
				$args[] = $value;
				$query .= '`' . $key . '` = ? AND ';
			}
		}

		$query = substr($query, 0, -4);

		$amount = \DB::scallar('SELECT SUM(Montant) as Amount FROM ' . static::wrapField(static::$table) . ' WHERE ' . $query, $args);

		return $amount;
	}

	public static function getCANombre($where)
	{
		$args = array();
		$query = '';



		if (isset($where['Promotion'])) {
			$query .= "(Inscription IN (SELECT `ID` FROM `ins_inscriptions` where `Promotion`=" . $where['Promotion'] . ")) AND ";
			unset($where['Promotion']);
		}

		if (isset($where['Cycle'])) {
			$query .= "(Inscription IN (SELECT ID FROM ins_inscriptions where  `Niveau` IN (SELECT ID FROM gen_niveaux WHERE Cycle=" . $where['Cycle'] . " ))) AND ";
			unset($where['Cycle']);
		}

		if (isset($where['Niveau'])) {
			$query .= "(Inscription IN (SELECT ID FROM ins_inscriptions where `Niveau` = " . $where['Niveau'] . " )) AND ";
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
				$query .= '`' . $key . '` = ? AND ';
			}
		}

		$query = substr($query, 0, -4);

		$count = \DB::scallar('SELECT COUNT(DISTINCT Inscription) as Nombre FROM ' . static::wrapField(static::$table) . ' WHERE ' . $query, $args);

		return $count;
	}


	public static function rubriques($inscription, $month = null)
	{

		$inscription_id =  $inscription->get('ID');
		$rubriques = array();

		$where = array(
			'J1Inscription' => $inscription_id,
			// '(Sites  LIKE \'%"' . $inscription->getSite()->getPk(true) . '"%\')'
		);

		if ($month !== null) {
			$where['J1Mois'] = $month;
		}

		$rubriques = EncaissementRubrique::getList(
			array('where' => $where),
			EncaissementRubrique::sqlQuery(true) . <<<END
		JOIN (SELECT `EncaissementRubrique` AS `J1EncaissementRubrique` , Inscription AS J1Inscription, Mois AS J1Mois  FROM `fnc_encaissementinscriptions`) AS `j1` ON `fnc_encaissementrubriques`.`ID` = `j1`.`J1EncaissementRubrique`
END
		);

		return $rubriques;
	}

	public static function inscriptionHasRubrique($inscription, $service, $month = null)
	{
		$where = array(
			'EncaissementRubrique' => $service->get('ID'),
			'Inscription' => $inscription->get('ID'),
		);

		if ($month) {
			$where['Mois'] =  $month;
		}

		$items = EncaissementRubriqueInscription::getList(array('where' => $where));
		return count($items) ? $items[0] : null;
	}

	public static function inscriptionHasPayedRubrique($inscription, $service, $month = null)
	{


		// fnc_encaissementlignes
		$query = "SELECT SUM(`Montant`) AS Montant FROM `fnc_encaissementlignes` WHERE `Canceled` IS NULL AND EncaissementRubrique = ? AND Inscription = ?";

		$params = array($service->get('ID'), $inscription->get('ID'),);

		if ($month) {
			$query .= " AND Mois = ? ";
			$params[] =  $month;
		}


		$montant_payed = \DB::scallar($query, $params) ?: 0;


		// fnc_encaissementinscriptions
		$query = "SELECT SUM(`Montant`) AS Montant FROM `fnc_encaissementinscriptions` WHERE EncaissementRubrique = ? AND Inscription = ?";
		$params = array($service->get('ID'), $inscription->get('ID'),);

		if ($month) {
			$query .= " AND Mois = ? ";
			$params[] =  $month;
		}

		$montant = \DB::scallar($query, $params) ?: 0;

		return $montant  == $montant_payed;
	}


	public  static function totalDefaultAmount($inscription)
	{
		$query = "SELECT SUM(`Montant`) AS Montant FROM `fnc_encaissementinscriptions` " . " WHERE Inscription = ?";
		$params = array($inscription->get('ID'));
		return \DB::scallar($query, $params);
	}

	public  static function totalAmountPayed($inscription)
	{
		$query = "SELECT SUM(`Montant`) AS Montant FROM `fnc_encaissementlignes`" . "WHERE `Canceled` IS NULL AND Inscription = ?";
		$params = array($inscription->get('ID'));
		return \DB::scallar($query, $params);
	}


	public  static function defaultAmountOfRubriques($inscription, $month, $rubrique = null)
	{

		if ($rubrique) {
			$inscriptionRubrique = self::inscriptionHasRubrique($inscription, $rubrique, $month);
			return $inscriptionRubrique ? $inscriptionRubrique->get('Montant') : 0;
		}

		$total = 0;
		foreach (self::rubriques($inscription, $month) as $rubrique) {
			$inscriptionRubrique =  self::inscriptionHasRubrique($inscription, $rubrique, $month);
			if ($inscriptionRubrique) {
				$total  += $inscriptionRubrique->get('Montant');
			}
		}

		return $total;
	}

	public static function sumAmountOfRubriques($inscription, $month = null, $rubrique = null, $paiement_mode = null)
	{
		$inscription_id = $inscription->get('ID');

		$query = "SELECT SUM(Montant) as Amount from fnc_encaissementlignes where `Canceled` IS NULL AND Inscription = $inscription_id";

		if ($rubrique) {
			$rubrique  =  $rubrique->get('ID');
			$query .= " and EncaissementRubrique = $rubrique ";
		}

		if (!is_null($month)) {
			$query .= " and Mois = $month";
		}

		if ($paiement_mode) {
			$query .= " AND Encaissement IN(select ID from fnc_encaissements where PaiementMode = '$paiement_mode') ";
		}
		$result = \DB::reader($query);

		$amount = isset($result[0]) ? ($result[0]['Amount'] ? $result[0]['Amount'] : 0) : 0;

		return $amount;
	}

	public static function etatEncaisementServices($inscription)
	{
		$months_list = $inscription->Promotion->months()->zero_list;
		$serviceMonths = array();
		foreach ($months_list as $key_month => $month) {
			$services = array();
			foreach (self::rubriques($inscription, $key_month) as $s) {
				$services[] = (object)array(
					'service' => $s,
				);
			}
			$serviceMonths[$key_month] = (object)array(
				'month' => $month,
				'services' => $services,
			);
		}

		return $serviceMonths;
	}

	public static function inscriptionHasRubriqueInMonths($inscription, $months)
	{
		$items = EncaissementRubriqueInscription::getList(array('where' => array('Inscription' => $inscription->ID, 'Mois IN(' . implode(',', $months) . ')')));
		return !!count($items);
	}
}
