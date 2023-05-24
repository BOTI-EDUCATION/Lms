<?php

namespace Models;

class Eleve extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'gen_eleves';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'User' => array(
			'fk' => 'User',
		),
		'NomAr' => array(
			'type' => 'varchar',
		),
		'PrenomAr' => array(
			'type' => 'varchar',
		),
		'CNE' => array(
			'type' => 'varchar',
		),
		'Massar' => array(
			'type' => 'varchar',
		),
		'An_etablissement' => array(
			'type' => 'varchar',
		),
		'ForeignLang' => array(
			'type' => 'boolean',
		),
		'Matricule' => array(
			'type' => 'varchar',
		),
		'CRMApplicationFields' => array(
			'type' => 'varchar',
		),
	);



	public function getFichePersonnelle()
	{
		return EleveFichePersonnelle::getList(array('where' => array('Eleve' => $this->get('ID')), 'order' => array('Date' => true)));
	}

	public function getSuiviScolaire()
	{

		return EleveSuiviScolaire::getList(array('where' => array('Eleve' => $this->get('ID')), 'order' => array('Date' => true)));
	}


	public function getDocuments()
	{
		return \Models\Document\EleveDocument::where(['Eleve' => $this->getKey()])->get();
	}


	public function nickName()
	{
		return implode(' ', array($this->get('User')->get('Nom'), $this->get('User')->get('Prenom')));

		// return $this->get('Prenom').' '.strtoupper(substr($this->get('Nom'), 0, 2));
	}

	public static function activeEleves($promotion = null, $classe = null, $cycle = null, $classeRequired = true)
	{

		if (!$promotion)
			$promotion = Promotion::promotion_actuelle();

		$where = array();
		if ($classe)
			$where['J1Classe'] = $classe->get('ID');
		elseif ($promotion)
			$where['J1Promotion'] = $promotion->get('ID');
		// else

		if ($classeRequired)
			$where[] = 'J1Classe IS NOT NULL';

		if ($cycle)
			$where['J3Cycle'] = $cycle->get('ID');

		return Eleve::getList(
			array('where' => $where),
			Eleve::sqlQuery(true) . <<<END
	 JOIN (SELECT `ID` AS `J1ID`, `Eleve` AS `J1Eleve`, `Promotion` AS `J1Promotion`, `Niveau` AS `J1Niveau`, `Classe` AS `J1Classe`, `Ordre` AS `J1Ordre`  FROM `ins_inscriptions`) AS `j1` ON `gen_eleves`.`ID` = `j1`.`J1Eleve`
	 JOIN (SELECT `ID` AS `J2ID`, `Annee` AS `J2Annee`, `Actuelle` AS `J2Actuelle` FROM `gen_promotions`) AS `j2` ON `j1`.`J1Promotion` = `j2`.`J2ID`
	 JOIN (SELECT `ID` AS `J3ID`, `Cycle` AS `J3Cycle` FROM `gen_niveaux`) AS `j3` ON `j1`.`J1Niveau` = `j3`.`J3ID`
END
		);
	}


	public function getInscription($promotion = null)
	{
		$check_for_promotion  = !is_null($promotion);

		if (is_null($promotion)) {
			$promotion =   Promotion::promotion_actuelle();
		}

		$where = array(
			'Eleve' => $this->get('ID'),
			'Promotion' =>  $promotion->get('ID'),
		);

		$inscriptions = Inscription::all(
			array(
				'where' => $where,
				'limit' => 1
			)
		);
		if ($check_for_promotion && !count($inscriptions)) {
			return null;
		}

		// Get last inscription
		if (!count($inscriptions)) {

			$where = array(
				'Eleve' => $this->get('ID'),
			);

			$inscriptions = Inscription::all(
				array(
					'where' => $where,
					'order' => array('ID' => false),
					'limit' => 1
				)
			);
		}

		if (!$inscriptions) {
			return null;
		}
		return $inscriptions[0];
	}



	public function getInscriptionProchaine()
	{

		$array = array(
			'Eleve' => $this->get('ID'),
		);


		$promotion =   Promotion::promotion_overte_pour_inscriptions();

		if (!$promotion)
			return null;

		$array['Promotion'] =  $promotion->get('ID');

		$inscriptions = Inscription::all(
			array(
				'where' => $array,
				'order' => array('J1Actuelle' => false),
				'limit' => 1
			),
			Inscription::sqlQuery() . <<<END
			JOIN (SELECT `ID` AS `J1ID`, `Annee` AS `J1Annee`, `Actuelle` AS `J1Actuelle` FROM `gen_promotions`) AS `j1` ON `ins_inscriptions`.`Promotion` = `j1`.`J1ID`
END
		);
		if (!$inscriptions)
			return null;

		return $inscriptions[0];
	}

	public function getEtatPaiement()
	{
		$classe = $this->getInscription()->get('Classe');
	}


	public function getPaiements($mois)
	{
		return \Models\FIN\Encaissement::getList(array('where' => array('Inscription' => $this->getInscription()->getPK(true))));
	}



	public function getPosts($format = null, $rubrique = null, $matiere = null, $filter_date = true)
	{
		if ($format)
			$format = PostFormat::getByAlias($format);

		//$inscription_prochaine  = $this->getInscription(Promotion::promotion_overte_pour_inscriptions());


		$inscription = 	$this->getInscription();
		$promotion 	 = $inscription ? $inscription->Promotion : null;

		$date_pub 	 = $promotion && $promotion->get('DateDebut') ? $promotion->get('DateDebut') : (date('Y') - 1) . '-08-01';

		if (!$filter_date) {
			$date_pub  = '2000-01-01';
		}

		// if ($inscription->DateInscripiton) {
		// 	$date_pub  = date('Y-m-d', strtotime($inscription->DateInscripiton));
		// }


		$posts = array();
		$postsMerge = array();
		$postsSort = array();

		/* Start GET POST ELEVE */

		$where = array();
		$where['J1Visible'] = true;
		$where[] = '(J1DateExpiration IS NULL OR (J1DateExpiration IS NOT NULL AND \'' . date('Y-m-d') . '\' BETWEEN J1DatePublication AND J1DateExpiration) )';
		// $where['J1Home'] = true;
		$where['Eleve'] = $this->get('ID');

		//$where[] = '(DATE(J1DatePublication) >= \'' . $date_pub . '\')';
		if ($format)
			$where['J1PostFormat'] = $format->get('ID');
		if ($rubrique)
			$where['J1PostCategorie'] = $rubrique->get('ID');
		if ($matiere)
			$where['J1Matiere'] = $matiere->get('ID');
		// if($rubrique && in_array($rubrique->get('ID'), array(3,4)))
		// $where[] = '( J1DateRemise IS NULL OR  DATE(J1DateRemise) >= DATE_SUB(CURDATE() , INTERVAL 7 DAY ) )';


		$postsEleve = PostEleve::getList(
			array('where' => $where, 'order' => array('J1DateRemise' => false)),
			PostEleve::sqlQuery() . <<<END
	JOIN (SELECT `ID` AS `J1ID`, `Matiere` AS `J1Matiere`, `PostCategorie` AS `J1PostCategorie`, `PostFormat` AS `J1PostFormat`, `DateRemise` AS `J1DateRemise`, `DatePublication` AS `J1DatePublication`, `DateExpiration` AS `J1DateExpiration`, `Home` AS `J1Home`, `Visible` AS `J1Visible` FROM `com_posts`) AS `j1` ON `com_posteleves`.`Post` = `j1`.`J1ID`
END
		);

		foreach ($postsEleve as $item)
			$postsMerge[] = array(
				'object' => $item->get('Post'),
				'date' => $item->get('Post')->get('DatePublication'),
			);

		/* End GET POST ELEVE */

		if ($inscription) {

			/* Start GET POST SERVICE */
			$where = array();
			$where['Visible'] = true;
			$where[] = '(DateExpiration IS NULL OR (DateExpiration IS NOT NULL AND \'' . date('Y-m-d') . '\' BETWEEN DatePublication AND DateExpiration) )';
			//$where[] = '(DATE(DatePublication) >= \'' . $date_pub . '\')';
			//$where["J2Inscription"] = $inscription->get('ID');
			if ($format)
				$where['PostFormat'] = $format->get('ID');
			if ($rubrique)
				$where['PostCategorie'] = $rubrique->get('ID');
			if ($matiere)
				$where['Matiere'] = $matiere->get('ID');
			$where[] = "`J1Service` IN (SELECT  `EncaissementRubrique`  FROM `fnc_encaissementinscriptions` WHERE `Inscription` = " . $inscription->get('ID') . ")";

			$postsEleve = Post::getList(
				array('where' => $where),
				Post::sqlQuery(true) . <<<END
	JOIN (SELECT `Post` AS `J1Post`,`Service` AS `J1Service` FROM `com_postservices`) AS `j1` ON `com_posts`.`ID` = `j1`.`J1Post`
END
			);
			foreach ($postsEleve as $item)
				$postsMerge[] = array(
					'object' => $item,
					'date' => $item->get('DatePublication'),
				);

			//	dd($postsEleve);

			/* End GET POST SERVICE */
		}




		if ($inscription && $inscription->get('Niveau')) {


			// By niveaux 
			$where = array();
			$where['J1Visible'] = true;
			$where[] = '(J1DateExpiration IS NULL OR (J1DateExpiration IS NOT NULL AND \'' . date('Y-m-d') . '\' BETWEEN J1DatePublication AND J1DateExpiration) )';

			$where['Niveau'] = $inscription->get('Niveau')->get('ID');

			$where[] = '(Site IS NULL OR Site ="' . $inscription->getSite()->getKey() . '")';

			// if ($inscription_prochaine &&  $inscription_prochaine->get('Niveau')) {
			// 	unset($where['Niveau']);
			// 	$where[] = '(Niveau IN (' . $inscription_prochaine->get('Niveau')->get('ID') . ',' . $inscription->get('Niveau')->get('ID') . '))';
			// }

			$where[] = '(DATE(J1DatePublication) >= \'' . $date_pub . '\')';
			if ($format)
				$where['J1PostFormat'] = $format->get('ID');
			if ($rubrique)
				$where['J1PostCategorie'] = $rubrique->get('ID');
			if ($matiere)
				$where['J1Matiere'] = $matiere->get('ID');



			$postsNiveau = PostNiveau::getList(
				array('where' => $where, 'order' => array('J1DateRemise' => false)),
				PostNiveau::sqlQuery(true) . <<<END
	JOIN (SELECT `ID` AS `J1ID`,  `Matiere` AS `J1Matiere`,`PostCategorie` AS `J1PostCategorie`, `PostFormat` AS `J1PostFormat`, `DateRemise` AS `J1DateRemise`, `DatePublication` AS `J1DatePublication`, `DateExpiration` AS `J1DateExpiration`, `Home` AS `J1Home`, `Visible` AS `J1Visible` FROM `com_posts`) AS `j1` ON `com_postniveaux`.`Post` = `j1`.`J1ID`
END
			);

			foreach ($postsNiveau as $item)
				$postsMerge[] = array(
					'object' => $item->get('Post'),
					'date' => $item->get('Post')->get('DatePublication'),
				);


			// By cycles
			$where = array();
			$where['J1Visible'] = true;
			$where[] = '(J1DateExpiration IS NULL OR (J1DateExpiration IS NOT NULL AND \'' . date('Y-m-d') . '\' BETWEEN J1DatePublication AND J1DateExpiration) )';

			$where['Cycle'] = $inscription->get('Niveau')->get('Cycle')->get('ID');

			$where[] = '(Site IS NULL OR Site ="' . $inscription->getSite()->getKey() . '")';

			$where[] = '(DATE(J1DatePublication) >= \'' . $date_pub . '\')';
			if ($format)
				$where['J1PostFormat'] = $format->get('ID');
			if ($rubrique)
				$where['J1PostCategorie'] = $rubrique->get('ID');
			if ($matiere)
				$where['J1Matiere'] = $matiere->get('ID');



			$postsCycles = PostCycle::getList(
				array('where' => $where, 'order' => array('J1DateRemise' => false)),
				PostCycle::sqlQuery(true) . <<<END
 JOIN (SELECT `ID` AS `J1ID`,  `Matiere` AS `J1Matiere`,`PostCategorie` AS `J1PostCategorie`, `PostFormat` AS `J1PostFormat`, `DateRemise` AS `J1DateRemise`, `DatePublication` AS `J1DatePublication`, `DateExpiration` AS `J1DateExpiration`, `Home` AS `J1Home`, `Visible` AS `J1Visible` FROM `com_posts`) AS `j1` ON `com_postcycles`.`Post` = `j1`.`J1ID`
END
			);

			foreach ($postsCycles as $item)
				$postsMerge[] = array(
					'object' => $item->get('Post'),
					'date' => $item->get('Post')->get('DatePublication'),
				);
		}



		if ($inscription && $inscription->get('Classe')) {

			$where = array();
			$where['J1Visible'] = true;
			$where[] = '(J1DateExpiration IS NULL OR (J1DateExpiration IS NOT NULL AND \'' . date('Y-m-d') . '\' BETWEEN J1DatePublication AND J1DateExpiration) )';
			// $where['J1Home'] = true;
			$where['Classe'] = $inscription->get('Classe')->get('ID');
			// if ($inscription_prochaine && $inscription_prochaine->get('Classe')) {
			// 	unset($where['Classe']);
			// 	$where[] = '(Classe IN (' . $inscription_prochaine->get('Classe')->get('ID') . ',' . $inscription->get('Classe')->get('ID') . '))';
			// }

			///$where[] = '(DATE(J1DatePublication) >= \'' . $date_pub . '\')';
			if ($format)
				$where['J1PostFormat'] = $format->get('ID');
			if ($rubrique)
				$where['J1PostCategorie'] = $rubrique->get('ID');
			if ($matiere)
				$where['J1Matiere'] = $matiere->get('ID');
			// if($rubrique && in_array($rubrique->get('ID'), array(3,4)))
			// $where[] = '( J1DateRemise IS NULL OR  DATE(J1DateRemise) >= DATE_SUB(CURDATE() , INTERVAL 7 DAY ) )';


			$postsClasse = PostClasse::getList(
				array('where' => $where, 'order' => array('J1DateRemise' => false)),
				PostClasse::sqlQuery(true) . <<<END
	JOIN (SELECT `ID` AS `J1ID`,`Matiere` AS `J1Matiere`, `PostCategorie` AS `J1PostCategorie`, `PostFormat` AS `J1PostFormat`, `DateRemise` AS `J1DateRemise`, `DatePublication` AS `J1DatePublication`, `DateExpiration` AS `J1DateExpiration`, `Home` AS `J1Home`, `Visible` AS `J1Visible` FROM `com_posts`) AS `j1` ON `com_postclasses`.`Post` = `j1`.`J1ID`
END
			);


			foreach ($postsClasse as $item)
				$postsMerge[] = array(
					'object' => $item->get('Post'),
					'date' => $item->get('Post')->get('DatePublication'),
				);
		}

		$where = array();
		$where['Visible'] = true;
		$where[] = 'Parents = true';
		//$where[] = '(Public = true OR Parents = true)';
		$where[] = '(DateExpiration IS NULL OR (DateExpiration IS NOT NULL  AND \'' . date('Y-m-d') . '\' BETWEEN DatePublication AND DateExpiration) )';

		$where[] = '(DATE(DatePublication) >= \'' . $date_pub . '\')';
		if ($format)
			$where['PostFormat'] = $format->get('ID');
		if ($rubrique)
			$where['PostCategorie'] = $rubrique->get('ID');
		if ($matiere)
			$where['Matiere'] = $matiere->get('ID');
		// if($rubrique && in_array($rubrique->get('ID'), array(3,4)))
		// $where[] = 'DATE(DateRemise) >= DATE_SUB(CURDATE() , INTERVAL 7 DAY )';

		$posts = Post::getList(array('where' => $where, 'order' => array('DatePublication' => false)));

		foreach ($posts as $item)
			$postsMerge[] = array(
				'object' => $item,
				'date' => $item->get('DatePublication'),
			);

		foreach ($postsMerge as $key => $row) {
			$date[$key]  = $row['date'];
		}

		if ($postsMerge)
			// Sort the data with DATE ascending, NOMBRE ascending
			array_multisort($date, SORT_DESC, $postsMerge);

		foreach ($postsMerge as $item) {
			$postsSort[$item['object']->get('ID')]  = $item['object'];
		}

		return $postsSort;
	}



	public function getPostsDebug($format = null, $rubrique = null, $matiere = null, $filter_date = true)
	{
		if ($format)
			$format = PostFormat::getByAlias($format);

		$inscription_prochaine  = $this->getInscription(Promotion::promotion_overte_pour_inscriptions());
		$inscription = 	$this->getInscription();
		$promotion 	 = 	$inscription->Promotion;

		$date_pub 	 =  $promotion->get('DateDebut') ?: (date('Y') - 1) . '-08-01';

		if (!$filter_date) {
			$date_pub  = '2000-01-01';
		}

		// if ($inscription->DateInscripiton) {
		// 	$date_pub  = date('Y-m-d', strtotime($inscription->DateInscripiton));
		// }


		$posts = array();
		$postsMerge = array();
		$postsSort = array();

		/* Start GET POST ELEVE */

		$where = array();
		$where['J1Visible'] = true;
		$where[] = '(J1DateExpiration IS NULL OR (J1DateExpiration IS NOT NULL AND \'' . date('Y-m-d') . '\' BETWEEN J1DatePublication AND J1DateExpiration) )';
		// $where['J1Home'] = true;
		$where['Eleve'] = $this->get('ID');
		$where[] = '(DATE(J1DatePublication) >= \'' . $date_pub . '\')';
		if ($format)
			$where['J1PostFormat'] = $format->get('ID');
		if ($rubrique)
			$where['J1PostCategorie'] = $rubrique->get('ID');
		if ($matiere)
			$where['J1Matiere'] = $matiere->get('ID');
		// if($rubrique && in_array($rubrique->get('ID'), array(3,4)))
		// $where[] = '( J1DateRemise IS NULL OR  DATE(J1DateRemise) >= DATE_SUB(CURDATE() , INTERVAL 7 DAY ) )';


		$postsEleve = PostEleve::getList(
			array('where' => $where, 'order' => array('J1DateRemise' => false)),
			PostEleve::sqlQuery() . <<<END
	JOIN (SELECT `ID` AS `J1ID`, `Matiere` AS `J1Matiere`, `PostCategorie` AS `J1PostCategorie`, `PostFormat` AS `J1PostFormat`, `DateRemise` AS `J1DateRemise`, `DatePublication` AS `J1DatePublication`, `DateExpiration` AS `J1DateExpiration`, `Home` AS `J1Home`, `Visible` AS `J1Visible` FROM `com_posts`) AS `j1` ON `com_posteleves`.`Post` = `j1`.`J1ID`
END
		);

		foreach ($postsEleve as $item)
			$postsMerge[] = array(
				'object' => $item->get('Post'),
				'date' => $item->get('Post')->get('DatePublication'),
			);

		/* End GET POST ELEVE */

		if ($inscription) {

			/* Start GET POST SERVICE */
			$where = array();
			$where['Visible'] = true;
			$where[] = '(DateExpiration IS NULL OR (DateExpiration IS NOT NULL AND \'' . date('Y-m-d') . '\' BETWEEN DatePublication AND DateExpiration) )';
			//$where[] = '(DATE(DatePublication) >= \'' . $date_pub . '\')';
			//$where["J2Inscription"] = $inscription->get('ID');
			if ($format)
				$where['PostFormat'] = $format->get('ID');
			if ($rubrique)
				$where['PostCategorie'] = $rubrique->get('ID');
			if ($matiere)
				$where['Matiere'] = $matiere->get('ID');
			$where[] = "`J1Service` IN (SELECT  `EncaissementRubrique`  FROM `fnc_encaissementinscriptions` WHERE `Inscription` = " . $inscription->get('ID') . ")";

			$postsEleve = Post::getList(
				array('where' => $where),
				Post::sqlQuery(true) . <<<END
	JOIN (SELECT `Post` AS `J1Post`,`Service` AS `J1Service` FROM `com_postservices`) AS `j1` ON `com_posts`.`ID` = `j1`.`J1Post`
END
			);
			foreach ($postsEleve as $item)
				$postsMerge[] = array(
					'object' => $item,
					'date' => $item->get('DatePublication'),
				);

			//	dd($postsEleve);

			/* End GET POST SERVICE */
		}




		if ($inscription && $inscription->get('Niveau')) {

			$where = array();
			$where['J1Visible'] = true;
			$where[] = '(J1DateExpiration IS NULL OR (J1DateExpiration IS NOT NULL AND \'' . date('Y-m-d') . '\' BETWEEN J1DatePublication AND J1DateExpiration) )';


			$where['Niveau'] = $inscription->get('Niveau')->get('ID');
			if ($inscription_prochaine &&  $inscription_prochaine->get('Niveau')) {
				unset($where['Niveau']);
				$where[] = '(Niveau IN (' . $inscription_prochaine->get('Niveau')->get('ID') . ',' . $inscription->get('Niveau')->get('ID') . '))';
			}

			$where[] = '(DATE(J1DatePublication) >= \'' . $date_pub . '\')';
			if ($format)
				$where['J1PostFormat'] = $format->get('ID');
			if ($rubrique)
				$where['J1PostCategorie'] = $rubrique->get('ID');
			if ($matiere)
				$where['J1Matiere'] = $matiere->get('ID');


			// if($rubrique && in_array($rubrique->get('ID'), array(3,4)))
			// $where[] = '( J1DateRemise IS NULL OR  DATE(J1DateRemise) >= DATE_SUB(CURDATE() , INTERVAL 7 DAY ) )';


			$postsNiveau = PostNiveau::printQuery(
				array('where' => $where, 'order' => array('J1DateRemise' => false)),
				PostNiveau::sqlQuery(true) . <<<END
	JOIN (SELECT `ID` AS `J1ID`,  `Matiere` AS `J1Matiere`,`PostCategorie` AS `J1PostCategorie`, `PostFormat` AS `J1PostFormat`, `DateRemise` AS `J1DateRemise`, `DatePublication` AS `J1DatePublication`, `DateExpiration` AS `J1DateExpiration`, `Home` AS `J1Home`, `Visible` AS `J1Visible` FROM `com_posts`) AS `j1` ON `com_postniveaux`.`Post` = `j1`.`J1ID`
END
			);

			dd($postsNiveau);

			foreach ($postsNiveau as $item)
				$postsMerge[] = array(
					'object' => $item->get('Post'),
					'date' => $item->get('Post')->get('DatePublication'),
				);
		}

		if ($inscription && $inscription->get('Classe')) {

			$where = array();
			$where['J1Visible'] = true;
			$where[] = '(J1DateExpiration IS NULL OR (J1DateExpiration IS NOT NULL AND \'' . date('Y-m-d') . '\' BETWEEN J1DatePublication AND J1DateExpiration) )';
			// $where['J1Home'] = true;
			$where['Classe'] = $inscription->get('Classe')->get('ID');
			if ($inscription_prochaine && $inscription_prochaine->get('Classe')) {
				unset($where['Classe']);
				$where[] = '(Classe IN (' . $inscription_prochaine->get('Classe')->get('ID') . ',' . $inscription->get('Classe')->get('ID') . '))';
			}

			///$where[] = '(DATE(J1DatePublication) >= \'' . $date_pub . '\')';
			if ($format)
				$where['J1PostFormat'] = $format->get('ID');
			if ($rubrique)
				$where['J1PostCategorie'] = $rubrique->get('ID');
			if ($matiere)
				$where['J1Matiere'] = $matiere->get('ID');
			// if($rubrique && in_array($rubrique->get('ID'), array(3,4)))
			// $where[] = '( J1DateRemise IS NULL OR  DATE(J1DateRemise) >= DATE_SUB(CURDATE() , INTERVAL 7 DAY ) )';


			$postsClasse = PostClasse::getList(
				array('where' => $where, 'order' => array('J1DateRemise' => false)),
				PostClasse::sqlQuery(true) . <<<END
	JOIN (SELECT `ID` AS `J1ID`,`Matiere` AS `J1Matiere`, `PostCategorie` AS `J1PostCategorie`, `PostFormat` AS `J1PostFormat`, `DateRemise` AS `J1DateRemise`, `DatePublication` AS `J1DatePublication`, `DateExpiration` AS `J1DateExpiration`, `Home` AS `J1Home`, `Visible` AS `J1Visible` FROM `com_posts`) AS `j1` ON `com_postclasses`.`Post` = `j1`.`J1ID`
END
			);


			foreach ($postsClasse as $item)
				$postsMerge[] = array(
					'object' => $item->get('Post'),
					'date' => $item->get('Post')->get('DatePublication'),
				);
		}

		$where = array();
		$where['Visible'] = true;
		$where[] = 'Parents = true';
		//$where[] = '(Public = true OR Parents = true)';
		$where[] = '(DateExpiration IS NULL OR (DateExpiration IS NOT NULL  AND \'' . date('Y-m-d') . '\' BETWEEN DatePublication AND DateExpiration) )';

		$where[] = '(DATE(DatePublication) >= \'' . $date_pub . '\')';
		if ($format)
			$where['PostFormat'] = $format->get('ID');
		if ($rubrique)
			$where['PostCategorie'] = $rubrique->get('ID');
		if ($matiere)
			$where['Matiere'] = $matiere->get('ID');
		// if($rubrique && in_array($rubrique->get('ID'), array(3,4)))
		// $where[] = 'DATE(DateRemise) >= DATE_SUB(CURDATE() , INTERVAL 7 DAY )';

		$posts = Post::getList(array('where' => $where, 'order' => array('DatePublication' => false)));

		foreach ($posts as $item)
			$postsMerge[] = array(
				'object' => $item,
				'date' => $item->get('DatePublication'),
			);

		foreach ($postsMerge as $key => $row) {
			$date[$key]  = $row['date'];
		}

		if ($postsMerge)
			// Sort the data with DATE ascending, NOMBRE ascending
			array_multisort($date, SORT_DESC, $postsMerge);

		foreach ($postsMerge as $item) {
			$postsSort[$item['object']->get('ID')]  = $item['object'];
		}

		return $postsSort;
	}



	//---------------------Check Unique Values
	public static function CNE_Exists($cne)
	{

		if (!$cne)
			return false;

		$database = \DB::getInstance();

		$query = 'SELECT `ID` FROM ' . static::wrapField(static::$table) . ' WHERE `CNE`=?';
		$params = array($cne);

		return \DB::scallar($query, $params);
	}

	public static function Massar_Exists($massar)
	{
		if (!$massar)
			return false;

		$database = \DB::getInstance();

		$query = "SELECT `ID` FROM " . static::wrapField(static::$table) . " WHERE `Massar`=? collate utf8_bin";

		$params = array($massar);

		$results  = \DB::scallar($query, $params);

		return $results;
	}
	//---------------------Check Unique Values

	public function getCountAbsences()
	{
		return 0;
	}
	public function getCountRetards()
	{
		return 0;
	}
	public function getSoldeDiscipline()
	{
		return 0;
	}


	public function tokens()
	{
		$tokens = array(
			'ios' => array(),
			'android' => array(),
		);

		$parrainages = Parrainage::getList(array('where' => array('Eleve' => $this->get('ID'))));

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
		$parrainages = Parrainage::getList(array('where' => array('Eleve' => $this->get('ID'))));
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

	public function ParentsPhone()
	{
		$numbers_phone = array();
		$parrainages = Parrainage::getList(array('where' => array('Eleve' => $this->get('ID'))));
		foreach ($parrainages as $item) {
			if ($item->get('Parent')->get('User')->get('Tel')) {
				$numbers_phone[] = $item->get('Parent')->get('User');
			}
		}
		return $numbers_phone;
	}

	public function phones()
	{
		$numbers_phone = array();
		$parrainages = Parrainage::getList(array('where' => array('Eleve' => $this->get('ID'))));
		foreach ($parrainages as $item) {
			if ($item->get('Parent')->get('User')->get('Tel')) {
				$numbers_phone[] = $item->get('Parent')->get('User')->get('Tel');
			}
		}
		return $numbers_phone;
	}

	public function getExamens($inscription = null)
	{

		$where = array();
		$where['Classe'] = $inscription->get('Classe')->get('ID');
		if (isAllowed('mobile_show_validate_evaluation')) {
			$where[] = "NotesPubliees IS NOT NULL";
		}

		$examens = isAllowed('mobile_show_evaluation') ?  \Models\Evaluation::getList(array("where" => $where, 'order' => array('NotesPubliees' => false, 'Date' => true))) : array();

		return $examens;
	}

	public function getDevoirs()
	{

		$devoirs = array();
		$categories = PostCategorie::getList(array('where' => array('ID IN (3,4)')));
		foreach ($categories as $categorie) {
			$results = $this->getPosts(null, $categorie);
			if ($results) {
				foreach ($results as $item)
					$devoirs[] = $item;
			}
		}
		return $devoirs;
	}


	public static function getList($args = null, $query = null)
	{
		if (!is_array($args))
			$args = array();

		$user = \Session::getInstance()->getCurUser();
		$args['where'][] = '`User` NOT IN (SELECT `ID` FROM `users` WHERE `DeletedAt` IS NOT NULL)';
		if ($user && $user->get('Classes')) {
			$classes =  $user->get('Classes');
			$args['where'][] = "ID IN (SELECT Eleve FROM ins_inscriptions where Classe IN (" . $classes . "))";
		}

		return parent::getList($args, $query);
	}

	public static function __getList($args = null, $query = null)
	{
		if (!is_array($args))
			$args = array();

		if (!$query)
			$query = Eleve::sqlQuery();

		$user = \Session::getInstance()->getCurUser();
		if ($user && $user->get('Classes')) {
			$query .= <<<END
		JOIN (SELECT `ID` AS `JM1ID`, `Eleve` AS `JM1Eleve`, `Classe` AS `JM1Classe` FROM `ins_inscriptions`) AS `jm_1` ON `gen_eleves`.`ID` = `jm_1`.`JM1Eleve`
END;

			$classes =  $user->get('Classes');

			if (!isset($args['where'])) {
				$args['where'] = array();
			}
			$request = "JM1Classe IN (" . $classes . ")";
			// or don't have a classe if user admin,resp_pedago

			if (roleIs('admin', 'resp_pedago')) {
				$request .=   " OR JM1Classe IS NULL";
			}

			foreach ($args['where'] as $key => $val) {
				if (is_numeric($key)) {
					$request .= " AND " . $val;
				} else {
					$request .= " AND " . $key . " = " . $val;
				}
			}
			$args['where'][] = $request;
		}

		return parent::getList($args, $query);
	}



	public static function getCount($args = null, $query = null)
	{
		if (!is_array($args))
			$args = array();

		return parent::getCount($args, null);
	}

	public static function getByCNE($cne)
	{
		$idEleve = \DB::scallar('SELECT `ID` FROM ' . static::wrapField(static::$table) . ' WHERE `CNE`=?', array($cne));

		if (!$idEleve)
			return null;

		return new self($idEleve);
	}

	public static function getByMatricule($cne)
	{
		$idEleve = \DB::scallar('SELECT `ID` FROM ' . static::wrapField(static::$table) . ' WHERE `Matricule`=?', array($cne));

		if (!$idEleve)
			return null;

		return new self($idEleve);
	}

	public static function getByMassar($cne)
	{
		$cne =  str_replace(' ', '', $cne);
		$idEleve = \DB::scallar('SELECT `ID` FROM ' . static::wrapField(static::$table) . ' WHERE `Massar`=?', array($cne));

		if (!$idEleve)
			return null;

		return new self($idEleve);
	}

	public static function getByMassarSpace($cne)
	{
		$idEleve = \DB::scallar('SELECT `ID` FROM ' . static::wrapField(static::$table) . ' WHERE `Massar` LIKE \'%' . $cne . '%\'');

		if (!$idEleve)
			return null;

		return new self($idEleve);
	}


	public static function getByPrenomAR($cne)
	{
		$idEleve = \DB::scallar('SELECT `ID` FROM ' . static::wrapField(static::$table) . ' WHERE `PrenomAr`=?', array($cne));

		if (!$idEleve)
			return null;

		return new self($idEleve);
	}

	public function parents()
	{
		$parents = array();
		$parrinages = \Models\Parrainage::getList(array('where' => array('Eleve' => $this->ID)));
		foreach ($parrinages as $p) {
			$parents[$p->Type ? $p->Type->ID : 3] = $p->Parent;
		}
		return $parents;
	}

	function frero()
	{
		$eleves = array();
		$tuteurs = array();
		$parents = $this->parents();
		foreach ($parents as $p) {
			$tuteurs[] = $p->ID;
		}
		$parrinages = Parrainage::getList(array('where' => array('Parent IN (\'' . implode(',', $tuteurs) . '\') and Type IN(1,2)')));
		foreach ($parrinages as $p) {
			try {
				if ($this->ID != $p->Eleve->ID) {
					$eleves[] = $p->Eleve;
				}
			} catch (\Exception $th) {
			}
		}
		return $eleves;
	}

	public function pickPersonneAutorized()
	{
		return Pick\PersonneAutorized::getList(array('where' => array($this->ID . ' IN(Eleves)')));
	}

	public function getNomComplet()
	{
		$nom =  implode(' ', array($this->get('PrenomAr'), $this->get('NomAr')));

		return trim($nom) ? $nom : $this->User->getNomComplet();
	}

	public function getInscriptions()
	{

		return Inscription::all(
			array(
				'where' => array(
					'Eleve' => $this->get('ID'),
				)
			)
		);
	}


	public function getFather()
	{
		$parents = \Models\Parrainage::getList(array('where' => array('Eleve' => $this->ID)));
		foreach ($parents as $parent)
			if ($parent->Type && $parent->Type->ID == 1) return $parent->Parent;

		return null;
	}

	public function getMother()
	{
		$parents = \Models\Parrainage::getList(array('where' => array('Eleve' => $this->ID)));
		foreach ($parents as $parent)
			if ($parent->Type && $parent->Type->ID == 2) return $parent->Parent;

		return null;
	}



	public static function crmLangauges($lang  = null)
	{

		$langauges =  [
			'fr' => 'Francais',
			'ar' => 'Arabe',
			'en' => 'English',
		];
		if ($lang) {
			return $langauges[$lang];
		}

		return $langauges;
	}

	public  static function lastMatricule()
	{
		$promotion =  Promotion::promotion_actuelle();
		$query = 'SELECT MAX(CAST(SUBSTRING_INDEX(`Matricule`,"/",1) AS UNSIGNED)) AS max FROM `gen_eleves` WHERE Matricule LIKE "%/' .	substr($promotion->Annee, 2) . '"';
		$maxMatricule = \DB::scallar($query);
		return ($maxMatricule ? $maxMatricule  + 1 : 1) . '/' . substr($promotion->Annee, 2);
	}

	public static function getByFicheRenseignement($rempli = true){
		$eleves = \Models\Eleve::getList(
			[
				'where' => [
					'INS_PROMO' => \Models\Promotion::promotion_actuelle()->ID,
					'(`INS_DEPART` IS NULL)',
					'((`CRMApplicationFields` '.($rempli?'LIKE':'NOT LIKE').' \'%reglement%\') OR (`CRMApplicationFields` IS NULL))'
				]
			],
			\Models\Eleve::sqlQuery(true) . ' JOIN (SELECT `Eleve` AS `INS_ELEVE` , `Promotion` AS `INS_PROMO`, `Depart` AS `INS_DEPART` FROM `ins_inscriptions`) AS `INS` ON `INS`.`INS_ELEVE` = `gen_eleves`.`ID` '
		);
		return $eleves;
	}

	public function hasFicheRenseigment(){

		if(!$this->CRMApplicationFields)
		return false;

		$crmfields = json_decode($this->CRMApplicationFields,true);

		return (isset($crmfields['reglement'])&&$crmfields['reglement']?true:false);
	}
}
