<?php

namespace Models;

use Models\ETD\Edt;
use Models\ETD\SeanceTracking;
use Session;

class Absence extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'sco_absences';

	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Inscription' => array(
			'fk' => 'Inscription',
		),
		'PlanningPeriode' => array(
			'fk' => 'PlanningPeriode',
		),
		'Cours' => array(
			'type' => 'varchar',
		),
		'Professeur' => array(
			'fk' => 'Enseignant',
		),
		'Date' => array(
			'type' => 'datetime',
		),
		'Retards' => array(
			'type' => 'int',
		),
		'SaisiPar' => array(
			'fk' => 'User',
		),
		'SaisiLe' => array(
			'type' => 'datetime',
		),
		'Justifie' => array(
			'type' => 'boolean',
		),
		'JustifiePar' => array(
			'fk' => 'User',
		),
		'MotifAbsAlias' => array(
			'type' => 'varchar',
		),
		'MotifAbsLabel' => array(
			'type' => 'varchar',
		),
		'JustifieLe' => array(
			'type' => 'datetime',
		),
		'JustifieMotif' => array(
			'type' => 'varchar',
		),
		'JustifieFile' => array(
			'type' => 'varchar',
		),
		'DemandeJustification' => array(
			'type' => 'boolean',
		),
		'DemandeJustificationPar' => array(
			'fk' => 'User',
		),
		'DemandeJustificationLe' => array(
			'type' => 'datetime',
		),
		'ValidateAt' => array(
			'type' => 'date',
		),
		'ValidateBy' => array(
			'fk' => 'User',
		),
		'TimeStart' => array(
			'type' => 'varchar',
		),
		'TimeEnd' => array(
			'type' => 'varchar',
		),
		'DeletedAt' => array(
			'type' => 'varchar',
		),
		'CumulativeAbsence' => array(
			'type' => 'int',
		),
	);

	public function getJustifieFileName()
	{
		$name = 'Justification d\'absence ' . $this->get('Inscription')->get('Eleve')->get('User')->getNomComplet();
		$ext = pathinfo($this->get('JustifieFile'), PATHINFO_EXTENSION);
		return \Tools::getAlias($name) . '.' . $ext;
	}

	
	public function getJustifieFileLink()
	{
		return  \GoogleStorage::getUrl(\Config::get('path-docs-absences') . $this->get('JustifieFile'));
	}


	public static function minRetardsParPeriode($periode)
	{


		$minRetards = \DB::scallar('SELECT (SUM(`Retards`)) FROM `sco_absences` WHERE Retards > 0 AND Date BETWEEN \'' . $periode[0] . '\' AND \'' . $periode[1] . '\'');

		if (!$minRetards)
			$minRetards = 0;



		return $minRetards;
	}

	public  function getCours()
	{
		$cour = null;
		try {
			$cour = new SeanceTracking(explode(',', $this->Cours)[0]);
		} catch (\Exception $ex) {
		}
		return $cour;
	}


	public static function getList($args = null, $query = null)
	{
		if (!is_array($args))
			$args = array();

		$args['where'][] = 'DeletedAt IS NULL';
		$user = \Session::getInstance()->getCurUser();
		if ($user && $user->get('Classes')) {
			$classes =  $user->get('Classes');
			$args['where'][] = "Inscription IN (SELECT ID FROM ins_inscriptions where Classe IN (" . $classes . "))";
		}

		return parent::getList($args, $query);
	}


	public static function maxAbsence($classe, $periode)
	{

		if ($classe) {

			$query = <<<END
		SELECT `Inscription`, COUNT(*) AS Total FROM `sco_absences`
		JOIN (SELECT `ID` AS `J1ID`, `Classe` AS `J1Classe` FROM `ins_inscriptions`) AS `j1` ON `sco_absences`.`Inscription` = `j1`.`J1ID`
		WHERE J1Classe = ? AND DATE(Date) BETWEEN ? AND ? AND (Retards IS NULL OR Retards = 0)
		GROUP BY `Inscription`
		ORDER BY Total DESC
		LIMIT 1
END;
			$params = array($classe->get('ID'), $periode[0], $periode[1]);
		} else {

			$query = <<<END
		SELECT `Inscription`, COUNT(*) AS Total FROM `sco_absences`
		JOIN (SELECT `ID` AS `J1ID`, `Classe` AS `J1Classe` FROM `ins_inscriptions`) AS `j1` ON `sco_absences`.`Inscription` = `j1`.`J1ID`
		WHERE DATE(Date) BETWEEN ? AND ? AND (Retards IS NULL OR Retards = 0)
		GROUP BY `Inscription`
		ORDER BY Total DESC
		LIMIT 1
END;

			$params = array($periode[0], $periode[1]);
		}


		$result = \DB::reader($query, $params);

		$response = null;

		foreach ($result as $data) {

			try {
				$inscription = new Inscription($data['Inscription']);
			} catch (Exception $e) {
				continue;
			}

			$response = array();
			$response['inscription'] = $inscription;
			$response['total'] = $data['Total'];
		}

		return ($response);
	}

	public static function maxRetard($classe, $periode)
	{

		if ($classe) {

			$query = <<<END
		SELECT `Inscription`, SUM(Retards) AS Total FROM `sco_absences`
		JOIN (SELECT `ID` AS `J1ID`, `Classe` AS `J1Classe` FROM `ins_inscriptions`) AS `j1` ON `sco_absences`.`Inscription` = `j1`.`J1ID`
		WHERE J1Classe = ? AND DATE(Date) BETWEEN ? AND ? AND Retards > 0
		GROUP BY `Inscription`
		ORDER BY Total DESC
		LIMIT 1
END;
			$params = array($classe->get('ID'), $periode[0], $periode[1]);
		} else {

			$query = <<<END
		SELECT `Inscription`, SUM(Retards) AS Total FROM `sco_absences`
		JOIN (SELECT `ID` AS `J1ID`, `Classe` AS `J1Classe` FROM `ins_inscriptions`) AS `j1` ON `sco_absences`.`Inscription` = `j1`.`J1ID`
		WHERE DATE(Date) BETWEEN ? AND ? AND Retards > 0
		GROUP BY `Inscription`
		ORDER BY Total DESC
		LIMIT 1
END;

			$params = array($periode[0], $periode[1]);
		}


		$result = \DB::reader($query, $params);

		$response = null;

		foreach ($result as $data) {

			try {
				$inscription = new Inscription($data['Inscription']);
			} catch (Exception $e) {
				continue;
			}

			$response = array();
			$response['inscription'] = $inscription;
			$response['total'] = $data['Total'];
		}

		return ($response);
	}


	public function notification($settings_notifications)
	{

		if ($this->get('Retards') > 0) {
			if (isset($settings_notifications['retard']) && $settings_notifications['retard']) {
				$variationMsg = $settings_notifications['retard']['message'];
				$variationMsg = str_replace("%prenom%", $this->get('Inscription')->get('Eleve')->get('User')->get('Prenom'), $variationMsg);
				$variationMsg = str_replace("%nom%", $this->get('Inscription')->get('Eleve')->get('User')->get('Nom'), $variationMsg);
				$variationMsg = str_replace("%nombre%", $this->get('Retards'), $variationMsg);
			} else {
				$variationMsg = \Config::get('nom_ecole') . ' vous informe que ' . $this->get('Inscription')->get('Eleve')->get('User')->get('Prenom') . ' est arrivé(e) en retard de ' . $this->get('Retards') . ' minutes aujourd\'hui.';
			}
		} else {

			try {
				$cours = new Cours(explode(',', $this->get('Cours'))[0]);
			} catch (Exception $e) {
				$cours = null;
			}
			$seance = '';
			if ($cours) {
				if ($cours->getPeriodeHeure()  == 'matin') {
					$seance = 'Matin';
				} else {
					$seance = 'Aprés-midi';
				}
			}

			if (isset($settings_notifications['absence']) && $settings_notifications['absence']) {
				$variationMsg = $settings_notifications['absence']['message'];
				$variationMsg = str_replace("%prenom%", $this->get('Inscription')->get('Eleve')->get('User')->get('Prenom'), $variationMsg);
				$variationMsg = str_replace("%nom%", $this->get('Inscription')->get('Eleve')->get('User')->get('Nom'), $variationMsg);
				$variationMsg = str_replace("%seance%", $seance, $variationMsg);
			} else {
				$variationMsg = \Config::get('nom_ecole') . ' vous informe que ' . $this->get('Inscription')->get('Eleve')->get('User')->get('Prenom') . ' a été absent(e) aujourd\'hui. Merci de nous contacter pour justifier son absence. La Direction';
			}
		}

		return $variationMsg;
	}

	public function notifier($by_user =  null, $periode = null)
	{


		$eleve  = $this->get('Inscription')->get('Eleve');

		if (!$by_user) {
			$by_user = Session::getInstance()->getCurUser();
		}

		try {
			$cours =  new SeanceTracking(explode(',', $this->get('Cours'))[0]);
		} catch (\Exception $e) {
			return;
		}

		$settings_notifications = array();
		$config = \Models\Config::getByAlias('settings_notifications');
		if ($config) {
			$settings_notifications = json_decode($config->get('Value'), true);
		}

		if ($this->get('Retards') > 0) {
			if (isset($settings_notifications['retard']) && !$settings_notifications['retard']['enabled'])
				return null;
		} else {
			if (isset($settings_notifications['absence']) && !$settings_notifications['absence']['enabled'])
				return null;
		}



		$dateLabel = '';
		if (date('Y-m-d') == date('Y-m-d', strtotime($this->Date))) {
			if ($periode) {
				$dateLabel .= 'aujourd\'hui ' . ($periode == 'matin' ? 'la matinée' : ' l\'après-midi');
				// ' pour la séance ' . $cours->Seance->Unite->Label;
			} else {
				$dateLabel .= 'aujourd\'hui pour la séance ' . $cours->Seance->Unite->Label . ' de ' . $cours->Seance->getHeure('From') . ' à ' . $cours->Seance->getHeure('To');
			}
		} else {
			if ($periode) {
				$dateLabel .= ($periode == 'matin' ? 'le matin ' : ' l\'après-midi ') . \Tools::dateFormat($this->Date);
				// ' pour la séance ' . $cours->Seance->Unite->Label;
			} else {
				$dateLabel .= 'le ' . \Tools::dateFormat($this->Date) . ' pour la séance ' . $cours->Seance->Unite->Label . ' de  ' . $cours->Seance->getHeure('From') . ' à ' . $cours->Seance->getHeure('To');
			}
		}

		if ($this->get('Retards') > 0) {
			$notifLabel = 'Retard.';
			$notifMessage = $this->get('Inscription')->get('Eleve')->get('User')->getNomComplet() . ' a eu un retard ' . $dateLabel . ' de ' . $this->get('Retards') . ' mins';
		} else {
			$notifLabel = 'Absence.';
			if (\Config::getSchool() != "pommehappy") {
				$notifMessage = $this->get('Inscription')->get('Eleve')->get('User')->getNomComplet() . ' était absent(e) ' . $dateLabel;
			} else {
				if (date('Y-m-d') == date('Y-m-d', strtotime($this->Date))) {
					$notifMessage = $this->get('Inscription')->get('Eleve')->get('User')->getNomComplet() . " s’est absenté(e) aujourd’hui. Si ce n'est pas déjà fait, merci de bien vouloir justifier son absence auprès de la Direction (via l’application, téléphone, ou messagerie).  \n Merci d’avance !";
				} else {
					$notifMessage = $this->get('Inscription')->get('Eleve')->get('User')->getNomComplet() . " absenté(e) le " . \Tools::dateFormat($this->Date) . ". Si ce n'est pas déjà fait, merci de bien vouloir justifier son absence auprès de la Direction (via l’application, téléphone, ou messagerie). \n Merci d’avance !";
				}
			}
		}

		$tokens = $this->get('Inscription')->get('Eleve')->simpleTokens();
		$has_tokens = count($tokens);
		$parents = $this->get('Inscription')->get('Eleve')->ParentsPhone();

		if (isAllowed('notification_sms_absences_all')) {
			fcm_send($tokens, $notifLabel, $notifMessage, 'eleve_absences', null, $eleve->ID);
			foreach ($parents as $user) {
				if ($user->EnableSmsNotif) {
					smsCasaNet($user->Tel, $notifMessage);
					NotificationSms::create(array(
						'User' => $by_user,
						'UserTo' => $user,
						'UserToName' => $user->getNomComplet(),
						'GSM' => $user->Tel,
						'Inscription' => $this->Inscription,
						'Action' => "Absence",
						'Message' => $notifMessage,
						'Date' => date('Y-m-d H:i:s'),
					));
					ActionLog::create(array('User' => $by_user, 'Detail' => 'send_absence_sms_(' . $user->Tel . ')', 'Date' => date('Y-m-d H:i:s')));
				}
			}
		} else if (isAllowed('notification_absences_sms_no_token')) {
			if (!$has_tokens) {
				foreach ($parents as $user) {
					if ($user->EnableSmsNotif) {
						smsCasaNet($user->Tel, $notifMessage);
						NotificationSms::create(array(
							'User' => $by_user,
							'UserTo' => $user,
							'UserToName' => $user->getNomComplet(),
							'GSM' => $user->Tel,
							'Inscription' => $this->Inscription,
							'Action' => "Absence",
							'Message' => $notifMessage,
							'Date' => date('Y-m-d H:i:s'),
						));
						ActionLog::create(array('User' => $by_user, 'Detail' => 'send_absence_sms_(' . $user->Tel . ')', 'Date' => date('Y-m-d H:i:s')));
					}
				}
			} else {
				fcm_send($tokens, $notifLabel, $notifMessage, 'eleve_absences', null, $eleve->ID);
			}
		} else {
			fcm_send($tokens, $notifLabel, $notifMessage, 'eleve_absences', null,	$eleve->ID);
		}

		$notif = new Notif();
		$notif
			->set('Label', $notifLabel)
			->set('Inscription', $this->get('Inscription'))
			->set('Message', $notifMessage)
			->set('Date', date('Y-m-d H:i:s'))
			->set('TypeRessource', 'absence')
			->set('IDRessource', $this->getKey());

		$notif->save();
	}


	public function _notifier()
	{


		$settings_notifications = array();
		$config = \Models\Config::getByAlias('settings_notifications');
		if ($config) {
			$settings_notifications = json_decode($config->get('Value'), true);
		}

		if ($this->get('Retards') > 0) {
			if (isset($settings_notifications['retard']) && !$settings_notifications['retard']['enabled'])
				return null;
		} else {
			if (isset($settings_notifications['absence']) && !$settings_notifications['absence']['enabled'])
				return null;
		}


		$tokensAndroid = array();
		$tokensIos = array();
		$parrainages = Parrainage::getList(array('where' => array('Eleve' => $this->get('Inscription')->get('Eleve')->get('ID'))));

		if ($this->get('Retards') > 0) {
			$notifLabel = 'Retard.';
			$notifMessage = $this->get('Inscription')->get('Eleve')->get('User')->getNomComplet() . ' a eu un retard aujourd\'hui de ' . $this->get('Retards');
		} else {
			$notifLabel = 'Absence.';
			$notifMessage = $this->get('Inscription')->get('Eleve')->get('User')->getNomComplet() . ' était absent(e) aujourd\'hui';
		}


		_snf($this->get('Inscription')->get('Eleve')->tokens(), $notifLabel, $notifMessage, 'eleve_absences');

		// $message = $this->notification($settings_notifications);
		/*
		foreach($parrainages as $item) {
		
			if(!$item->get('Parent')->get('User')->get('TokenID'))
				continue;
			
			if($item->get('Parent')->get('User')->get('TokenDevice') == 'ios')
				$tokensIos[] = $item->get('Parent')->get('User')->get('TokenID');
			else
				$tokensAndroid[] = $item->get('Parent')->get('User')->get('TokenID');
			
		}
		
		if($tokensAndroid) {
			$messageAndroid =  array(
				"title"=> $notifLabel,
				'body' 	=> $message,
				// "content-available"=> '0',
				'icon'	=> 'myicon',
				'sound' => 'mySound',
			);
			$message_status = send_notification_firebase($tokensAndroid, $messageAndroid, 'android');
		}
		
		if($tokensIos) {
			$messageIos =  array(
				"title"=> $notifLabel,
				'body' 	=> $message,
				'icon'	=> 'myicon',
				'sound' => 'mySound',
			);
			
			$message_status = send_notification_firebase($tokensIos, $messageIos, 'ios');
		}
		*/


		$notif = new Notif();
		$notif
			->set('Label', $notifLabel)
			->set('Inscription', $this->get('Inscription'))
			->set('Message', $notifMessage)
			->set('Date', date('Y-m-d H:i:s'));

		$notif->save();
	}

	public static function countAbsences($dateDebut, $dateFin)
	{

		$query = <<<END
		SELECT DATE_FORMAT(Date,'%Y-%m') AS Date, count(*) AS Count
		FROM sco_absences
		where 
			`Date` BETWEEN ? AND ? 
			AND
			(Retards IS NULL OR Retards = 0)
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

	public static function countRetards($dateDebut, $dateFin)
	{

		$query = <<<END
		SELECT DATE_FORMAT(Date,'%Y-%m') AS Date, count(*) AS Count
		FROM sco_absences
		where 
			`Date` BETWEEN ? AND ? 
			AND
			Retards > 0
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




	public static function isAbsenceWholeDay($inscription, $date)
	{

		$day_n = (int)date('N',   strtotime($date));


		$classeID =  $inscription->Classe->ID;
		$inscriptionId  = $inscription->ID;

		$count = \DB::reader("
            SELECT 
                    (select count(*) FROM `sco_absences` WHERE `Inscription` = $inscriptionId AND `DeletedAt` IS NULL AND `Retards` IS NULL AND `Date` LIKE '$date%') as count_absences,
                    (select count(*) FROM `sco_seance` WHERE `Day` = $day_n AND `Classe` = $classeID) as count_seances
        ");

		return $count[0]['count_seances'] == $count[0]['count_absences'];
	}



	public function _saveCumulativeAbsence()
	{

		$date = date('Y-m-d', strtotime($this->Date));
		$inscription = $this->Inscription;

		$isAbsenceWholeDay =  self::isAbsenceWholeDay($inscription, $date);

		if (!$isAbsenceWholeDay) {
			return;
		}

		$yesterday = date('Y-m-d', strtotime("-1 days", strtotime($this->Date)));

		$yesterdayFirstAbs =  \Models\Absence::where([
			'Inscription' => $inscription->ID,
			"`Date` LIKE '$yesterday%'",
			'CumulativeAbsence IS NOT NULL'
		])->first();


		if ($yesterdayFirstAbs) {
			$cumulativeAbsence = $yesterdayFirstAbs->CumulativeAbsence ?: 0;
			if (!$this->get('CumulativeAbsence')) {
				$this->set('CumulativeAbsence', ($cumulativeAbsence + 1));
				$this->save();
			}
			return;
		}

		if (!$this->get('CumulativeAbsence')) {
			$this->set('CumulativeAbsence',  1);
			$this->save();
		}
	}

	public static function saveCumulativeAbsence($inscription)
	{
		$inscriptionId  = $inscription->ID;
		$lastAbs  =  \Models\Absence::where(['Inscription' => $inscription->ID])->order(['Date'=>false])->first();

		$absences_dates = [];
		$_absences_dates = \DB::reader("SELECT DISTINCT(`Date`) FROM `sco_absences` WHERE `Inscription` = $inscriptionId AND  `DeletedAt` IS NULL  ORDER BY `Date` LIMIT 5");

		foreach ($_absences_dates as $item) {
			$absences_dates[date('Y-m-d', strtotime($item['Date']))] = date('Y-m-d', strtotime($item['Date']));
		}


		
		if(count($absences_dates) && $lastAbs) { 

			$cumulativeAbsence  = 0;
			foreach ($absences_dates as $date) {
				$date = date('Y-m-d', strtotime($date));

				$isAbsenceWholeDay =  self::isAbsenceWholeDay($inscription, $date);
				$yesterday = date('Y-m-d', strtotime("-1 days", strtotime($date)));
				$yesterdayAbs =   self::isAbsenceWholeDay($inscription, $yesterday);

				if ($yesterdayAbs && $isAbsenceWholeDay) {
					 $cumulativeAbsence+=1;
				} elseif($isAbsenceWholeDay) {
					$cumulativeAbsence += 1;
				}

			}

			$lastAbs->set('CumulativeAbsence',  $cumulativeAbsence);
			$lastAbs->save();  
	  }

	}
}
