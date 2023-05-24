<?php

namespace Models;

class Niveau extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'gen_niveaux';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'NextNiveaux' => array(
			'type' => 'varchar',
		),
		'TypesEvaluation' => array(
			'type' => 'varchar',
		),
		'Cycle' => array(
			'fk' => 'Cycle',
		),
		'Code' => array(
			'type' => 'varchar',
		),
		'Label' => array(
			'type' => 'varchar',
		),
		'LabelAr' => array(
			'type' => 'varchar',
		),
		'Ordre' => array(
			'type' => 'int',
		),
		'Enabled' => array(
			'type' => 'boolean',
		),
	);

	// public static function getList($args=null,$query=null) {
	//     if (!is_array($args)) $args = array();
	//     $args['where'][] = '(Deleted IS NULL)';
	//     return parent::getList($args, $query);
	// }

	public static function getByCode($code)
	{
		$items = Niveau::getList(array('where' => array('Code' => $code)));
		return count($items) ? $items[0] : null;
	}


	public function getClasses()
	{
		$classes = array();
		$items = Classe::getList(array('where' => array('Niveau' => $this->get('ID'), 'Promotion' => $_SESSION['promotion_actuelle'])));
		if (!$items)
			return array();
		foreach ($items as $item)
			$classes[$item->get('Niveau')->get('ID')] = $item;
		return $classes;
	}

	public function getEvaluationsClasses()
	{
		$classes = array();
		$classes = Classe::getList(array('where' => array('Niveau' => $this->get('ID'), 'Promotion' => $_SESSION['promotion_actuelle'])));
		return $classes;
	}

	public function nextNiveau()
	{
		$items = null;
		if ($this->get('Ordre'))
			$items = Niveau::getList(array('where' => array('Ordre > ' . $this->get('Ordre'), 'Enabled' => true), 'order' => array('Ordre' => true)));

		if (!$items)
			return null;
		return $items[0];
	}

	public function ifLastNiveau()
	{
		if ($this->get('Ordre')) {
			$items = Niveau::getCount(array('where' => array('Ordre > ' . $this->get('Ordre')), 'order' => array('Ordre' => true)));
			if ($items > 0)
				return false;
		}
		return true;
	}

	public function getCountElevesNonAffectes($promotion = null)
	{
		if (!$promotion)
			$promotion = Promotion::promotion_actuelle();
		return   Inscription::getCount(array('where' => array('Niveau' => $this->get('ID'), 'Promotion' => $promotion->get('ID'), 'Classe IS NULL')));
	}
	public function getCountClasses($promotion = null)
	{
		if (!$promotion)
			$promotion = Promotion::promotion_actuelle();
		return   Classe::getCount(array('where' => array('Niveau' => $this->get('ID'), 'Promotion' => $promotion->get('ID'))));
	}
	public function getCountEleves($promotion = null)
	{
		if (!$promotion)
			$promotion = Promotion::promotion_actuelle();
		return  Inscription::getCount(array('where' => array('Niveau' => $this->get('ID'), 'Promotion' => $promotion->get('ID'))));
	}
	public function getElevesNonAffectes($promotion = null)
	{
		if (!$promotion)
			$promotion = Promotion::promotion_actuelle();
		return  Inscription::getAll(array('where' => array('Niveau' => $this->get('ID'), 'Promotion' => $promotion->get('ID'),  'Classe IS NULL')));
	}

	public function _getInscriptions($promotion = null)
	{
		if (!$promotion)
			$promotion = Promotion::promotion_actuelle();
		return  Inscription::getAll(array('where' => array('Niveau' => $this->get('ID'), 'Promotion' => $promotion->get('ID')), 'order' => array('Ordre' => true)));
	}

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
						'Niveau' => $this->get('ID'),
						'Promotion' => $promotion->get('ID')
					), 'order' => array('J2Nom' => true)
				),
				Inscription::sqlQuery() . <<<END
			JOIN (SELECT `ID` AS `J1ID`, `User` AS `J1User` FROM `gen_eleves`) AS `j1` ON `ins_inscriptions`.`Eleve` = `j1`.`J1ID`
			JOIN (SELECT `ID` AS `J2ID`, `Homme` AS `J2Homme`,`Nom` AS `J2Nom`,`Prenom` AS `J2Prenom` FROM `users`) AS `j2` ON `j1`.`J1User` = `j2`.`J2ID`
