<?php

namespace Models;

use Session;

class DisciplineAction extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'disciplineactions';

	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'DisciplineActionType' => array(
			'fk' => 'DisciplineActionType',
		),
		'Cours' => array(
			'fk' => 'ETD\\SeanceTracking',
		),
		'Inscription' => array(
			'fk' => 'Inscription',
		),
		'Professeur' => array(
			'fk' => 'User',
		),
		'Matiere' => array(
			'fk' => 'Unite',
		),
		'Label' => array(
			'type' => 'varchar',
		),
		'Commentaire' => array(
			'type' => 'varchar',
		),
		'UserBy' => array(
			'fk' => 'User',
		),
		'Valeur' => array(
			'type' => 'int',
		),
		'DateAction' => array(
			'type' => 'date',
		),
		'Date' => array(
			'type' => 'datetime',
		),
		'Reseted' => array(
			'type' => 'datetime',
		),
		'ValidateAt' => array(
			'type' => 'date',
		),
		'ValidateBy' => array(
			'fk' => 'User',
		),
		'ResetedBy' => array(
			'fk' => 'User',
		),
		'Compense' => array(
			'fk' => 'Compensation',
		),
		'DeletedAt' => array(
			'type' => 'varchar',
		),
	);

	public function notification()
	{

		$variationMsg = 'Nous vous informons que ' . $this->get('Inscription')->get('Eleve')->get('User')->get('Prenom');

		if ($this->get('DisciplineActionType')->get('Nature')->get('ID') == 1) {
			$variationMsg .= ' a eu un avertissement aujourd\'hui pour : ' . $this->get('DisciplineActionType')->get('Label');
		} elseif ($this->get('DisciplineActionType')->get('Nature')->get('ID') == 2) {
			$variationMsg .= ' a eu une contravention aujourd\'hui pour : ' . $this->get('DisciplineActionType')->get('Label');
		} elseif ($this->get('DisciplineActionType')->get('Nature')->get('ID') == 3) {
			$variationMsg .= ' a obtenu un Mérite aujourd\'hui.';
		} elseif ($this->get('DisciplineActionType')->get('Nature')->get('ID') == 4) {
			$variationMsg .= ' a obtenu un billet de valorisation aujourd\'hui.';
		} else {
			$variationMsg .= ' a fait une action disciplinaire aujourd\'hui.';
		}

		return $variationMsg;
	}


	public function notifier()
	{
		$eleve = $this->get('Inscription')->get('Eleve');

		//$notifLabel = 'Action disciplinaire';
		$notifLabel = $this->get('DisciplineActionType')->get('Label');

		$notifMessage = $this->notification();

		$tokens = $eleve->simpleTokens();
		$has_tokens = count($tokens);
		$parents = $eleve->ParentsPhone();
		if (isAllowed('notification_sms_discpline_all')) {
			fcm_send($tokens, $notifLabel, $notifMessage, 'eleve_discipline', null, $eleve->ID);
			foreach ($parents as $user) {
				if ($user->EnableSmsNotif) {
					smsCasaNet($user->Tel, $notifMessage);
					NotificationSms::create(array(
						'User' => Session::getInstance()->getCurUser(),
						'UserTo' => $user,
						'UserToName' => $user->getNomComplet(),
						'GSM' => $user->Tel,
						'Inscription' => $this->Inscription,
						'Action' => "Discipline",
						'Message' => $notifMessage,
						'Date' => date('Y-m-d H:i:s'),
					));
					ActionLog::catchLog('send_discipline_sms_(' . $user->Tel . ')');
				}
			}
		} else if (isAllowed('notification_sms_discpline_no_token')) {

			if (!$has_tokens) {
				foreach ($parents as $user) {
					if ($user->EnableSmsNotif) {
						smsCasaNet($user->Tel, $notifMessage);
						NotificationSms::create(array(
							'User' => Session::getInstance()->getCurUser(),
							'UserTo' => $user,
							'UserToName' => $user->getNomComplet(),
							'GSM' => $user->Tel,
							'Inscription' => $this->Inscription,
							'Action' => "Discipline",
							'Message' => $notifMessage,
							'Date' => date('Y-m-d H:i:s'),
						));
						ActionLog::catchLog('send_discipline_sms_(' . $user->Tel . ')');
					}
				}
			} else {
				fcm_send($tokens, $notifLabel, $notifMessage, 'eleve_discipline', null, $eleve->ID);
			}
		} else {
			fcm_send($tokens, $notifLabel, $notifMessage, 'eleve_discipline', null, $eleve->ID);
		}
	}



	public function _notifier()
	{

		$tokensAndroid = array();
		$tokensIos = array();
		$parrainages = Parrainage::getList(array('where' => array('Eleve' => $this->get('Inscription')->get('Eleve')->get('ID'))));
		$notifLabel = 'Action disciplinaire';
		$message = $this->notification();

		foreach ($parrainages as $item) {

			if (!$item->get('Parent')->get('User')->get('TokenID'))
				continue;

			if ($item->get('Parent')->get('User')->get('TokenDevice') == 'ios')
				$tokensIos[] = $item->get('Parent')->get('User')->get('TokenID');
			else
				$tokensAndroid[] = $item->get('Parent')->get('User')->get('TokenID');
		}

		_snf(array('android' => $tokensAndroid, 'ios' => $tokensIos), $notifLabel, $message, 'eleve_discipline');


		$notif = new Notif();
		$notif
			->set('Label', $notifLabel)
			->set('Inscription', $this->get('Inscription'))
			->set('Message', $message)
			->set('Date', date('Y-m-d H:i:s'));
		$notif->save();
	}

	public static function getList($args = null, $query = null)
	{
		if (!is_array($args))
			$args = array();
		$user = \Session::getInstance()->getCurUser();
		$args['where'][] = "(`DeletedAt` IS NULL)";


		if ($user && $user->get('Classes')) {
			$classes =  $user->get('Classes');
			$args['where'][] = "(Inscription IN (SELECT ID FROM ins_inscriptions where Classe IN (" . $classes . ")))";
		}

		return parent::getList($args, $query);
	}

	public static function getEleveActionData($inscription, $date, $cours = null)
	{
		$where = array(
			'Inscription' => $inscription->get('ID'),
			'DateAction' => $date,
		);

		if ($cours) {
			$where['Cours'] = $cours->ID;
		}

		$actions = static::getList(array('where' => $where));

		$actionsData = array();
		foreach ($actions as $action) {
			$actionsData[] = array(
				'id' => $action->get('ID'),
				'type' => $action->get('DisciplineActionType')->get('Label'),
				'cours' => $action->get('Matiere') ? $action->get('Matiere')->get('Label') : '',
				'courID' => $action->get('Cours') ? $action->get('Cours')->ID : '',
				'valeur' => $action->get('Valeur')
			);
		}

		return $actionsData;
	}

	public static function eleveScore($inscription = null)
	{

		$result = array();
		$result['label'] = 'Etat de discipline actuel';
		$result['description'] = 'Compteur est mis à jour au fur et à mesure que votre enfant accumule les bons ou mauvais comportements au sein de l’école. Réinitialisé à la fin de chaque période';
		$result['score'] = 0;
		$result['icone'] = '';
		$result['color'] = '';
		$result['actions']['data'] = array();
		$result['actions']['empty'] = false;
		$result['actions']['empty_icon'] = \URL::absolute(\URL::base('assets/icons/empty.png'));
		$result['actions']['empty_text'] = 'Aucune donnée pour le moment.';
		$result['actions']['title'] = 'Historique des actions de discipline';

		if (!$inscription)
			return $result;

		// $periode = Periode::periodeActuelle();

		// $promotions = Promotion::getList(array('where' => array('Actuelle' => true)));
		// if($promotions){
		// $yearNow = $promotions[0]->get('Annee') - 1 ;
		// $nextYear = $promotions[0]->get('Annee');
		// }else {

		// $yearNow = date('Y');
		// $nextYear = date('Y') + 1;
		// }

		$disciplineactionst = null;
		// if($inscription && $periode){
		if ($inscription) {

			$wheredisciplineactionst = array();
			$wheredisciplineactionst['Inscription'] = $inscription->get('ID');
			// if($periode->get('MoisDebut') < $periode->get('MoisFin') && $periode->get('MoisDebut') > 8)
			// $wheredisciplineactionst[] = 'DateAction BETWEEN CONCAT(\''.$yearNow.'-\' ,LPAD('.$periode->get('MoisDebut').', 2, 0), \'-\', LPAD('.$periode->get('JourDebut').', 2, 0)) AND  CONCAT(\''.$yearNow.'-\', LPAD('.$periode->get('MoisFin').', 2, 0), \'-\', LPAD('.$periode->get('JourFin').', 2, 0))';
			// elseif($periode->get('MoisDebut') < $periode->get('MoisFin'))
			// $wheredisciplineactionst[] = 'DateAction BETWEEN CONCAT(\''.$nextYear.'-\' ,LPAD('.$periode->get('MoisDebut').', 2, 0), \'-\', LPAD('.$periode->get('JourDebut').', 2, 0)) AND  CONCAT(\''.$nextYear.'-\', LPAD('.$periode->get('MoisFin').', 2, 0), \'-\', LPAD('.$periode->get('JourFin').', 2, 0))';
			// else
			// $wheredisciplineactionst[] = 'DateAction BETWEEN CONCAT(\''.$yearNow.'-\' ,LPAD('.$periode->get('MoisDebut').', 2, 0), \'-\', LPAD('.$periode->get('JourDebut').', 2, 0)) AND  CONCAT(\''.$nextYear.'-\', LPAD('.$periode->get('MoisFin').', 2, 0), \'-\', LPAD('.$periode->get('JourFin').', 2, 0))';

			$disciplineactionst = DisciplineAction::getList(array('where' => $wheredisciplineactionst));
		}

		$disciplineNbr = 0;
		if ($disciplineactionst) {
			foreach ($disciplineactionst as $disciplineactions) {
				if ($disciplineactions->get('ValidateAt')) {
					if (!$disciplineactions->get('DisciplineActionType'))
						continue;
					// if($disciplineactions->get('DisciplineActionType')->get('Flag'))
					$disciplineNbr += $disciplineactions->get('Valeur');
					// else
					// $disciplineNbr -= $disciplineactions->get('Valeur');

					$matiere = ($disciplineactions->get('Matiere') ? $disciplineactions->get('Matiere')->get('Label') : '');
					$actionData = array(
						'id' => $disciplineactions->get('ID'),
						'date' => \Tools::dateFormat($disciplineactions->get('DateAction')),
						'day' => \Tools::dateFormat($disciplineactions->get('DateAction'), '%d'),
						'month' => \Tools::dateFormat($disciplineactions->get('DateAction'), '%h'),
						'nature' => $disciplineactions->get('DisciplineActionType')->get('Nature')->get('Label'),
						'type' => $disciplineactions->get('DisciplineActionType')->get('Label'),
						'couleur' => $disciplineactions->get('DisciplineActionType')->get('Flag') ? '#75c228' : '#f5393a',
						'flag' => $disciplineactions->get('DisciplineActionType')->get('Flag'),
						'commentaire' => $disciplineactions->get('Commentaire') . $matiere
					);


					if (_school_alias != 'mariefrance') {
						if ($disciplineactions->get('Valeur') > 0)
							$actionData['valeur'] = '+' . $disciplineactions->get('Valeur');
						else
							$actionData['valeur'] = $disciplineactions->get('Valeur');
					} else {
						$actionData['valeur'] = ' ';
					}

					$result['actions']['data'][$disciplineactions->get('DateAction')] = $actionData;
				}
			}
		}

		// Cadeau gagnés
		$query = 'SELECT SUM(Valeur) FROM `compensations` WHERE `Inscription`=:inscription ';
		$params = array('inscription' => $inscription->get('ID'));
		$compensation = \DB::scallar($query, $params);

		if ($compensation)
			$disciplineNbr -= $compensation;

		if ($disciplineNbr > 0)
			$result['score'] = '+' . $disciplineNbr;
		else
			$result['score'] = $disciplineNbr;

		if ($disciplineNbr > 0) {
			$result['icone'] = \URL::absolute(\URL::base('assets/img/svg/smile.svg'));
			$result['color'] = '#75c228';
		} elseif ($disciplineNbr == 0) {
			$result['icone'] = \URL::absolute(\URL::base('assets/img/svg/neutre.svg'));
			$result['color'] = '#006df0';
		} elseif ($disciplineNbr < 0) {
			$result['icone'] = \URL::absolute(\URL::base('assets/img/svg/sad.svg'));
			$result['color'] = '#f5393a';
		}

		if (isAllowed('hide_discipline_score'))
			$result['score'] = null;

		if (count($result['actions']['data']) == 0) {
			$result['actions']['empty'] = true;
		}

		return $result;
	}


	public static function newEleveScore($inscription = null)
	{
		$result = array(
			'score' => 0,
			'data' => array(),
		);
		// $periode = Periode::periodeActuelle();

		// $promotions = Promotion::getList(array('where' => array('Actuelle' => true)));
		// if($promotions){
		// $yearNow = $promotions[0]->get('Annee') - 1 ;
		// $nextYear = $promotions[0]->get('Annee');
		// }else {

		// $yearNow = date('Y');
		// $nextYear = date('Y') + 1;
		// }

		$disciplineactionst = null;
		// if($inscription && $periode){
		if ($inscription) {

			$wheredisciplineactionst = array();
			$wheredisciplineactionst['Inscription'] = $inscription->get('ID');
			// if($periode->get('MoisDebut') < $periode->get('MoisFin') && $periode->get('MoisDebut') > 8)
			// $wheredisciplineactionst[] = 'DateAction BETWEEN CONCAT(\''.$yearNow.'-\' ,LPAD('.$periode->get('MoisDebut').', 2, 0), \'-\', LPAD('.$periode->get('JourDebut').', 2, 0)) AND  CONCAT(\''.$yearNow.'-\', LPAD('.$periode->get('MoisFin').', 2, 0), \'-\', LPAD('.$periode->get('JourFin').', 2, 0))';
			// elseif($periode->get('MoisDebut') < $periode->get('MoisFin'))
			// $wheredisciplineactionst[] = 'DateAction BETWEEN CONCAT(\''.$nextYear.'-\' ,LPAD('.$periode->get('MoisDebut').', 2, 0), \'-\', LPAD('.$periode->get('JourDebut').', 2, 0)) AND  CONCAT(\''.$nextYear.'-\', LPAD('.$periode->get('MoisFin').', 2, 0), \'-\', LPAD('.$periode->get('JourFin').', 2, 0))';
			// else
			// $wheredisciplineactionst[] = 'DateAction BETWEEN CONCAT(\''.$yearNow.'-\' ,LPAD('.$periode->get('MoisDebut').', 2, 0), \'-\', LPAD('.$periode->get('JourDebut').', 2, 0)) AND  CONCAT(\''.$nextYear.'-\', LPAD('.$periode->get('MoisFin').', 2, 0), \'-\', LPAD('.$periode->get('JourFin').', 2, 0))';

			$disciplineactionst = DisciplineAction::getList(array('where' => $wheredisciplineactionst, 'order' => array('DateAction' => true)));
		}

		$disciplineNbr = 0;
		if ($disciplineactionst) {
			foreach ($disciplineactionst as $disciplineactions) {
				if ($disciplineactions->get('ValidateAt')) {
					if (!$disciplineactions->get('DisciplineActionType'))
						continue;
					// if($disciplineactions->get('DisciplineActionType')->get('Flag'))
					$disciplineNbr += $disciplineactions->get('Valeur');
					// else
					// $disciplineNbr -= $disciplineactions->get('Valeur');

					$actionData = array(
						'id' => $disciplineactions->get('ID'),
						'date' => \Tools::dateFormat($disciplineactions->get('DateAction')),
						'nature' => $disciplineactions->get('DisciplineActionType')->get('Nature')->get('Label'),
						'type' => $disciplineactions->get('DisciplineActionType')->get('Label') . '-' . $disciplineactions->get('Commentaire'),
						'couleur' => $disciplineactions->get('DisciplineActionType')->get('Flag') ? '#75c228' : '#f5393a',
						'commentaire' => $disciplineactions->get('Commentaire')
					);

					if ($disciplineactions->get('Valeur') > 0)
						$actionData['valeur'] = '+' . $disciplineactions->get('Valeur');
					else
						$actionData['valeur'] = $disciplineactions->get('Valeur');

					$result['data'][] = $actionData;
				}
			}
		}

		$result['score'] = $disciplineNbr;
		return $result;
	}


	public static function countActionDiscipline($dateDebut, $dateFin)
	{

		$query = <<<END
		SELECT DATE_FORMAT(Date,'%Y-%m') AS Date, count(*) AS Count
		FROM disciplineactions
		where 
			`Date` BETWEEN ? AND ?
		group by DATE_FORMAT(Date,'%Y-%m')
		ORDER by DATE_FORMAT(Date,'%Y-%m') ASC
END;

		$params = array($dateDebut, $dateFin);

		$result = \DB::reader($query, $params);

		$response = array();

		foreach ($result as $data) {

			$response[$data['Date']] = array(
				'Count' => $data['Count'],
			);
		}

		return ($response);
	}
}
