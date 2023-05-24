<?php

namespace Models;

class Evaluation extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'sco_evaluations';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);

	protected static $fields = array(
		'Unite' => array(
			'fk' => 'Unite',
		),
		'Classe' => array(
			'fk' => 'Classe',
		),
		'Matiere' => array(
			'fk' => 'Matiere',
		),
		'Niveau' => array(
			'fk' => 'Niveau',
		),
		'Enseignant' => array(
			'fk' => 'Enseignant',
		),
		'TypeExam' => array(
			'fk' => 'TypeEvaluation',
		),
		'Semestre' => array(
			'type' => 'int',
		),
		'Rang' => array(
			'type' => 'int',
		),
		'Ennonce' => array(
			'type' => 'varchar',
		),
		'Fait' => array(
			'type' => 'varchar', // implode(',',['date','user','remarque']); 
		),
		'Remarque' => array(
			'type' => 'text',
		),
		'NotesValidees' => array(
			'type' => 'datetime',
		),
		'NotesValideesBy' => array(
			'fk' => 'User',
		),
		'NotesPubliees' => array(
			'type' => 'datetime',
		),
		'NotesPublieesBy' => array(
			'fk' => 'User',
		),
		'CopiesPubliees' => array(
			'type' => 'text',
		),
		'NonComptabilise' => array(
			'type' => 'boolean',
		),
		'Date' => array(
			'type' => 'datetime',
		),
		'Ordre' => array(
			'type' => 'int',
		),
		'By' => array(
			'fk' => 'User',
		),
	);

	public function getEnnonceName()
	{
		$name = $this->get('Cours')->get('Classe')->get('Label') . ' ' . $this->get('Cours')->get('Classe')->get('Promotion')->get('Label');
		$ext = pathinfo($this->get('Ennonce'), PATHINFO_EXTENSION);
		return \Tools::getAlias('ennoncé-examen-' . $name) . '.' . $ext;
	}
	public function getEnnonceLink()
	{
		return \GoogleStorage::getUrl(\Config::get('path-docs-examens') . $this->get('Ennonce'));
	}


	public function deleteNotes()
	{
		$notes = Note::getList(array('where' => array('Evaluation' => $this->get('ID'))));
		foreach ($notes as $item) {
			$item->delete();
		}
	}

	public static function prochains_examens($limit = 10)
	{

		$examens = array();
		$where = array();
		$where[] = 'Date >= \'' . date('Y-m-d') . '\'';
		$examens = Evaluation::getList(array('where' => $where, 'order' => array('Date' => true), 'limit' => $limit));

		return $examens;
	}

	public static function examens_non_faits()
	{

		$examens = array();
		$where = array();


		$where[] = 'Fait IS NULL';
		$examens = Evaluation::getCount(array('where' => $where));

		return $examens ? $examens : 0;
	}

	public static function check_all_notes_validees()
	{

		$examens = array();
		$where = array();
		$where[] = 'NotesValidees IS NULL';
		$examens = Evaluation::getCount(array('where' => $where));

		return ($examens && $examens > 0) ? false : true;
	}


	public function maxNote()
	{
		$niveaux_unites = \Models\NiveauUnite::getList(array('where' => array('Unite' => $this->get('Matiere')->get('ID'), 'Niveau' => $this->get('Niveau')->get('ID'))));
		if (count($niveaux_unites))
			return $niveaux_unites[0];
		return null;
	}


	public static function check_all_notes_saisies()
	{

		$examens = array();
		$where = array();
		$where[] = 'NotesPubliees IS NULL';
		$where[] = 'ID NOT IN (SELECT Evaluation FROM `notes`)';
		$examens = Evaluation::getCount(array('where' => $where));

		return ($examens && $examens > 0) ? false : true;
	}

	public static function check_all_notes_publiees()
	{

		$examens = array();
		$where = array();
		$where[] = 'NotesPubliees IS NULL';
		$examens = Evaluation::getCount(array('where' => $where));

		return ($examens && $examens > 0) ? false : true;
	}



	public function moyenne_generale($limit = 5)
	{

		$notes = Note::getList(array('where' => array('Evaluation' => $this->getPK(true))));
		$total = 0;
		if ($this->get('Classe')) {
			$inscriptions = Inscription::getList(array('where' => array('Classe' => $this->get('Classe')->getPK(true))));
			$count = count($inscriptions);
			foreach ($notes as $note) {
				$total += (float)$note->get('Valeur');
			}
		}

		return number_format($total / ($count ?: 1), 2);
	}

	public function getState()
	{
		$count_inscriptions  = $this->get('Classe')->getCountEleves();
		$count_notes =  Note::getCount(array('where' => array('Evaluation' => $this->getPK(true))));
		if ($count_notes == 0) {
			return -1;
		}
		if ($count_inscriptions <= $count_notes) {
			return 1;
		}
		if ($count_inscriptions != $count_notes) {
			return 0;
		}
	}

	public function getStateColor()
	{
		$state = $this->getState();
		$color = "blue";
		switch ($state) {
			case -1:
				$color = "red";
				break;
			case 0:
				$color = "orange";
				break;
			case 1:
				$color = "green";
				break;
		}
		return $color;
	}





	public static function examensPlanifiesGroupByClasse($inscription)
	{

		$query = <<<END
		SELECT `Inscription` `EncaissementRubrique`, COUNT(*) AS Count FROM `sco_examens`
		JOIN (SELECT `ID` AS `J1ID`, `Classe` AS `J1Classe`, `Annule` AS `J1Annule`, `Valide` AS `J1Valide` FROM `sco_cours`) AS `j1` ON `sco_examens`.`Cours` = `j1`.`J1ID` 
		WHERE Inscription = ?
		GROUP BY `Inscription`
END;

		$params = array($inscription->get('ID'));

		$result = \DB::reader($query, $params);

		$response = array();

		foreach ($result as $data) {

			$response[$data['EncaissementRubrique']] = array(
				'Montant' => $data['Montant'],
			);
		}

		return ($response);
	}

	public function notification($settings_notifications)
	{

		if ($settings_notifications['note']['message']) {

			$variationMsg = $settings_notifications['note']['message'];
			$variationMsg = str_replace("%prenom%", $this->get('Inscription')->get('Eleve')->get('User')->get('Prenom'), $variationMsg);
			$variationMsg = str_replace("%nom%", $this->get('Inscription')->get('Eleve')->get('User')->get('Nom'), $variationMsg);
			$variationMsg = str_replace("%nombre%", $this->get('Retards'), $variationMsg);
		}

		return $variationMsg;
	}

	public function notifier()
	{


		$settings_notifications = array();
		$config = \Models\Config::getByAlias('settings_notifications');
		if ($config) {
			$settings_notifications = json_decode($config->get('Value'), true);
		}

		if (isset($settings_notifications['note']) && !$settings_notifications['note']['enabled'])
			return null;


		$message = $this->notification($settings_notifications);
	}

	/* 
	ALTER TABLE `sco_examens` ADD `Ennonce` VARCHAR(25) NULL AFTER `Remarque`;
	ALTER TABLE `sco_examens` ADD `NotesPubliees` DATETIME NULL AFTER `NotesValideesBy`, ADD `NotesPublieesBy` INT NULL AFTER `NotePubliees`;
	*/

	public function notes()
	{
		return Note::getList(array('where' => array('Evaluation' => $this->ID)));
	}

	public function getNote($inscription)
	{
		$note = Note::hasNote($this->get('ID'), $inscription->get('ID'));
		return  $note ? $note->get('Valeur') : null;
	}

	public static function _getMoyennesUnite($inscription, $prog_niveau_unite, $semestre)
	{

		$MoyenneUnite 	= 0;
		$MoyennesMatieres = array();
		$classe = $inscription->get('Classe');
		$niveau = $inscription->get('Niveau');
		$unite = $prog_niveau_unite->get('Unite');


		/*if ($semestre  == 1) {
			$evalautions_pv =  EvaluationsPv::where(['Classe' => $classe, 'Unite' => $unite, 'Semestre' => $semestre, 'Inscription' => $inscription])->first();
			if ($evalautions_pv) {
				return array('Unite' => $unite, 'MoyenneUnite' => $evalautions_pv->get('Moyenne'), 'NotesMatieres' => $evalautions_pv->getArray('MoyenneMatieres', false, true));
			}
		}*/


		$type_evaluations = $prog_niveau_unite->getProgramationEvaluationsControles($semestre);
		$matieres = $unite->getProgramtionMatieres($niveau->get('ID'));

		foreach ($matieres as $matiere) {
			$evaluations = \Models\Evaluation::getEvaluations($classe, $matiere, $semestre);
			$moyenne_matiere = 0;
			$total_coefficient 		= 0;
			$total_notes_matiere 	= 0;

			foreach ($evaluations as $evaluation) {

				if (!($evaluation->get('TypeExam') && isset($type_evaluations[$evaluation->get('TypeExam')->ID]))) {
					continue;
				}

				$type_evaluation =  $type_evaluations[$evaluation->get('TypeExam')->ID];
				$coefficient = $type_evaluation->coefficient;
				$evaluation_note = $evaluation->getNote($inscription);

				if ($evaluation_note && ($coefficient != 0)) {
					$total_notes_matiere += ($evaluation_note * $coefficient);
					$total_coefficient	 += $coefficient;
				}
			}

			$moyenne_matiere = $total_notes_matiere / ($total_coefficient ?: 1);
			$MoyennesMatieres[$matiere->get('ID')] = array('matiere' => $matiere, 'moyenne_matiere' => $moyenne_matiere);
		}


		//Calaculate Unite Moyenne (loop on moyennes matieres) * getCoefficientEcole ($unite, $matiere, $classe->Niveau)
		$MoyenneUnite = 0;
		foreach ($MoyennesMatieres as $MyenneMatiere) {
			$cf = $MyenneMatiere['matiere']->getCoefficient($niveau);
			$MoyenneUnite += $cf * $MyenneMatiere['moyenne_matiere'];
		}

		$MoyenneUnite = $MoyenneUnite / (count($matieres) ?: 1);

		// if ($semestre == 1) {
		// 	(new EvaluationsPv())
		// 		->set('Inscription', $inscription)
		// 		->set('Classe', $classe)
		// 		->set('Unite', $unite)
		// 		->set('Semestre', $semestre)
		// 		->set('Moyenne', $MoyenneUnite)
		// 		->setJson('MoyenneMatieres', $MoyennesMatieres)
		// 		->save();
		// }

		return array('Unite' => $unite, 'MoyenneUnite' => $MoyenneUnite, 'NotesMatieres' => $MoyennesMatieres);
	}


	public static function getMoyennesUnite($inscription, $prog_niveau_unite, $semestre)
	{

		$MoyenneUnite 	= 0;
		$MoyennesMatieres = array();
		$classe = $inscription->get('Classe');
		$niveau = $inscription->get('Niveau');
		$unite = $prog_niveau_unite->get('Unite');


		/*if ($semestre  == 1) {
			$evalautions_pv =  EvaluationsPv::where(['Classe' => $classe, 'Unite' => $unite, 'Semestre' => $semestre, 'Inscription' => $inscription])->first();
			if ($evalautions_pv) {
				return array('Unite' => $unite, 'MoyenneUnite' => $evalautions_pv->get('Moyenne'), 'NotesMatieres' => $evalautions_pv->getArray('MoyenneMatieres', false, true));
			}
		}*/


		$type_evaluations = $prog_niveau_unite->getProgramationEvaluationsControles($semestre);
		$matieres = $unite->getProgramtionMatieres($niveau->get('ID'));



		foreach ($matieres as $matiere) {

			$evaluations = \Models\Evaluation::getEvaluations($classe, $matiere, $semestre);
			$moyenne_matiere = 0;
			$total_notes_matiere 	= 0;
			$total_coefficient 		= [];

			foreach ($evaluations as $evaluation) {
				if (!($evaluation->get('TypeExam') && isset($type_evaluations[$evaluation->get('TypeExam')->ID]))) {
					continue;
				}

				$type_evaluation =  $type_evaluations[$evaluation->get('TypeExam')->ID];
				$count_evaluation = $type_evaluation->count;
				$coefficient_evaluation = $type_evaluation->coefficient;
				$total_coefficient[$evaluation->get('TypeExam')->ID] =  $coefficient_evaluation;

				$evaluation_note = $evaluation->getNote($inscription);

				if ($evaluation_note) {
					$total_notes_matiere += ($evaluation_note * $coefficient_evaluation * (1 / $count_evaluation));
				}
			}

			$moyenne_matiere = $total_notes_matiere / (array_sum($total_coefficient) ?: 1);
			$MoyennesMatieres[$matiere->get('ID')] = array('matiere' => $matiere, 'moyenne_matiere' => $moyenne_matiere);
		}


		//Calaculate Unite Moyenne (loop on moyennes matieres) * getCoefficientEcole ($unite, $matiere, $classe->Niveau)

		$MoyenneUnite = 0;
		$sum_cf = 0;
		foreach ($MoyennesMatieres as $MyenneMatiere) {
			$sum_cf += $cf = $MyenneMatiere['matiere']->getCoefficient($niveau);
			$MoyenneUnite += $cf * $MyenneMatiere['moyenne_matiere'];
		}

		$MoyenneUnite = $MoyenneUnite / ($sum_cf ?: 1);

		// if ($semestre == 1) {
		// 	(new EvaluationsPv())
		// 		->set('Inscription', $inscription)
		// 		->set('Classe', $classe)
		// 		->set('Unite', $unite)
		// 		->set('Semestre', $semestre)
		// 		->set('Moyenne', $MoyenneUnite)
		// 		->setJson('MoyenneMatieres', $MoyennesMatieres)
		// 		->save();
		// }

		return array('Unite' => $unite, 'MoyenneUnite' => $MoyenneUnite, 'NotesMatieres' => $MoyennesMatieres);
	}


	public static function getMoyennesUniteWithoutExam($inscription, $prog_niveau_unite, $semestre)
	{

		$MoyenneUnite 	= 0;
		$MoyennesMatieres = array();
		$classe = $inscription->get('Classe');
		$niveau = $inscription->get('Niveau');
		$unite = $prog_niveau_unite->get('Unite');


		/*if ($semestre  == 1) {
			$evalautions_pv =  EvaluationsPv::where(['Classe' => $classe, 'Unite' => $unite, 'Semestre' => $semestre, 'Inscription' => $inscription])->first();
			if ($evalautions_pv) {
				return array('Unite' => $unite, 'MoyenneUnite' => $evalautions_pv->get('Moyenne'), 'NotesMatieres' => $evalautions_pv->getArray('MoyenneMatieres', false, true));
			}
		}*/


		$type_evaluations = $prog_niveau_unite->getProgramationEvaluationsControles($semestre);
		$matieres = $unite->getProgramtionMatieres($niveau->get('ID'));


		foreach ($matieres as $matiere) {

			$evaluations = \Models\Evaluation::getEvaluations($classe, $matiere, $semestre);
			$moyenne_matiere = 0;
			$total_notes_matiere 	= 0;
			$total_coefficient 		= [];

			foreach ($evaluations as $evaluation) {
				if (!($evaluation->get('TypeExam') && isset($type_evaluations[$evaluation->get('TypeExam')->ID])) || in_array($evaluation->get('TypeExam')->ID, [3, 4, 5])) {
					continue;
				}

				$type_evaluation =  $type_evaluations[$evaluation->get('TypeExam')->ID];
				$count_evaluation = $type_evaluation->count;
				$coefficient_evaluation = $type_evaluation->coefficient;
				$total_coefficient[$evaluation->get('TypeExam')->ID] =  $coefficient_evaluation;

				$evaluation_note = $evaluation->getNote($inscription);

				if ($evaluation_note) {
					$total_notes_matiere += ($evaluation_note * $coefficient_evaluation * (1 / $count_evaluation));
				}
			}

			$moyenne_matiere = $total_notes_matiere / (array_sum($total_coefficient) ?: 1);
			$MoyennesMatieres[$matiere->get('ID')] = array('matiere' => $matiere, 'moyenne_matiere' => $moyenne_matiere);
		}


		//Calaculate Unite Moyenne (loop on moyennes matieres) * getCoefficientEcole ($unite, $matiere, $classe->Niveau)

		$MoyenneUnite = 0;
		$sum_cf = 0;
		foreach ($MoyennesMatieres as $MyenneMatiere) {
			$sum_cf += $cf = $MyenneMatiere['matiere']->getCoefficient($niveau);
			$MoyenneUnite += $cf * $MyenneMatiere['moyenne_matiere'];
		}

		$MoyenneUnite = $MoyenneUnite / ($sum_cf ?: 1);

		// if ($semestre == 1) {
		// 	(new EvaluationsPv())
		// 		->set('Inscription', $inscription)
		// 		->set('Classe', $classe)
		// 		->set('Unite', $unite)
		// 		->set('Semestre', $semestre)
		// 		->set('Moyenne', $MoyenneUnite)
		// 		->setJson('MoyenneMatieres', $MoyennesMatieres)
		// 		->save();
		// }

		return array('Unite' => $unite, 'MoyenneUnite' => $MoyenneUnite, 'NotesMatieres' => $MoyennesMatieres);
	}

	public static function getMoyennesUniteEvaluation($inscription, $niveaux_unites, $semestre, $type_evaluation, $rang)
	{


		$classe = $inscription->get('Classe');
		$niveau = $inscription->get('Niveau');
		$moyenne = 0;
		$g_sum_cf = 0;
		$moyennes = [];

		foreach ($niveaux_unites as  $prog_niveau_unite) {

			$MoyenneUnite 	= 0;
			$MoyennesMatieres = array();
			$unite = $prog_niveau_unite->get('Unite');
			$matieres = $unite->getProgramtionMatieres($niveau->get('ID'));

			foreach ($matieres as $matiere) {

				$evaluations = \Models\Evaluation::getEvaluations($classe, $matiere, $semestre, $type_evaluation->id, $rang);
				$moyenne_matiere = 0;
				$total_notes_matiere 	= 0;

				foreach ($evaluations as $evaluation) {

					$evaluation_note = $evaluation->getNote($inscription);

					if ($evaluation_note) {
						$total_notes_matiere += ($evaluation_note * $type_evaluation->coefficient);
					}
				}
				$moyenne_matiere = $total_notes_matiere  / ($type_evaluation->coefficient ?: 1);
				$MoyennesMatieres[$matiere->get('ID')] = array('matiere' => $matiere, 'moyenne_matiere' => $moyenne_matiere);
			}
			//Calaculate Unite Moyenne (loop on moyennes matieres) * getCoefficientEcole ($unite, $matiere, $classe->Niveau)
			$MoyenneUnite = 0;
			$sum_cf = 0;
			foreach ($MoyennesMatieres as $MyenneMatiere) {
				$sum_cf += $cf = $MyenneMatiere['matiere']->getCoefficient($niveau);
				$MoyenneUnite += $cf * $MyenneMatiere['moyenne_matiere'];
			}

			$MoyenneUnite = $MoyenneUnite / ($sum_cf ?: 1);

			$unite_cf = $unite->getCoefficient($niveau, 'Coefficient_Ecole');
			$g_sum_cf += $unite_cf;
			$moyenne += $unite_cf * $MoyenneUnite;

			$moyennes[$unite->ID] = array('Unite' => $unite, 'MoyenneUnite' => $MoyenneUnite, 'NotesMatieres' => $MoyennesMatieres);
		}

		return  ['Moyenne' => $moyenne / $g_sum_cf, 'Moyennes' => $moyennes];
	}

	public static function getMoyeneClasseInscriptionsUnite($classe, $inscriptions, $niveau_unite, $semestre)
	{
		$unite = $niveau_unite->get('Unite');
		$total_moyenne_unite = 0;
		$moyenne_unite = 0;
		$moyenne_matieres =  array();
		$total_moyenne_matieres =  array();

		$ranges = [];

		$count_inscriptions = count($inscriptions);
		foreach ($inscriptions as $inscription) {

			$moyenne_unite =  self::getMoyennesUnite($inscription, $niveau_unite, $semestre);
			$total_moyenne_unite += $moyenne_unite['MoyenneUnite'];
			$ranges[$inscription->get('ID')] = $moyenne_unite['MoyenneUnite'];

			foreach ($moyenne_unite['NotesMatieres'] as $key => $item) {
				if (!isset($total_moyenne_matieres[$key])) {
					$total_moyenne_matieres[$key] = 0;
				}
				$total_moyenne_matieres[$key] += $item['moyenne_matiere'];
			}
		}

		foreach ($total_moyenne_matieres as $key => $moyenne_matiere) {
			$moyenne_matieres[$key] = $moyenne_matiere / $count_inscriptions;
		}

		$moyenne_unite  = $total_moyenne_unite / $count_inscriptions;

		// ! Sort an descending  
		arsort($ranges);

		$orders = array();
		foreach ($ranges as $key => $value) {

			array_push($orders, $key);
		}

		return array(
			'MoyenneUnite' => $moyenne_unite,
			'NotesMatieres' => $moyenne_matieres,
			'orders' => $orders
		);
	}

	public static function getEvaluations($classe, $matiere, $semestre, $type_evaluation = null, $rang = null)
	{

		$where =  array(
			'Classe' => $classe->ID,
			'Matiere' => $matiere->ID,
			'Semestre' => $semestre,
		);

		if ($type_evaluation) {
			$where['TypeExam'] = $type_evaluation;
		}

		if (!is_null($rang)) {
			$where['Rang'] = $rang;
		}

		return self::getList(array('where' => $where));
	}

	public static function getMoyennesUniteTypesEvaluations($inscription, $prog_niveau_unite, $semestre)
	{

		$classe = $inscription->get('Classe');
		$niveau = $inscription->get('Niveau');
		$unite = $prog_niveau_unite->get('Unite');
		$type_evaluations_moyenne = array();
		$type_evaluations = $prog_niveau_unite->getProgramationEvaluationsControles($semestre);
		$matieres = $unite->getProgramtionMatieres($niveau->get('ID'));
		foreach ($type_evaluations as  $type_id => $type_evaluation) {
			$MoyenneUnite 	= 0;
			$MoyennesMatieres = array();
			foreach ($matieres as $matiere) {
				$evaluations = \Models\Evaluation::getEvaluations($classe, $matiere, $semestre, $type_id);

				if (!count($evaluations)) {
					continue;
				}

				$moyenne_matiere = 0;
				$total_coefficient 		= 0;
				$total_notes_matiere 	= 0;
				foreach ($evaluations as $evaluation) {
					$coefficient = $type_evaluation->coefficient;
					$evaluation_note = $evaluation->getNote($inscription);

					if ($evaluation_note && ($coefficient != 0)) {
						$total_notes_matiere += ($evaluation_note * $coefficient);
						$total_coefficient	 += $coefficient;
					}
				}

				$moyenne_matiere = $total_notes_matiere / ($total_coefficient ?: 1);

				$MoyennesMatieres[$matiere->get('ID')] = array('matiere' => $matiere, 'moyenne_matiere' => $moyenne_matiere);
			}

			$MoyenneUnite = 0;
			foreach ($MoyennesMatieres as $MyenneMatiere) {
				$cf = $MyenneMatiere['matiere']->getCoefficient($niveau);
				$MoyenneUnite += $cf * $MyenneMatiere['moyenne_matiere'];
			}

			if ($type_evaluation->code != 'ExLocal') {
				$MoyenneUnite = $MoyenneUnite / (count($matieres) ?: 1);
			}

			$type_evaluations_moyenne[$type_evaluation->code] = array('Unite' => $unite, 'MoyenneUnite' => $MoyenneUnite, 'NotesMatieres' => $MoyennesMatieres);
		}

		return  $type_evaluations_moyenne;
	}

	public function nemuroEvalaution()
	{
		$evaluations = self::where(
			array(
				'Classe' => $this->Classe->ID,
				'Matiere' => $this->Matiere->ID,
				'Semestre' => $this->Semestre,
				'TypeExam' => $this->TypeExam->ID,
			)
		)->get();

		foreach ($evaluations as $i => $eva) {

			if ($this->ID == $eva->ID) {
				return $i;
			}
		}
	}

	public function getRang()
	{
		if (is_null($this->Rang)) {
			$evaluations = self::getEvaluations($this->Classe, $this->Matiere, $this->Semestre, $this->TypeExam->ID);
			foreach ($evaluations as $key => $eva) {
				if ($eva->ID == $this->ID) {
					$this->Rang = $key;
					$this->save();
					break;
				}
			}
		}

		return $this->Rang;
	}

	public static function getClassessments($classe, $inscriptions, $niveau_unite, $semestre)
	{
		$unite = $niveau_unite->get('Unite');
		$moyennnes =  array();
		foreach ($inscriptions as $inscription) {
			$moyenne_unite =  self::getMoyennesUnite($inscription, $niveau_unite, $semestre);
			$moyennnes[] = $moyenne_unite['MoyenneUnite'];
		}
		rsort($moyennnes);
		return 	$moyennnes;
	}
}
