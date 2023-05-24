<?php

namespace Models;

class Enseignant extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'gen_enseignants';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'User' => array(
			'fk' => 'User',
		),
		'Sites' => array(
			'type' => 'varchar',
		),

		'DateEmbauche' => array(
			'type' => 'date',
		),
	);



	public function getEnsNiveaux()
	{
		$where = array();
		$where[] = "ID IN (SELECT Niveau from ins_classes where ID IN (SELECT Classe FROM sco_enseignantclasses where Enseignant = " . $this->ID . ") )";
		$niveaux = Niveau::getList(array('where' => $where));

		return $niveaux;
	}

	public function getEnsMatieres()
	{

		$where = array();
		$where[] = "Unite IN (SELECT Unite from  sco_enseignantunite where Enseignant = " . $this->ID . " )";
		$matieres = Matiere::getList(array('where' => $where));

		return $matieres;
	}


	// unused
	public function getMatieres()
	{
		$enseignantMatieres = array();
		$items = EnseignantMatiere::getList(array('where' => array('Enseignant' => $this->get('ID', null, false))));
		if (!$items)
			return array();
		foreach ($items as $item)
			$enseignantMatieres[$item->get('Matiere')->get('ID')] = $item;
		return $enseignantMatieres;
	}


	public function getUnites()
	{
		$enseignantMatieres = array();
		$items = EnseignantUnite::getList(array('where' => array('Enseignant' => $this->get('ID', null, false))));
		if (!$items)
			return array();
		foreach ($items as $item)
			try {
				$item->get('Unite')->get('ID');
				if ($item->get('Unite'))
					$enseignantMatieres[$item->get('Unite')->get('ID')] = $item;
			} catch (\Throwable $th) {
				continue;
			}

		return $enseignantMatieres;
	}

	public function deleteMatieres()
	{
		$enseignantMatieres = $this->getMatieres();
		foreach ($enseignantMatieres as $item)
			$item->delete();
	}




	public function getMatieresLabels()
	{
		$enseignantMatieresLabels = array();
		$items = EnseignantMatiere::getList(array('where' => array('Enseignant' => $this->get('ID', null, false))));
		if (!$items)
			return array();
		foreach ($items as $item)
			$enseignantMatieresLabels[] = $item->get('Matiere')->get('Label');
		return $enseignantMatieresLabels;
	}


	public function getClasses()
	{
		$enseignantClasses = array();
		$items = EnseignantClasse::getList(array('where' => array('Enseignant' => $this->get('ID', null, false))));
		if (!$items)
			return array();
		foreach ($items as $item) {

			if ($item && $item->get('Classe'))
				$enseignantClasses[$item->get('Classe')->get('ID')] = $item;
		}
		return $enseignantClasses;
	}

	public function getClassesLabel()
	{
		$classesLabel = '';
		$items = EnseignantClasse::getList(array('where' => array('Enseignant' => $this->get('ID', null, false))));
		if (!$items)
			return $classesLabel;
		foreach ($items as $item)
			$classesLabel .= $item->get('Classe')->get('Label') . ' ';
		return $classesLabel;
	}

	public function getClassesLabels()
	{
		$enseignantClasses = array();
		$items = EnseignantClasse::getList(array('where' => array('Enseignant' => $this->get('ID', null, false))));
		if (!$items)
			return array();
		foreach ($items as $item)
			$enseignantClasses[] = $item->get('Classe')->get('Label');
		return $enseignantClasses;
	}

	public function deleteClasses()
	{
		$enseignantClasses = $this->getClasses();
		foreach ($enseignantClasses as $item) {
			$item->delete();
		}
	}


	public function getNiveaux()
	{
		$enseignantNiveaux = array();
		$enseignantClasses = $this->getClasses();
		foreach ($enseignantClasses as $item) {
			$enseignantNiveaux[$item->Classe->get('Niveau')->get('ID')] = $item->Classe->get('Niveau');
		}

		return $enseignantNiveaux;
	}


	public function getNiveauxLabels()
	{
		$enseignantNiveaux = array();
		$items = $this->getNiveaux();
		if (!$items)
			return array();
		foreach ($items as $item)
			$enseignantNiveaux[] = $item->get('Label');
		return $enseignantNiveaux;
	}


	public function deleteNiveaux()
	{
		$enseignantNiveaux = $this->getNiveaux();
		foreach ($enseignantNiveaux as $item)

			$item->delete();
	}

	public function deleteUnites()
	{
		$enseignantUnites = $this->getUnites();
		foreach ($enseignantUnites as $item)
			$item->delete();
	}

	public function getNextOnlineSessions()
	{
		$seances = array();
		$days_dates = \Models\ETD\Edt::weekDates();
		$cours = \Models\ETD\Seance::getList(array('where' => array(
			'Enseignant' => $this->get('ID'),
		), 'order' => array('Day' => true, 'From' => true)));

		foreach ($cours as $item) {
			if ($item->get('Day') >= (int)date('N', strtotime(date('Y-m-d')))) {
				$seanceTracking  =  \Models\ETD\SeanceTracking::getSeanceTracking($item, $days_dates[$item->get('Day')], $item->get('From'), $item->get('To'));
				$seances[] = $seanceTracking;
			}
		}

		return $seances;
	}

	public function getLastSessions($limit = null)
	{
		$seances = array();
		$days_dates = \Models\ETD\Edt::weekDates();
		$cours = \Models\ETD\Seance::getList(array('where' => array(
			'Enseignant' => $this->get('ID'),
		), 'order' => array('Day' => true, 'From' => true)));

		foreach ($cours as $item) {
			if ($item->get('Day') <= date('N', strtotime(date('Y-m-d')))) {
				$seanceTracking  =  \Models\ETD\SeanceTracking::getSeanceTracking($item, $days_dates[$item->get('Day')], $item->get('From'), $item->get('To'));
				$seances[] = $seanceTracking;
			}
		}

		return $seances;
	}

	public function __getNextOnlineSessions()
	{

		$dateEnd = new \Datetime();
		$dateEnd->modify('sunday last week');

		// $dateStart = clone $dateEnd;
		$dateStart = date('Y-m-d');
		$dateEnd->add(new \DateInterval('P6D'));
		$periodInterval = new \DateInterval('P1D');

		// 		$query = Cours::sqlQuery(true);
		// 		$query .= <<<END
		// 		JOIN (SELECT `ID` AS `J1ID`, `Heure_Debut` AS `J1Heure_Debut` FROM `sco_seances`) AS `j1` ON `sco_cours`.`Seance` = `j1`.`J1ID`
		// END;

		// 		$cours = array();
		// 		$cours = Cours::getList(array('where' => array(
		// 			'Annule IS NULL',
		// 			//'MeetID IS NOT NULL',
		// 			'Enseignant' => $this->get('ID'),
		// 			'Date BETWEEN \'' . \Tools::dateFormat($dateStart, '%Y-%m-%d') . '\' AND \'' . \Tools::dateFormat($dateEnd, '%Y-%m-%d') . '\''
		// 		), 'order' => array('Date' => true, 'J1Heure_Debut' => true), 'limit' => 8), $query);

		$cours = array();
		$cours = Cours::getList(array('where' => array(
			'Annule IS NULL',
			//'MeetID IS NOT NULL',
			'Enseignant' => $this->get('ID'),
			'Date BETWEEN \'' . \Tools::dateFormat($dateStart, '%Y-%m-%d') . '\' AND \'' . \Tools::dateFormat($dateEnd, '%Y-%m-%d') . '\''
		), 'order' => array('Date' => true, 'HeureDebut' => true), 'limit' => 8));

		return $cours;
	}

	public function _getLastSessions($limit = null)
	{
		$dateEnd = new \Datetime();


		$dateEnd->sub(new \DateInterval('P1D'));

		$dateStart = clone $dateEnd;
		$dateStart->sub(new \DateInterval('P2M'));

		// 		$query = Cours::sqlQuery(true);
		// 		$query .= <<<END
		// 		JOIN (SELECT `ID` AS `J1ID`, `Heure_Debut` AS `J1Heure_Debut` FROM `sco_seances`) AS `j1` ON `sco_cours`.`Seance` = `j1`.`J1ID`
		// END;

		// 		$cours = array();
		// 		$cours = Cours::getList(array('where' => array(
		// 			'Annule IS NULL',
		// 			'Enseignant' => $this->get('ID'),
		// 			'Date BETWEEN \'' . \Tools::dateFormat($dateStart, '%Y-%m-%d') . '\' AND \'' . \Tools::dateFormat($dateEnd, '%Y-%m-%d') . '\''
		// 		),  'order' => array('Date' => false, 'J1Heure_Debut' => true), 'order' => $limit), $query);

		$cours = array();
		$cours = Cours::getList(array('where' => array(
			'Annule IS NULL',
			'Enseignant' => $this->get('ID'),
			'Date BETWEEN \'' . \Tools::dateFormat($dateStart, '%Y-%m-%d') . '\' AND \'' . \Tools::dateFormat($dateEnd, '%Y-%m-%d') . '\''
		),  'order' => array('Date' => false, 'HeureDebut' => true), 'order' => $limit));

		//print_r(count($cours));exit();

		return $cours;
	}




	public function __getExamens($limit = null)
	{


		$query = Examen::sqlQuery(true);
		$query .= <<<END
			JOIN (SELECT `ID` AS `J1ID`, `Date` AS `J1Date`, `Enseignant` AS `J1Enseignant`, `Classe` AS `J1Classe`, `Valide` AS `J1Valide`, `Annule` AS `J1Annule`, `Matiere` AS `J1Matiere` FROM `sco_cours`) AS `j1` ON `sco_examens`.`Cours` = `j1`.`J1ID` 
END;
		$where 	 = array();
		$where[] = 'J1Annule IS NULL';
		$where[] = "J1Date > '2020-09-01'";
		$where[] = 'J1Enseignant = ' . $this->get('ID');
		$examens = Examen::getList(array('where' => $where, 'order' => array('J1Date' => false), 'limit' => $limit), $query);
		return $examens;
	}

	public function getExamens($limit = null)
	{
		$where 	 = array();
		$where[] = "Date > '2020-09-01'";
		$where[] = 'Enseignant = ' . $this->get('ID');
		//$where[] = 'Classe IN(select ID FROM ins_classes WHERE Promotion IN (SELECT ID FROM  gen_promotions WHERE Actuelle = 1))';
		$examens = Evaluation::getList(array('where' => $where, 'order' => array('Date' => false), 'limit' => $limit));

		return $examens;
	}

	public function getRessources($limit = null)
	{
		$qlimit = array();
		$ressources = array();
		if ($limit)
			$qlimit = array('limit' => $limit);

		try {
			$categorie = PostCategorie::getByAlias('ressource');
		} catch (Exception $e) {
			exit('Element introuvable');
		}


		$where 					= array();
		$where['Enseignant'] 	= $this->get('ID');
		$ressources 			= Post::getList(array('where' => $where, 'order' => array('DatePublication' => false, 'Date' => true), 'limit' => $limit));

		return $ressources;
	}
	public function getDevoirs($limit = null)
	{
		$devoirs = array();


		try {
			$categorie = PostCategorie::getByAlias('cahier-de-liaison');
		} catch (Exception $e) {
			exit('Element introuvable');
		}


		$where 					= array();
		$where['Enseignant'] 	= $this->get('ID');
		$devoirs 			= Post::getList(array('where' => $where, 'order' => array('DatePublication' => false), 'limit' => $limit));

		return $devoirs;
	}

	public function getNews($limit = null)
	{
		$posts = Post::getList(array('where' => array('Teachers' => true), 'order' => array('DatePublication' => false), 'limit' => $limit));
		return $posts;
	}


	public static function tokens()
	{
		$tokens = array(
			'ios' => array(),
			'android' => array(),
		);

		$items = Enseignant::getList();
		if (!$items)
			return null;
		foreach ($items as $item) {
			if ($item->User->get('TokenID'))
				$tokens[$item->User->get('TokenDevice')][] = $item->User->get('TokenID');
		}
		return $tokens;
	}

	public static function simpleTokens()
	{
		$tokens = array();
		$items = Enseignant::getList();
		if (!$items)
			return array();

		foreach ($items as $item) {
			if ($user_tokens =  $item->User->getArray('TokenID', true)) {
				foreach ($user_tokens as  $token) {
					$tokens[] = $token;
				}
			}
		}
		return $tokens;
	}
	public function getGroupes()
	{
		$groupes = Enseignantsactivities::where(array('Enseignant' => $this->get('ID')))->get();
		$groupes_id = [];
		foreach ($groupes as $groupe) {
			array_push($groupes_id, $groupe->get('Groupe')->get('ID'));
		};
		return $groupes_id;
	}
	public function getActivities()
	{
		$activities = Enseignantsactivitiesrubriques::where(array('Enseignant' => $this->get('ID')))->get();
		$activities_id = [];
		foreach ($activities as $activity) {
			array_push($activities_id, $activity->get('Rubrique')->get('ID'));
		};
		return $activities_id;
	}
	public function getEnseingantGroupes()
	{
		$groupes = Enseignantsactivities::where(array('Enseignant' => $this->get('ID')))->get();
		return $groupes;
	}
	public function getEnseingantActivities()
	{
		$activities = Enseignantsactivitiesrubriques::where(array('Enseignant' => $this->get('ID')))->get();
		return $activities;
	}
	public static function phones()
	{
		$items = Enseignant::getList();
		$phones = array();
		if (!$items)
			return null;
		foreach ($items as $item) {
			try {
				if ($item->get('User'))
					$phones[] = $item->get('User')->get('Tel');
			} catch (\Exception $th) {
				continue;
			}
		}
		return $phones;
	}

	public static function getList($args = null, $query = null)
	{
		if (!is_array($args))
			$args = array();

		if (!$query)
			$query = Enseignant::sqlQuery();

		$args['where'][] = '`User` NOT IN (SELECT `ID` FROM `users` WHERE `DeletedAt` IS NOT NULL)';
		$user = \Session::getInstance()->getCurUser();
		if ($user && $user->get('Classes')) {
			$classes =  $user->get('Classes');
			$args['where'][] = "ID IN (SELECT Enseignant  FROM  sco_enseignantclasses WHERE  Classe IN (" . $classes . "))";
		}

		return parent::getList($args, $query);
	}

	public function getClasseAffectations()
	{
		$htmlAccess = '';
		$classes = $this->getClasses();
		if ($classes) {
			if (count($classes) > 3) {
				$htmlAccess .= '<span class="tag tag-sm tag-info ">' . count($classes) . ' Classes</span>';
			} else {
				foreach ($classes as $cl) {
					$htmlAccess .= '<span class="tag tag-sm tag-info ">' . $cl->get('Classe')->get('Label') . '</span> ';
				}
			}
		} else {
			$htmlAccess .= '<span class="tag tag-sm tag-danger ">Non définie</span>';
		}

		return $htmlAccess;
	}
	public function getNiveauAffectations()
	{
		$htmlAccess = '';
		$niveaux = $this->getNiveaux();
		if ($niveaux) {
			if (count($niveaux) > 3) {
				$htmlAccess .= '<span class="tag tag-sm tag-info ">' . count($niveaux) . ' Niveaux</span>';
			} else {
				foreach ($niveaux as $nv) {
					$htmlAccess .= '<span class="tag tag-sm tag-info ">' . $nv->get('Niveau')->get('Label') . '</span> ';
				}
			}
		} else {
			$htmlAccess .= '<span class="tag tag-sm tag-danger ">Non défini</span>';
		}

		return $htmlAccess;
	}
	public function getMatiereAffectations()
	{
		$htmlAccess = '';
		$matieres = $this->getMatieres();
		if ($matieres) {
			if (count($matieres) > 3) {
				$htmlAccess .= '<span class="tag tag-sm tag-info ">' . count($matieres) . ' Matières</span>';
			} else {
				foreach ($matieres as $mt) {
					$htmlAccess .= '<span class="tag tag-sm tag-info ">' . $mt->get('Matiere')->get('Label') . '</span> ';
				}
			}
		} else {
			$htmlAccess .= '<span class="tag tag-sm tag-danger ">Non définie</span>';
		}

		return $htmlAccess;
	}
	public function getUniteAffectations()
	{
		$htmlAccess = '';
		$unites = $this->getUnites();
		if ($unites) {
			if (count($unites) > 3) {
				$htmlAccess .= '<span class="tag tag-sm tag-info ">' . count($unites) . ' Unitès</span>';
			} else {
				foreach ($unites as $mt) {
					$htmlAccess .= '<span class="tag tag-sm tag-info ">' . $mt->get('Unite')->get('Label') . '</span> ';
				}
			}
		} else {
			$htmlAccess .= '<span class="tag tag-sm tag-danger ">Non définie</span>';
		}

		return $htmlAccess;
	}
}
