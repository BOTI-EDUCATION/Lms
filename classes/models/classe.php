<?php

namespace Models;

use Exception;

class Classe extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'ins_classes';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Site' => array(
			'fk' => 'Site',
		),
		'Promotion' => array(
			'fk' => 'Promotion',
		),
		'Niveau' => array(
			'fk' => 'Niveau',
		),
		'Groupe' => array(
			'fk' => 'Groupe',
		),

		'Label' => array(
			'type' => 'varchar',
		),
		'Icone' => array(
			'type' => 'varchar',
		),
		'FraisScolarite' => array(
			'type' => 'double',
		),
		'FraisInscription' => array(
			'type' => 'double',
		),
		'Responsable' => array(
			'fk' => 'Eleve',
		),
		'ResponsableHistory' => array(
			'type' => 'varchar',
		),
		'EditHistory' => array(
			'type' => 'text',
		),
	);


	public function getIcone()
	{

		$url =  $this->get('Icone') ? \GoogleStorage::getUrl(\Config::get('path-images-classes')) :  null;
		
		if (!$url) {
			return \URL::base() . 'assets/icons/classe.png';
		}

		return $url;
	}


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

	public function nextYear($promotion)
	{
		$items = Classe::getList(array('where' => array('Promotion' => $promotion->get('ID'), 'Groupe' => $this->get('Groupe')->get('ID'), 'Niveau' => $this->get('Niveau')->get('ID'))));
		if (!$items)
			return null;
		return $items[0];
	}

	public function getCountEleves()
	{

		return  Inscription::getCount(
			array('where' => array('J2DeletedAt IS NULL', 'Classe' => $this->get('ID'))),
			Inscription::sqlQueryCount(true) . <<<END
		JOIN (SELECT `ID` AS `J1ID`, `User` AS `J1User` FROM `gen_eleves`) AS `j1` ON `ins_inscriptions`.`Eleve` = `j1`.`J1ID`
		JOIN (SELECT `ID` AS `J2ID`,`DeletedAt` AS `J2DeletedAt` FROM `users`) AS `j2` ON `j1`.`J1User` = `j2`.`J2ID`
END
		);
	}

	//Return La liste des élèves d'une classe
	public function getEleves()
	{
		$eleves = array();
		$query = 'SELECT `Eleve` FROM `ins_inscriptions` WHERE `Classe`=:classe AND `Promotion` =:promotion';
		$params = array('classe' => $this->getPK(true), 'promotion' => $_SESSION['promotion_actuelle']);

		$ids =  \DB::Reader($query, $params);
		foreach ($ids as $id) {
			$eleves[] = new Eleve($id['Eleve']);
		}
		return $eleves;
	}

	public function _getInscriptions()
	{
		$inscriptions = array();
		$items = Inscription::all(array('where' => array('Classe' => $this->get('ID')), 'order' => array('Ordre' => true)));
		foreach ($items as $item) {
			//$inscriptions[alais_string($item->get('Eleve')->get('User')->getNomComplet())] = $item;
		}
		//ksort($inscriptions, SORT_STRING | SORT_NATURAL);
		return $items;
	}



	public function getInscriptions($order_by = 'alphabet')
	{


		$inscriptions = array();

		if ($order_by  == 'alphabet') {
			$order = array('J2Nom' => true);
		}

		if ($order_by == 'massar') {
			$order = array('J1Massar' => true);
		}

		if ($order_by == 'custom') {
			$order = array('Ordre' => true);
		}


		if ($this->saved) {
			$items = Inscription::all(
				array('where' => array('Classe' => $this->get('ID'), 'J2DeletedAt IS NULL'), 'order' => $order),
				Inscription::sqlQuery() . <<<END
		JOIN (SELECT `ID` AS `J1ID`, `User` AS `J1User`, `Massar` AS `J1Massar` FROM `gen_eleves`) AS `j1` ON `ins_inscriptions`.`Eleve` = `j1`.`J1ID`
		JOIN (SELECT `ID` AS `J2ID`, `Homme` AS `J2Homme`,`Nom` AS `J2Nom`,`Prenom` AS `J2Prenom`,`DeletedAt` AS `J2DeletedAt` FROM `users`) AS `j2` ON `j1`.`J1User` = `j2`.`J2ID`
END
			);
			foreach ($items as $item) {
				$inscriptions[$item->ID] = $item;
			}
		}
		return $inscriptions;
	}


	public function pctFillesGarcons()
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
			array('where' => array('J2DeletedAt IS NULL', 'J2Homme' => true, 'Classe' => $this->get('ID'))),
			Inscription::sqlQueryCount(true) . <<<END
		JOIN (SELECT `ID` AS `J1ID`, `User` AS `J1User` FROM `gen_eleves`) AS `j1` ON `ins_inscriptions`.`Eleve` = `j1`.`J1ID`
		JOIN (SELECT `ID` AS `J2ID`, `Homme` AS `J2Homme`,`DeletedAt` AS `J2DeletedAt` FROM `users`) AS `j2` ON `j1`.`J1User` = `j2`.`J2ID`