END

			);
		}
		return $inscriptions;
	}


	public function tokens()
	{

		$promotion = Promotion::promotion_actuelle();

		$tokens = array(
			'ios' => array(),
			'android' => array(),
		);

		$parrainages = Parrainage::getList(
			array('where' => array('J2Niveau' => $this->get('ID'), 'J2Promotion' => $promotion->get('ID'))),
			Parrainage::sqlQuery() . <<<END
		JOIN (SELECT `ID` AS `J1ID` FROM `gen_eleves`) AS `j1` ON `parrainages`.`Eleve` = `j1`.`J1ID`
		JOIN (SELECT `ID` AS `J2ID`, `Eleve` AS `J2Eleve`, `Niveau` AS `J2Niveau`, `Promotion` AS `J2Promotion` FROM `ins_inscriptions`) AS `j2` ON `j1`.`J1ID` = `j2`.`J2Eleve`
END
		);

		foreach ($parrainages as $item) {

			if ($item->get('Parent')->get('User')->get('TokenID'))
				$tokens[$item->get('Parent')->get('User')->get('TokenDevice')][] = $item->get('Parent')->get('User')->get('TokenID');
		}
		return $tokens;
	}

	public function simpleTokens($with_ids = false, $site =  null)
	{
		$promotion = Promotion::promotion_actuelle();

		$tokens = array();
		$ids = array();

		$where =  array(
			'J2Depart IS NULL',
			'J2Niveau' => $this->get('ID'),
			'J2Promotion' => $promotion->get('ID')
		);

		if ($site) {
			$where['J2Site'] = $site->getKey();
		}
		$parrainages = Parrainage::getList(
			array('where' => $where),
			Parrainage::sqlQuery() . <<<END
		JOIN (SELECT `ID` AS `J1ID` FROM `gen_eleves`) AS `j1` ON `parrainages`.`Eleve` = `j1`.`J1ID`
		JOIN (SELECT `ID` AS `J2ID`, `Eleve` AS `J2Eleve`,  `Site` AS `J2Site`,`Niveau` AS `J2Niveau`,`Depart` AS `J2Depart`, `Promotion` AS `J2Promotion` FROM `ins_inscriptions`) AS `j2` ON `j1`.`J1ID` = `j2`.`J2Eleve`
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


	public function phones()
	{

		$promotion = Promotion::promotion_actuelle();
		$phones = array();
		$parrainages = Parrainage::getList(
			array('where' => array(
				'J2Depart IS NULL',
				'J2Niveau' => $this->get('ID'),
				'J2Promotion' => $promotion->get('ID')
			)),
			Parrainage::sqlQuery() . <<<END
		JOIN (SELECT `ID` AS `J1ID` FROM `gen_eleves`) AS `j1` ON `parrainages`.`Eleve` = `j1`.`J1ID`
		JOIN (SELECT `ID` AS `J2ID`, `Eleve` AS `J2Eleve`, `Niveau` AS `J2Niveau`,`Depart` AS `J2Depart`, `Promotion` AS `J2Promotion` FROM `ins_inscriptions`) AS `j2` ON `j1`.`J1ID` = `j2`.`J2Eleve`
END
		);

		foreach ($parrainages as $item) {

			if ($item->get('Parent')->get('User')->get('Tel'))
				$phones[] = $item->get('Parent')->get('User')->get('Tel');
		}
		return $phones;
	}


	public function pctFillesGarconsInscriptions($promotion)
	{

		$fillesGarcons = array(
			'filles' => array(
				'label' => 'Filles',
				'pct' => 0,
			),
			'garcons' => array(
				'label' => 'Garçons',
				'pct' => 0,
			),
		);
		$garcons = Inscription::getCount(
			array('where' => array('J2Homme' => true, 'Niveau' => $this->get('ID'), 'Promotion' => $promotion->get('ID'))),
			Inscription::sqlQueryCount(true) . <<<END
		JOIN (SELECT `ID` AS `J1ID`, `User` AS `J1User` FROM `gen_eleves`) AS `j1` ON `ins_inscriptions`.`Eleve` = `j1`.`J1ID`
		JOIN (SELECT `ID` AS `J2ID`, `Homme` AS `J2Homme` FROM `users`) AS `j2` ON `j1`.`J1User` = `j2`.`J2ID`
END
		);

		$countEleves = $this->getCountEleves($promotion);

		if (!$countEleves || $countEleves == 0)
			return $fillesGarcons;

		$prcGarcon = \Tools::numberFormat(($garcons * 100) / $countEleves, 0);
		$prcFille =  \Tools::numberFormat(100 - $prcGarcon, 0);

		$fillesGarcons['garcons']['pct'] = $prcGarcon;
		$fillesGarcons['filles']['pct'] = $prcFille;

		return $fillesGarcons;
	}

	public static function getList($args = null, $query = null)
	{
		if (!is_array($args))
			$args = array();

		$user = \Session::getInstance()->getCurUser();
		if ($user && $user->get('Classes')) {
			$classes =  $user->get('Classes');
			$args['where'][] = "ID IN (SELECT Niveau FROM ins_classes where ID IN (" . $classes . ") )";
		}

		if (isset($args['where'])) {
			$args['where']['Enabled'] =  true;
		} else {
			$args['where'] = array('Enabled' => true);
		}
		if (isset($args['order'])) {
			$args['order']['Cycle'] =  true;
			$args['order']['Ordre'] =  true;
		} else {
			$args['order'] = array('Cycle' => true, 'Ordre' => true);
		}

		return parent::getList($args, $query);
	}

	public function getInscriptionsCount($promotion = null, $gender = null)
	{
		/* ************ SQL QUERY ************
			SELECT COUNT(ins_inscriptions.ID) FROM ins_inscriptions 
			INNER JOIN gen_eleves ON gen_eleves.ID = ins_inscriptions.Eleve
			INNER JOIN users ON users.ID = gen_eleves.User AND users.Homme = 1
			WHERE ins_inscriptions.Classe = 97 AND ins_inscriptions.Promotion = 7
		*/

		if (!$promotion)
			$promotion = Promotion::promotion_actuelle();

		if ($gender === null)
			return  Inscription::getCount(array('where' => array('Niveau' => $this->get('ID'), 'Promotion' => $promotion->get('ID'))));

		$genderLabel = $gender > 0 ? 'Male' : 'Female';
		$query = "SELECT COUNT(ins_inscriptions.ID) AS `$genderLabel` FROM ins_inscriptions ";
		$query .= "INNER JOIN gen_eleves ON gen_eleves.ID = ins_inscriptions.Eleve ";
		$query .= "INNER JOIN users ON users.ID = gen_eleves.User AND users.Homme = $gender ";
		$query .= "WHERE ins_inscriptions.Niveau = $this->ID AND ins_inscriptions.Promotion = $promotion->ID";

		// return array_slice((\DB::reader($query, array()))[0], 0, 1);
		return \DB::scallar($query, array());
	}

	public function nextNiveaux()
	{
		return  $this->NextNiveaux ? self::where('ID IN(' . $this->NextNiveaux . ')')->get() : [];
	}
}
