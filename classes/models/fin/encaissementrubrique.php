<?php

namespace Models\FIN;

use Models\Inscription;
use \Models\Model;

class EncaissementRubrique extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'fnc_encaissementrubriques';
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
		'Promotion' => array(
			'fk' => 'Promotion',
		),
		'Sites' => array(
			'type' => 'text',
		),
		'Discounts' => array(
			'type' => 'varchar',
		),
		'Echeances' => array(
			'type' => 'varchar',
		),
		'Modalites' => array(
			'type' => 'text',
		),
		'Frequency' => array(
			'type' => 'text',
		),
		'Optionnel' => array(
			'type' => 'boolean',
		),
		'Function' => array(
			'type' => 'text',
		),
		'Icone' => array(
			'type' => 'varchar',
		),
		'Mensuel' => array(
			'type' => 'boolean',
		),
		'Mois' => array(
			'type' => 'int',
		),
		'MontantDefaut' => array(
			'type' => 'double',
		),
		'Enabled' => array(
			'type' => 'boolean',
		),
		'Parascolaire' => array(
			'type' => 'boolean',
		),
		'Remarques' => array(
			'type' => 'varchar',
		),
		'EditHistory' => array(
			'type' => 'text',
		),
	);


	public function beforeSave()
	{
		$history = $this->getArray('EditHistory') ?: array();
		$history[] = array(
			'user' => \Session::getInstance()->getCurUser()->ID,
			'action' => $this->saved ? 'update' : 'add',
			'date' => date('Y-m-d H:i:s'),
		);
		$this->setJson('EditHistory', $history);
	}


	static function annulesService()
	{
		return parent::getList(array('where' => array('Mensuel' => false)));
	}


	static function mensuelServices()
	{
		return parent::getList(array('where' => array('Mensuel' => true)));
	}


	public static function total($promotion, $month = null, $site =  null)
	{
		$query = "SELECT SUM(Montant) as Amount FROM fnc_encaissementinscriptions WHERE  `Inscription` IN (SELECT `ID` FROM `ins_inscriptions` WHERE " . ($site ? " `Site` = '" . $site->getKey() . "' AND " : "") . " `Depart` IS NULL AND `Promotion` = " . $promotion->get('ID') . ") AND `Mois` = $month";
		// echo $query;
		$result = \DB::reader($query);

		$amount = isset($result[0]) ? ($result[0]['Amount'] ? $result[0]['Amount'] : 0) : 0;

		return $amount;
	}


	public function taken_by_inscriptions($promotion, $month = null, $site =  null)
	{
		$service  =  $this->get('ID');

		$inscriptions = Inscription::getList(array('where' => array("`Depart` IS NULL AND `ID` IN (SELECT `Inscription` FROM `fnc_encaissementinscriptions` WHERE  `EncaissementRubrique` = $service " . (!is_null($month) ? "AND `Mois` = $month" : '') . " ) AND " . ($site ? " `Site` = '" . $site->getKey() . "' AND " : "") . " `Promotion` = " . $promotion->get('ID'))));

		return $inscriptions;
	}

	public function countPayedInscriptions($promotion, $month = null, $site = null)
	{
		$service  =  $this->get('ID');
		$query = "SELECT COUNT(DISTINCT Inscription) AS Count FROM `fnc_encaissementlignes` WHERE `Canceled` IS NULL AND `EncaissementRubrique` = $service " . (!is_null($month) ? "AND Mois = $month" : '')  . " AND Inscription in (SELECT ID FROM  ins_inscriptions where " . ($site ? " `Site` = '" . $site->getKey() . "' AND " : "") . " Depart IS NULL AND Promotion = " . $promotion->get('ID') . ")";
		$result = \DB::reader($query);

		$count = isset($result[0]) ? ($result[0]['Count'] ? $result[0]['Count'] : 0) : 0;
		return $count;
	}


	public function  defaultAmount($promotion, $month = null, $site = null)
	{

		$service  =  $this->get('ID');

		$query = "SELECT SUM(Montant) as Amount FROM `fnc_encaissementinscriptions` WHERE  `EncaissementRubrique` = $service " . (!is_null($month) ? "AND Mois = $month" : '')  . " AND Inscription in (SELECT ID FROM  ins_inscriptions  WHERE " . ($site ? " `Site` = '" . $site->getKey() . "' AND " : "") . " Depart IS NULL AND Promotion = " . $promotion->get('ID') . ")";

		$result = \DB::reader($query);

		$amount = isset($result[0]) ? ($result[0]['Amount'] ? $result[0]['Amount'] : 0) : 0;
		return $amount;
	}

	public function  countInscriptions($promotion, $month = null, $site =  null)
	{

		$service  =  $this->get('ID');

		$query = "SELECT COUNT(DISTINCT Inscription) as Nobmre FROM fnc_encaissementinscriptions WHERE `Montant` != 0 AND `EncaissementRubrique` = $service " . (!is_null($month) ? " AND Mois = $month" : '')  . " AND Inscription in (SELECT ID FROM  ins_inscriptions WHERE " . ($site ? " `Site` = '" . $site->getKey() . "' AND " : "") . " Depart IS NULL AND Promotion = " . $promotion->get('ID') . ")";

		$result = \DB::reader($query);

		$count = isset($result[0]) ? ($result[0]['Nobmre'] ? $result[0]['Nobmre'] : 0) : 0;
		return $count;
	}

	public function totalAmount($promotion, $month = null, $site =  null)
	{
		$service  =  $this->get('ID');
		$query = "SELECT SUM(Montant) as Amount FROM fnc_encaissementlignes WHERE Canceled IS NULL AND EncaissementRubrique = $service " . (!is_null($month) ? " AND Mois = $month" : '') . " AND Inscription in (SELECT ID FROM  ins_inscriptions WHERE  " . ($site ? " `Site` = '" . $site->getKey() . "' AND " : "") . " Depart IS NULL AND Promotion = " . $promotion->get('ID') . ")";
		// echo $query;

		$result = \DB::reader($query);

		$amount = isset($result[0]) ? ($result[0]['Amount'] ? $result[0]['Amount'] : 0) : 0;

		return $amount;
	}

	// SELECT SUM(Montant) FROM fnc_encaissementlignes WHERE EncaissementRubrique = 1 AND Inscription IN (SELECT ID FROM ins_inscriptions WHERE Promotion = 7) AND Inscription IN (SELECT Inscription FROM fnc_encaissementinscriptions  WHERE EncaissementRubrique = 1)

	public function  countInscriptiuonsGratuits($promotion, $month = null, $site =  null)
	{

		$service  =  $this->get('ID');

		$query = "SELECT COUNT(DISTINCT Inscription) as Nobmre FROM fnc_encaissementinscriptions WHERE Montant = 0 AND EncaissementRubrique = $service " . (!is_null($month) ? " AND Mois = $month" : '')  . " AND Inscription in (SELECT ID FROM  ins_inscriptions WHERE " . ($site ? " `Site` = '" . $site->getKey() . "' AND " : "") . " Depart IS NULL AND Promotion = " . $promotion->get('ID') . ")";

		$result = \DB::reader($query);

		$count = isset($result[0]) ? ($result[0]['Nobmre'] ? $result[0]['Nobmre'] : 0) : 0;
		return $count;
	}


	public function _unpaidInscriptions($promotion, $month = null, $niveau = null, $select_pre_month = null)
	{
		$service  =  $this->get('ID');
		$niveau   =  $niveau ? $niveau->get('ID') : null;
		$inscriptions = Inscription::getList(array('where' => array(($niveau ? "Niveau = $niveau AND " : "") . "ID IN (SELECT Inscription  FROM fnc_encaissementinscriptions WHERE  Montant != 0 AND EncaissementRubrique = $service " . (!is_null($month) ? "AND Mois = $month" : '') . ") AND ID NOT IN (SELECT Inscription FROM fnc_encaissementlignes WHERE Canceled IS NULL AND EncaissementRubrique = $service " . (!is_null($month) ? " AND Mois = $month" : '') . " ) AND Promotion = " . $promotion->get('ID'))));

		return $inscriptions;
	}

	public static function unpaidInscriptions($promotion, $service = null, $month = null, $niveau = null, $site = null, $select_pre_month = null)
	{
		$service  =  $service ? $service->get('ID') : null;
		$niveau   =  $niveau ? $niveau->get('ID') : null;
		$site   =  $site ? $site->getPk(true) : null;

		$site_query = $site ?  " AND (`Site` like '" . $site . "')" : " AND `Site` IS NOT NULL";
		$query =  "Depart IS NULL AND "  . ($niveau ? "`Niveau` = $niveau AND " : "") . "  `Promotion` = " . $promotion->get('ID') . " " . $site_query . " AND ";

		// $query =  "Depart IS NULL AND " . ($niveau ? "`Niveau` = $niveau AND " : "") . "  `Promotion` = " . $promotion->get('ID') . " AND ";
		$months_list = _zero_months_list;
		if (!is_null($month)) {
			$month_query = array();
			$month_query[] = " (`ID` IN (SELECT `Inscription`  FROM `fnc_encaissementinscriptions` WHERE  `Montant` != 0 AND " . ($service ? "`EncaissementRubrique` = $service AND " : ' ') . " `Mois` = $month  AND `Inscription` NOT IN (SELECT `Inscription` FROM `fnc_encaissementlignes`  WHERE `fnc_encaissementlignes`.`Montant` =  `fnc_encaissementinscriptions`.`Montant` AND Canceled IS NULL AND " . ($service ? "`EncaissementRubrique` = $service AND " : ' ') . " `Mois` = $month )) ) ";

			if ($select_pre_month) {
				foreach ($months_list as $key_month => $_month) {
					if ($key_month > $month) {
						break;
					}
					$month_query[] = " ( `ID` IN (SELECT `Inscription`  FROM `fnc_encaissementinscriptions` WHERE  `Montant` != 0 AND " . ($service ? "`EncaissementRubrique` = $service AND " : ' ') . "  `Mois` = $key_month  AND `Inscription` NOT IN (SELECT `Inscription` FROM `fnc_encaissementlignes` WHERE `fnc_encaissementlignes`.`Montant` =  `fnc_encaissementinscriptions`.`Montant` AND Canceled IS NULL AND " . ($service ? "`EncaissementRubrique` = $service AND " : ' ') . " `Mois` = $key_month )) ) ";
				}
			}
			$query .= " ( " . implode(' OR ', $month_query) . " )";
		} else {
			$month_query = array();

			foreach ($months_list as $key_month => $_month) {
				$month_query[] = " ( `ID` IN (SELECT `Inscription`  FROM `fnc_encaissementinscriptions` WHERE  `Montant` != 0 AND " . ($service ? "`EncaissementRubrique` = $service AND " : ' ') . "  `Mois` = $key_month  AND `Inscription` NOT IN (SELECT `Inscription` FROM `fnc_encaissementlignes` WHERE `fnc_encaissementlignes`.`Montant` =  `fnc_encaissementinscriptions`.`Montant` AND Canceled IS NULL AND " . ($service ? "`EncaissementRubrique` = $service AND " : ' ') . " `Mois` = $key_month )) ) ";
			}

			$query .= " ( " . implode(' OR ', $month_query) . " )";
		}

		return Inscription::where(array($query));
	}


	public function simpleTokens($with_ids = false)
	{
		$tokens = array();

		$parrainages = \Models\Parrainage::getList(
			array('where' => array('J3EncaissementRubrique' => $this->get('ID'))),
			\Models\Parrainage::sqlQuery() . <<<END
		JOIN (SELECT `ID` AS `J1ID` FROM `gen_eleves`) AS `j1` ON `parrainages`.`Eleve` = `j1`.`J1ID`
		JOIN (SELECT `ID` AS `J2ID`, `Eleve` AS `J2Eleve`, `Classe` AS `J2Classe` FROM `ins_inscriptions`) AS `j2` ON `j1`.`J1ID` = `j2`.`J2Eleve`
		JOIN (SELECT `ID` AS `J3ID`, `EncaissementRubrique` AS `J3EncaissementRubrique` ,`Inscription` AS `J3Inscription` FROM `fnc_encaissementinscriptions`) AS `j3` ON `j2`.`J2ID` = `j3`.`J3Inscription`
END
		);

		foreach ($parrainages as $item) {
			$user = $item->get('Parent')->get('User');
			$ids[] = $user->get('ID');
			if ($user_tokens = $user->getArray('TokenID', true))
				foreach ($user_tokens as  $token) {
					$tokens[] = $token;
				}
		}

		if ($with_ids) {
			return ['ids' => $ids, 'tokens' => $tokens];
		}
		return $tokens;
	}

	public function tokens()
	{
		$tokens = array(
			'ios' => array(),
			'android' => array(),
		);

		$parrainages = \Models\Parrainage::getList(
			array('where' => array('J3EncaissementRubrique' => $this->get('ID'))),
			\Models\Parrainage::sqlQuery() . <<<END
		JOIN (SELECT `ID` AS `J1ID` FROM `gen_eleves`) AS `j1` ON `parrainages`.`Eleve` = `j1`.`J1ID`
		JOIN (SELECT `ID` AS `J2ID`, `Eleve` AS `J2Eleve`, `Classe` AS `J2Classe` FROM `ins_inscriptions`) AS `j2` ON `j1`.`J1ID` = `j2`.`J2Eleve`
		JOIN (SELECT `ID` AS `J3ID`, `EncaissementRubrique` AS `J3EncaissementRubrique` ,`Inscription` AS `J3Inscription` FROM `fnc_encaissementinscriptions`) AS `j3` ON `j2`.`J2ID` = `j3`.`J3Inscription`
END
		);

		foreach ($parrainages as $item) {

			if ($item->get('Parent')->get('User')->get('TokenID'))
				$tokens[$item->get('Parent')->get('User')->get('TokenDevice')][] = $item->get('Parent')->get('User')->get('TokenID');
		}
		return $tokens;
	}


	public function phones()
	{
		$phones = array();

		$parrainages = \Models\Parrainage::getList(
			array('where' => array('J3EncaissementRubrique' => $this->get('ID'))),
			\Models\Parrainage::sqlQuery() . <<<END
		JOIN (SELECT `ID` AS `J1ID` FROM `gen_eleves`) AS `j1` ON `parrainages`.`Eleve` = `j1`.`J1ID`
		JOIN (SELECT `ID` AS `J2ID`, `Eleve` AS `J2Eleve`, `Classe` AS `J2Classe` FROM `ins_inscriptions`) AS `j2` ON `j1`.`J1ID` = `j2`.`J2Eleve`
		JOIN (SELECT `ID` AS `J3ID`, `EncaissementRubrique` AS `J3EncaissementRubrique` ,`Inscription` AS `J3Inscription` FROM `fnc_encaissementinscriptions`) AS `j3` ON `j2`.`J2ID` = `j3`.`J3Inscription`
END
		);

		foreach ($parrainages as $item) {
			if ($item->get('Parent')->get('User')->get('Tel'))
				$phones[] = $item->get('Parent')->get('User')->get('Tel');
		}
		return $phones;
	}



	public static function services_taken_by_inscriptions($promotion, $service, $month)
	{
		$inscriptions = array();
		if ($service && $month) {
			$items = Inscription::getList(array('where' => array("ID IN (SELECT Inscription FROM fnc_encaissementinscriptions WHERE EncaissementRubrique IN ($service)" . (!is_null($month) ? "AND Mois = $month" : '') . " ) AND Promotion = " . $promotion->get('ID'))));
			foreach ($items as $key => $item) {
				if (!$item->get('Classe')) {
					continue;
				}
				if ($item->get('Classe') && !isset($inscriptions[$item->get('Classe')->ID])) {
					$inscriptions[$item->get('Classe')->ID] = array();
				}

				$inscriptions[$item->get('Classe')->ID][] = $item;
			}
		}
		return $inscriptions;
	}

	// added
	public function getAmount($grille, $niveau, $inscription)
	{
		$amount = $this->get('MontantDefaut');
		if (isset($grille[$niveau->get('ID')]) && isset($grille[$niveau->get('ID')][$this->get('ID')])) {
			$grilleRubriquePrice = $grille[$niveau->get('ID')][$this->get('ID')];
		} else {
			$grilleRubriquePrice = RubriquePrice::rubriquePrice($niveau, $this, $inscription->Promotion, $inscription->Site);
		}
		if ($grilleRubriquePrice) {
			$amount = $grilleRubriquePrice->get('Frais');
		}
		return $amount;
	}


	public function getGrilleAmount($promotion, $niveau, $site)
	{
		$rubriquePrice =  RubriquePrice::rubriquePrice($niveau, $this, $promotion, $site);
		return $rubriquePrice->Frais;
	}


	public static  function frequencyOptions($item_alias = null)
	{
		$items  =  [
			'monthly' => 'Mensuel',
			'yearly' => 'Annuel',
			'onetime' => 'A l’entrée',
		];
		if ($item_alias) {
			return $items[$item_alias] ?? 'Mensuel';
		}
		return $items;
	}

	public static  function remisesOptions($item_alias = null)
	{
		$items  =  [
			'fratrie' => 'Fratrie',
			'1_yeay' => '1ère année',
		];

		if ($item_alias) {
			return $items[$item_alias] ?? 'Mensuel';
		}
		return $items;
	}

	public static  function modalitesOptions($item_alias = null)
	{
		$items  =  [
			'fratrie' => 'Fratrie',
			'1_yeay' => '1ère année',
		];

		if ($item_alias) {
			return $items[$item_alias] ?? 'Mensuel';
		}
		return $items;
	}

	public function frequency()
	{
		return  $this->getArray('frequency') ?: [];
	}


	public function modalites()
	{
		return  $this->getArray('Modalites') ?: [];
	}

	public function sites()
	{
		return  $this->getArray('Sites') ?: [];
	}

	public function getIcone()
	{
		$url =  $this->get('Icone')  ? \GoogleStorage::getUrl(\Config::get('path-encaissements') . 'services/'  . $this->get('Icone')) : null;

		if (!$url) {
			return 'https://cdn-icons-png.flaticon.com/512/7514/7514355.png';
		}
		return $url;
	}


	public function discounts()
	{

		return  \Models\FIN\DiscountBase::query()->whereIn('ID', $this->get('Discounts') ? array_keys($this->getArray('Discounts', false, true)) : [])->get();
	}

	public function calculateDiscount($discountType, $amount)
	{

		if (isset($discountType['typeValue'])) {
			if ($discountType['typeValue'] == 'percent') {
				return ($amount  * ($discountType['value'] / 100));
			}
			return $discountType['value'];
		}

		return ($amount  * ($discountType['percent'] / 100));
	}

	public function echeances($promotion)
	{
		$echeances =  $this->getArray('Echeances', false, true);
		$echeances =  isset($echeances[$promotion->getKey()]) ? $echeances[$promotion->getKey()] : [];
		$result = [];
		foreach ($echeances as $month => $percent) {
			$result[] = (object)[
				'percent' => $percent,
				'month' => $month,
			];
		}
		return $result;
	}

	public static function getAdmissionService()
	{

		return self::where(['Function' => 'admission'])->first();
	}
}