END
		);

		$countEleves = $this->getCountEleves();

		if (!$countEleves || $countEleves == 0)
			return $fillesGarcons;

		$prcGarcon = \Tools::numberFormat(($garcons * 100) / $countEleves, 0);
		$prcFille =  \Tools::numberFormat(100 - $prcGarcon, 0);

		$fillesGarcons['garcons']['pct'] = $prcGarcon;
		$fillesGarcons['filles']['pct'] = $prcFille;

		return $fillesGarcons;
	}

	public function tokens()
	{
		$tokens = array(
			'ios' => array(),
			'android' => array(),
		);

		$parrainages = Parrainage::getList(
			array('where' => array('J2Classe' => $this->get('ID'))),
			Parrainage::sqlQuery() . <<<END
		JOIN (SELECT `ID` AS `J1ID` FROM `gen_eleves`) AS `j1` ON `parrainages`.`Eleve` = `j1`.`J1ID`
		JOIN (SELECT `ID` AS `J2ID`, `Eleve` AS `J2Eleve`, `Classe` AS `J2Classe` FROM `ins_inscriptions`) AS `j2` ON `j1`.`J1ID` = `j2`.`J2Eleve`
END
		);

		foreach ($parrainages as $item) {

			if ($item->get('Parent')->get('User')->get('TokenID'))
				$tokens[$item->get('Parent')->get('User')->get('TokenDevice')][] = $item->get('Parent')->get('User')->get('TokenID');
		}
		return $tokens;
	}


	public function simpleTokens($with_ids = false)
	{
		$tokens = array();
		$ids = array();
		$parrainages = Parrainage::getList(
			array('where' => array('J2Classe' => $this->get('ID'))),
			Parrainage::sqlQuery() . <<<END
		JOIN (SELECT `ID` AS `J1ID` FROM `gen_eleves`) AS `j1` ON `parrainages`.`Eleve` = `j1`.`J1ID`
		JOIN (SELECT `ID` AS `J2ID`, `Eleve` AS `J2Eleve`, `Classe` AS `J2Classe` FROM `ins_inscriptions`) AS `j2` ON `j1`.`J1ID` = `j2`.`J2Eleve`
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
		$phones = array();

		$parrainages = Parrainage::getList(
			array('where' => array('J2Classe' => $this->get('ID'))),
			Parrainage::sqlQuery() . <<<END
		JOIN (SELECT `ID` AS `J1ID` FROM `gen_eleves`) AS `j1` ON `parrainages`.`Eleve` = `j1`.`J1ID`
		JOIN (SELECT `ID` AS `J2ID`, `Eleve` AS `J2Eleve`, `Classe` AS `J2Classe` FROM `ins_inscriptions`) AS `j2` ON `j1`.`J1ID` = `j2`.`J2Eleve`
END
		);

		foreach ($parrainages as $item) {

			if ($item->get('Parent')->get('User')->get('Tel'))
				$phones[] = $item->get('Parent')->get('User')->get('Tel');
		}
		return $phones;
	}


	public function isResponsable($eleve)
	{
		if (!$this->get('Responsable'))
			return false;

		return ($eleve->getPK(true) == $this->get('Responsable')->getPK(true));
	}

	public function total_encaissements($mois)
	{

		$query = <<<END
		SELECT SUM(`Montant`) AS Montant FROM `fnc_encaissementlignes`

		JOIN (SELECT `ID` AS `J1ID`, `Label` AS `J1Label` FROM `fnc_encaissementrubriques`) AS `j1` ON `fnc_encaissementlignes`.`EncaissementRubrique` = `j1`.`J1ID`

		JOIN (SELECT `ID` AS `J2ID`, `Inscription` AS `J2Inscription` FROM `fnc_encaissements`) AS `j2` ON `fnc_encaissementlignes`.`Encaissement` = `j2`.`J2ID`

		JOIN (SELECT `ID` AS `J3ID`, `Classe` AS `J3Classe` FROM `ins_inscriptions`) AS `j3` ON `j2`.`J2Inscription` = `j3`.`J3ID`

END;

		if ($mois == 9) {
			$query .= <<<END
		WHERE J3Classe = ? AND  ( Mois = ? OR Mois IS NULL )
END;
		} else {
			$query .= <<<END
		WHERE J3Classe = ? AND Mois = ?
END;
		}

		$params = array($this->get('ID'), $mois);

		$result = \DB::scallar($query, $params);

		return ($result) ? $result : 0;
	}


	public function cours_generes()
	{

		$query = <<<END
		SELECT COUNT(*) AS Count FROM `sco_seance_tracking`

		WHERE Classe = ?

END;

		$params = array($this->get('ID'));

		$result = \DB::scallar($query, $params);

		return ($result) ? $result : 0;
	}

	public function absence_saisies()
	{

		$query = <<<END
		SELECT COUNT(*) AS Count FROM `sco_absences`

		JOIN (SELECT `ID` AS `J1ID`, `Classe` AS `J1Classe` FROM `sco_seance_tracking`) AS `j1` ON `sco_absences`.`Cours` = `j1`.`J1ID`

		WHERE ( Retards = 0 OR Retards IS NULL) AND ValidateAt IS NOT NULL AND J1Classe = ?

END;

		$params = array($this->get('ID'));

		$result = \DB::scallar($query, $params);

		return ($result) ? $result : 0;
	}

	public function transmission_saisies()
	{

		$query = <<<END
		SELECT COUNT(*) AS Count FROM `trans_tracking`

		JOIN (SELECT `ID` AS `J1ID`, `Classe` AS `J1Classe` FROM `ins_inscriptions`) AS `j1` ON `trans_tracking`.`Inscription` = `j1`.`J1ID`
		JOIN (SELECT `ID` AS `J2ID` FROM `ins_classes`) AS `j2` ON `j2`.`J2ID` = `j1`.`J1Classe`

		WHERE J2ID = ?

END;

		$params = array($this->get('ID'));

		$result = \DB::scallar($query, $params);

		return ($result) ? $result : 0;
	}

	public function retards_saisies()
	{

		$query = <<<END
		SELECT COUNT(*) AS Count FROM `sco_absences`

		JOIN (SELECT `ID` AS `J1ID`, `Classe` AS `J1Classe` FROM `sco_seance_tracking`) AS `j1` ON `sco_absences`.`Cours` = `j1`.`J1ID`

		WHERE Retards > 0 AND ValidateAt IS NOT NULL AND J1Classe = ?

END;

		$params = array($this->get('ID'));

		$result = \DB::scallar($query, $params);

		return ($result) ? $result : 0;
	}

	public function examens_generes()
	{

		// 		$query = <<<END
		// 		SELECT COUNT(*) AS Count FROM `sco_examens`

		// 		JOIN (SELECT `ID` AS `J1ID`, `Classe` AS `J1Classe`, `Annule` AS `J1Annule` FROM `sco_seance_tracking`) AS `j1` ON `sco_examens`.`Cours` = `j1`.`J1ID`

		// 		WHERE J1Annule IS NULL AND J1Classe = ?

		// END;

		// 		$params = array($this->get('ID'));

		// 		$result = \DB::scallar($query, $params);

		return 0;
	}

	public function _getExamensByType($semestre = null)
	{
		$examens = array();
		$where = array();
		$where['Semestre'] = $semestre;
		$where['Classe'] = $this->get('ID');
		$where[] = 'TypeExam IS NOT NULL';

		$items = Evaluation::getList(array('where' => $where, 'order' => array('ID' => true)));
		if (!$items)
			return array();
		foreach ($items as $item) {
			if (!$item->get('Matiere'))
				continue;

			if (!isset($examens[$item->get('Matiere')->get('ID')][$item->get('TypeExam')->get('ID')]))
				$examens[$item->get('Matiere')->get('ID')][$item->get('TypeExam')->get('ID')] = array();

			$examens[$item->get('Matiere')->get('ID')][$item->get('TypeExam')->get('ID')][]  = $item;
		}
		return $examens;
	}

	public function _getExamensByTypeAndHasNote($semestre = null)
	{
		$examens = array();
		$where = array();
		$where['Semestre'] = $semestre;
		$where['Classe'] = $this->get('ID');
		$where[] = 'TypeExam IS NOT NULL';
		$where[] = 'ID IN (SELECT Evaluation from notes)';

		$items = Evaluation::getList(array('where' => $where, 'order' => array('ID' => true)));
		if (!$items)
			return array();
		foreach ($items as $item) {
			if (!isset($examens[$item->get('Matiere')->get('ID')][$item->get('TypeExam')->get('ID')]))
				$examens[$item->get('Matiere')->get('ID')][$item->get('TypeExam')->get('ID')] = array();

			$examens[$item->get('Matiere')->get('ID')][$item->get('TypeExam')->get('ID')][]  = $item;
		}
		return $examens;
	}


	public function getExamensByType($semestre = null)
	{
		$examens = array();
		$where = array();
		$where['Semestre'] = $semestre;
		$where['Classe'] = $this->get('ID');
		$where[] = 'TypeExam IS NOT NULL';

		$items = Evaluation::getList(array('where' => $where, 'order' => array('ID' => true, 'Rang' => true)));
		if (!$items)
			return array();
		foreach ($items as $item) {
			if (!$item->get('Matiere'))
				continue;

			if (is_null($item->Rang)) {
				$item->getRang();
			}

			if (!isset($examens[$item->get('Matiere')->get('ID')][$item->get('TypeExam')->get('ID')])) {
				$examens[$item->get('Matiere')->get('ID')][$item->get('TypeExam')->get('ID')] = array();
			}
			$examens[$item->get('Matiere')->get('ID')][$item->get('TypeExam')->get('ID')][$item->Rang]  = $item;
		}
		return $examens;
	}


	public function getExamensByTypeAndHasNote($semestre = null)
	{
		$examens = array();
		$where = array();
		$where['Semestre'] = $semestre;
		$where['Classe'] = $this->get('ID');
		$where[] = 'TypeExam IS NOT NULL';
		$where[] = 'ID IN (SELECT Evaluation from notes)';

		$items = Evaluation::getList(array('where' => $where, 'order' => array('ID' => true)));
		if (!$items)
			return array();

		foreach ($items as $item) {

			if (is_null($item->Rang)) {
				$item->getRang();
			}

			if (!isset($examens[$item->get('Matiere')->get('ID')][$item->get('TypeExam')->get('ID')])) {
				$examens[$item->get('Matiere')->get('ID')][$item->get('TypeExam')->get('ID')] = array();
			}

			$examens[$item->get('Matiere')->get('ID')][$item->get('TypeExam')->get('ID')][$item->Rang]  = $item;
		}
		return $examens;
	}

	public static function _getList($args = null, $query = null, $getAll = null)
	{
		if (!is_array($args))
			$args = array();
		if (!$getAll && !isset($args['where']['Promotion'])) {
			$args['where']['Promotion'] = $_SESSION['promotion_actuelle'];
		} else {
			if (is_object($args['where']['Promotion'])) {
				$args['where']['Promotion'] = $args['where']['Promotion']->ID;
			}
		}

		if (!$getAll) {
			$user = \Session::getInstance()->getCurUser();
			if ($user && $user->get('Classes')) {
				$classes =  $user->get('Classes');
				$args['where'][] = "ID IN (" . $classes . ")";
			}
		}

		return parent::getList($args, $query);
	}



	public static function getList($args = null, $query = null, $getAll = null)
	{
		if (!is_array($args))
			$args = array();


		if (!$getAll && !isset($args['where']['Promotion'])) {
			$args['where']['Promotion'] = $_SESSION['promotion_actuelle'];
		} else {
			if (isset($args['where']['Promotion'])&&is_object($args['where']['Promotion'])) {
				$args['where']['Promotion'] = $args['where']['Promotion']->ID;
			}
		}

		if (!$getAll) {
			$user = \Session::getInstance()->getCurUser();
			if ($user && $user->get('Classes')) {
				$classes =  $user->get('Classes');
				$args['where'][] = "ID IN (" . $classes . ")";
			}
		}
		if (!$query) {

			$args['order'] = [];
			if (!isset($args['order']['J2Ordre'])) {
				$args['order']['J2Ordre'] = true;
			}

			// if (!isset($args['order']['Groupe'])) {
			// 	$args['order']['Groupe'] = true;
			// }

			$query = Classe::sqlQuery() . <<<END
		JOIN (SELECT `ID` AS `J2ID`, `Ordre` AS `J2Ordre` FROM `gen_niveaux`) AS `j2` ON `j2`.`J2ID` = `ins_classes`.`Niveau`
END;
		}

		return parent::getList($args, $query);
	}




	function getEnseignants()
	{
		$list  =  EnseignantClasse::getList(array('where' => array('Classe' => $this->get('ID'))));;
		$enseignants = [];
		foreach ($list as $key => $item) {

			try {
				$ens = $item->get('Enseignant');
				if ($ens->User->DeletedAt) {
					continue;
				}

				$enseignants[] = $ens;
			} catch (Exception $th) {
				continue;
			}
		}

		return $enseignants;
	}

	function getEtds()
	{
		$list  = ETD\Edt::all(array('where' => array('Classe' => $this->get('ID'))));
		return $list;
	}

	function addEtd($data)
	{
		$data['Classe'] = $this->get('ID');
		return $this->add(ETD\Edt::class, $data);
	}

	public function moyeneClassMatiere($matiere, $examens, $evatuations)
	{
		$inscriptions = $this->getInscriptions();
		$moyanSum = 0;

		foreach ($inscriptions as $inscription) {
			$moyanSum += $inscription->moyeneMatiere($matiere, $examens, $evatuations);
		}

		return $moyanSum / count($inscriptions);
	}

	public function moyeneClassUnite($matieres, $examens, $evatuations)
	{
		$moyanSum = 0;

		foreach ($matieres as $matiere) {
			$moyanSum += $this->moyeneClassMatiere($matiere, $examens, $evatuations);
		}

		return $moyanSum / count($matieres);
	}

	public function getNextClasse()
	{
		$classes = Classe::getList(
			array(
				'where' => array(
					'Site' => $this->get('Site')->getKey(),
					'Promotion' => $this->get('Promotion')->get('ID'),
					'(`ID` > ' . $this->get('ID') . ')'
				),
				'order' => array(
					'ID' => true
				),
				'limit' => 1
			)
		);

		if (!$classes) {

			$classes = Classe::getList(
				array(
					'where' => array(
						'Site' => $this->get('Site')->getKey(),
						'Promotion' => $this->get('Promotion')->get('ID')
					),
					'order' => array(
						'ID' => true
					),
					'limit' => 1
				)
			);

			if (!$classes)
				return $this;
		}

		return $classes[0];
	}

	public function getPrevClasse()
	{
		$classes = Classe::getList(
			array(
				'where' => array(
					'Site' => $this->get('Site')->getKey(),
					'Promotion' => $this->get('Promotion')->get('ID'),
					'(`ID` < ' . $this->get('ID') . ')'
				),
				'order' => array(
					'ID' => false
				),
				'limit' => 1
			)
		);

		if (!$classes) {

			$classes = Classe::getList(
				array(
					'where' => array(
						'Site' => $this->get('Site')->getKey(),
						'Promotion' => $this->get('Promotion')->get('ID')
					),
					'order' => array(
						'ID' => false
					),
					'limit' => 1
				)
			);

			if (!$classes)
				return $this;
		}

		return $classes[0];
	}


	//$classes = Models\Classe::getList(array('where' => array('Promotion' => $promotion->get('ID')), 'order' => array('Promotion' => false, 'Niveau' => true, 'Groupe' => true)), null, true);

}
