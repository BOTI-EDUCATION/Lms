<?php

namespace Models\FIN;

use Exception;
use Models\Model;
use Models\Promotion;

class Encaissement extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'fnc_encaissements';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);


	protected static $fields = array(
		'Inscription' => array(
			'fk' => 'Inscription',
		),
		'Inscriptions' => array(
			'type' => 'text',
		),
		'EncaissementRubriques' => array(
			'type' => 'text',
		),
		'CompteBancaire' => array(
			'fk' => 'FIN\\CompteBancaire',
		),
		'Avoir' => array(
			'fk' => 'FIN\\Avoir',
		),
		'Caisse' => array(
			'fk' => 'FIN\\Caisse',
		),
		'Promotion' => array(
			'fk' => 'Promotion',
		),
		'UserBy' => array(
			'fk' => 'User',
		),
		'PaiementMode' => array(
			'type' => 'varchar',
		),
		'Partenaire' => array(
			'fk' => 'Partenaire',
		),
		'DetailMode' => array(
			'type' => 'int',
		),
		'PaiementDetail' => array(
			'type' => 'text',
		),
		'Montant' => array(
			'type' => 'double',
		),
		'EcheanceCheque' => array(
			'type' => 'date'
		),
		'NumRecu' => array(
			'type' => 'varchar',
		),
		'Remarque' => array(
			'type' => 'text',
		),
		'Encaisse' => array(
			'type' => 'datetime',
		),
		'EncaisseBy' => array(
			'fk' => 'User',
		),
		'EncaisseDate' => array(
			'type' => 'date',
		),
		'NumOperation' => array(
			'type' => 'varchar',
		),
		'DatePaiement' => array(
			'type' => 'datetime',
		),
		'Date' => array(
			'type' => 'datetime',
		),
		'File' => array(
			'type' => 'varchar',
		),
		'EncaissementPar' => array(
			'type' => 'varchar',
		),
		'DetailsModeInfos' => array(
			'type' => 'text',
		),
		'EditHistory' => array(
			'type' => 'text',
		),
		'CaisseHistory' => array(
			'type' => 'text',
		),
		'Validated' => array(
			'type' => 'text',
		),
		'Canceled' => array(
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

	public function saveCaisseHistory($caisse)
	{
		$history = $this->getArray('CaisseHistory') ?: array();
		$history[] = array(
			'user' => \Session::getInstance()->getCurUser()->ID,
			'caisse' => $caisse->getKey(),
			'date' => date('Y-m-d H:i:s'),
		);

		return $this->setJson('CaisseHistory', $history);
	}

	public function getInscriptions()
	{
		$ids = $this->get('Inscriptions');
		$ids = str_replace('"', '', $ids);
		$ids = explode(',', $ids);


		$inscriptions = array();
		foreach ($ids as $value) {
			$inscriptions[] = new \Models\Inscription($value);
		}

		return $inscriptions;
	}

	/* ALTER TABLE `fnc_encaissements` ADD `EncaisseDate` DATE NULL AFTER `EncaisseBy`, ADD `NumOperation` VARCHAR(255) NULL AFTER `EncaisseDate`; */
	private $child = false;

	public function getDetailmode()
	{
		if ($this->child !== false) // If already loaded, just return it
			return $this->child;
		if (!$this->get('PaiementMode')) { // If no type defined, we won't be able to defin child source
			$this->child = null;
			return $this->child;
		}

		$child = null;

		try {

			switch ($this->get('PaiementMode')) { // Check type to retrieve child
				case 'cheque':
					try {
						$child = new PaiementchequeDetail($this->get('DetailMode'));
					} catch (Exception $e) {
						$child = null;
					}
					break;
				case 'virement':
					try {
						$child = new PaiementVirementDetail($this->get('DetailMode'));
					} catch (Exception $e) {
						$child = null;
					}
					break;

				case 'tpe':
					try {
						$child = new PaiementTpeDetail($this->get('DetailMode'));
					} catch (Exception $e) {
						$child = null;
					}

					break;
			}
		} catch (Exception  $e) {
		}


		if (!$child) { // If no child found, return null
			$this->child = null;
			return $this->child;
		}

		// Only one child allowed per payment
		$this->child = $child;
		return $this->child;
	}

	public function  getPaiementMode()
	{
		$modes = PaiementMode::getList(array('where' => array('Alias' => $this->get('PaiementMode'))));
		if (count($modes)) {
			return $modes[0];
		}
		return null;
	}

	public static function getEncaissements($filter, $count = false)
	{

		$zone_recherche 	= isset($filter['zone_recherche']) ? $filter['zone_recherche'] : null;
		$comptes 			= isset($filter['comptes']) ? $filter['comptes'] : null;
		$services			= isset($filter['services']) ? $filter['services'] : null;
		$niveau 			= isset($filter['niveau']) ? $filter['niveau'] : null;
		$site 			= isset($filter['site']) ? $filter['site'] : null;
		$classe 			= isset($filter['classe']) ? $filter['classe'] : null;
		$mode 				= isset($filter['mode']) ? $filter['mode'] : null;
		$periode 			= isset($filter['periode']) ? $filter['periode'] : null;
		$montant_min 			= isset($filter['montant_min']) ? $filter['montant_min'] : null;
		$montant_max 			= isset($filter['montant_max']) ? $filter['montant_max'] : null;
		$promotion 			= isset($filter['promotion']) ? $filter['promotion'] : null;
		$user 				= isset($filter['user']) ? $filter['user'] : null;
		$service 				= isset($filter['service']) ? $filter['service'] : null;

		$compte_bancaire 				= isset($filter['compte_bancaire']) ? $filter['compte_bancaire'] : null;

		$encaissements 		= array();
		$where 				= array();

		if ($count)
			$query = Encaissement::sqlQueryCount();
		else
			$query = Encaissement::sqlQuery(true);

		$query .= <<<END
	JOIN (SELECT `ID` as `J0ID`, `Encaissement` as `JOEncaissement`, `Inscription` as `J0Inscription` FROM `fnc_encaissementlignes`) AS `j0` ON `fnc_encaissements`.`ID` = `j0`.`JOEncaissement` 
	JOIN (SELECT `ID` AS `J1ID`, `Classe` AS `J1Classe`, `Niveau` AS `J1Niveau`,`Site` AS `J1Site`, `Eleve` AS `J1Eleve` FROM `ins_inscriptions`) AS `j1` ON `j0`.`J0Inscription` = `j1`.`J1ID` 
END;


		if ($mode) {
			$where['PaiementMode'] = $mode->get('Alias');
		}

		if ($zone_recherche) {

			$query .= <<<END
	JOIN (SELECT `ID` AS `J3ID`, `CNE` AS `J3CNE`, `User` AS `J3User` FROM `gen_eleves`) AS `j3` ON `j1`.`J1Eleve` = `j3`.`J3ID`
	JOIN (SELECT `ID` AS `J4ID`, LOWER(`Nom`) AS J4Nom, LOWER(`Prenom`) AS J4Prenom FROM `users`) AS `j4` ON `j3`.`J3User` = `j4`.`J4ID`
END;
			$where[] = '(J4Nom  LIKE \'%' . strtolower($zone_recherche) . '%\' OR J4Prenom  LIKE \'%' . strtolower($zone_recherche) . '%\' OR J3CNE = \'' . strtolower($zone_recherche) . '\' OR NumRecu = \'' . strtolower($zone_recherche) . '\')';
		} else {

			if ($classe) {
				$where['J1Classe'] = $classe->get('ID');
			}

			if ($niveau && !$classe) {
				$where['J1Niveau'] = $niveau->get('ID');
			}

			if ($site && !$classe) {
				$where['J1Site'] = $site->getKey();
			}


			if ($periode) {

				$periode =  explode(' - ', $periode);
				$where[] = '((DATE(DatePaiement) BETWEEN \'' . $periode[0] . '\' AND \'' . $periode[1] . '\') OR (DATE(JSON_UNQUOTE(json_extract(`Canceled`, "$.date"))) BETWEEN \'' . $periode[0] . '\' AND \'' . $periode[1] . '\' ))';
			}
		}

		if ($montant_max) {
			$where[] = 'Montant  <= ' . $montant_max;
		}



		if ($montant_min) {
			$where[] = 'Montant  >= ' . $montant_min;
		}

		// $promotion = $_SESSION['promotion_actuelle'];
		if ($promotion)
			$where['Promotion'] = $promotion->get('ID');


		if ($compte_bancaire)
			$where['CompteBancaire'] = $compte_bancaire->get('ID');

		if ($service)
			$where[] = "`ID` IN(SELECT `Encaissement` FROM `fnc_encaissementlignes` WHERE  `EncaissementRubrique` = " . $service->getKey() . " )";

		if ($user) {
			$where['UserBy'] = $user->get('ID');
		}

		if (roleIs('agent_financier')) {
			$where['UserBy'] = \Session::user()->get('ID');
		}
		//print_r($query);exit;
		if ($count)
			$encaissements = Encaissement::getCount(array('where' => $where), $query, true);
		else
			$encaissements = Encaissement::getList(array('where' => $where, 'limit' => 500, 'order' => ['DatePaiement' => false, 'NumRecu' => false]), $query, true);

		return $encaissements;
	}


	public static function getTotalEncaissementsParMois($mois = null, $type = null)
	{

		if (!$mois) {

			$mois = (date('d') > 15) ? date('m') : date('m') - 1;

			if ($mois == 0)
				$mois = 12;
		}

		$promotion = $_SESSION['promotion_actuelle'];


		$encaissementsMontant = \DB::scallar('SELECT (SUM(`Montant`)) FROM `fnc_encaissements` WHERE  Canceled IS NULL And Encaisse IS NOT NULL AND `Mois` = ' . $mois . ' AND Promotion = ' . $promotion . ' AND EncaissementRubrique = ' . $type->getPK(true));

		if (!$encaissementsMontant)
			$encaissementsMontant = 0;



		return $encaissementsMontant;
	}



	public static function encaissements($inscription)
	{

		$query = <<<END
		SELECT `Inscription`, `Mois`, `EncaissementRubrique`, SUM(`Montant`) AS Montant FROM `fnc_encaissements`
		JOIN (SELECT `ID` AS `J1ID`, `Label` AS `J1Label` FROM `fnc_encaissementrubriques`) AS `j1` ON `fnc_encaissements`.`EncaissementRubrique` = `j1`.`J1ID`
		WHERE Inscription = ?
		GROUP BY `Inscription`, `Mois`, `EncaissementRubrique`
END;

		$params = array($inscription->get('ID'));

		$result = \DB::reader($query, $params);

		$response = array();

		foreach ($result as $data) {

			$response[$data['Mois'] ? $data['Mois'] : '9'][$data['EncaissementRubrique']] = array(
				'Montant' => $data['Montant'],
			);
		}

		return ($response);
	}


	public static function total_encaissements($mois, $promotion = null)
	{
		if (!$promotion) {
			$promotion = \Models\Promotion::promotion_actuelle();
		}

		$query = "SELECT SUM(`Montant`) AS Montant FROM `fnc_encaissementlignes` WHERE  `Inscription` IN (SELECT `ID` FROM  `ins_inscriptions` WHERE `Depart` IS NULL AND `Promotion` = " . $promotion->get('ID') . ") AND Mois = ?  AND Canceled IS NULL";

		$params = array($mois);
		$result = \DB::scallar($query, $params);

		return ($result) ? $result : 0;
	}




	public static function getEncaissementOfInscription($inscription)
	{

		return self::getList(array('where' => array(
			'(Inscription = ' . $inscription->ID . ' OR Inscriptions LIKE \'%"' . $inscription->ID . '"%\')',
		)));
	}

	public function encaissementLignes($inscription =  null)
	{
		$where = array(
			'Encaissement' => $this->get('ID')
		);

		if ($inscription) {
			$where['Inscription'] = $inscription->get('ID');
		}

		$lignes = EncaissementLigne::getList(array('where' => $where));

		return $lignes;
	}


	public function encaissementLignesByInscription()
	{

		$lignesArray = array();
		$lignes = EncaissementLigne::getList(array('where' => array(
			'Encaissement' => $this->get('ID')
		)));

		foreach ($lignes as $item) {
			if (!isset($lignesArray[$item->get('Inscription')->get('ID')])) {
				$lignesArray[$item->get('Inscription')->get('ID')] = array(
					'inscription' => $item->get('Inscription'),
					'lignes' => array(),
				);
			}
			$lignesArray[$item->get('Inscription')->get('ID')]['lignes'][] = $item;
		}


		return $lignesArray;
	}


	public function getPaiementModeLabel()
	{
		$mode = null;
		switch ($this->get('PaiementMode')) {
			case 'espece':
				$mode = "Espèce";
				break;

			case 'cheque':
				$mode = "Chèque";
				break;

			case 'virement':
				$mode = "Virement";
				break;

			case 'tpe':
				$mode = "TPE";
				break;

			case 'avoir':
				$mode = "Avoir";
				break;
		}

		return $mode;
	}

	public static  function _getEncaissementsBy($type, $banque = null, $start = null, $end = null)
	{

		$request = "PaiementMode = '$type'";
		$request .= ' AND `Canceled` IS NULL';
		if ($banque) {
			$banque  = $banque->get('ID');
			$request .= " AND (DetailMode IN (SELECT ID FROM paiementchequedetails WHERE Banque = $banque))";
		}
		if ($start && $end) {
			$request   .= ' AND EcheanceCheque BETWEEN "' . $start . '" and "' . $end . '"';
		}

		return	self::getList(array('where' => array($request)));
	}



	public static  function getEncaissementsBy($type, $banque = null, $start = null, $end = null, $encaisse = null, $tireur = null, $numCheque = null)
	{

		if (is_array($type)) {
			$request = " `PaiementMode` IN('" . implode("','", $type) . "')";
		} else {
			$request = " `PaiementMode` = '$type' ";
		}

		$request .= ' AND `Canceled` IS NULL';

		if (!is_null($encaisse)) {
			$request .= ' AND `Encaisse` ' . ($encaisse ? 'IS NOT NULL' : 'IS  NULL');
		}

		if ($banque) {
			$banque  = $banque->get('ID');
			$request .= " AND (`DetailMode` IN (SELECT `ID` FROM `paiementchequedetails` WHERE Banque = $banque))";
		}

		if ($tireur) {
			//dd($tireur);
			$request .= " AND (`DetailMode` IN (SELECT `ID` FROM `paiementchequedetails` WHERE `Tireur` LIKE \"%$tireur%\"))";
		}

		if ($numCheque) {
			$request .= " AND (`DetailMode` IN (SELECT `ID` FROM `paiementchequedetails` WHERE `NumCheque` LIKE \"%$numCheque%\"))";
		}

		if ($start && $end) {
			$request   .= ' AND `EcheanceCheque` BETWEEN "' . $start . '" and "' . $end . '"';
		}

		return	self::getList(array('where' => array($request), 'order' => array('EcheanceCheque' => false, 'DatePaiement' => false), 'limit' => 500));
	}




	public function chequeAdepose()
	{
		return array();
	}

	public static function getCA($mois = null)
	{
		//GetPromotion Actuelle if promotion is null
		$total_CA = \DB::scallar('SELECT (SUM(`Montant`)) FROM `fnc_encaissementinscriptions` WHERE `Mois` = ' . $mois);

		if (!$total_CA)
			$total_CA = 0;

		return $total_CA;
	}
	public static function getTotalEncaissements($mois = null)
	{
		//GetPromotion Actuelle if promotion is null
		$total_Encaissements = \DB::scallar('SELECT (SUM(`Montant`)) FROM `fnc_encaissementlignes` WHERE `Canceled` IS NULL AND `Mois` = ' . $mois);

		if (!$total_Encaissements)
			$total_Encaissements = 0;

		return $total_Encaissements;
	}

	public function getFile()
	{
		return  \GoogleStorage::getUrl(\Config::get('path-encaissements')  . $this->get('File'));
	}


	public  function cancelEncaissement($generate_avoir, $comment, $canceled_at)
	{

		$user = \Session::getInstance()->getCurUser();


		foreach ($this->encaissementLignes() as  $ligne) {
			$ligne->setJson('Canceled', array(
				'date' => date('Y-m-d H:i:s'),
				'user' => $user->ID,
			));
			$ligne->save();
		}

		$this->setJson('Canceled', array(
			'cdate' => date('Y-m-d H:i:s'),
			'date' => $canceled_at ?? date('Y-m-d H:i:s'),
			'comment' => $comment,
			'user' => $user->ID,
		));

		if ($this->Avoir) {
			$this->Avoir->restore($this);
		}

		if ($generate_avoir && !$this->Avoir) {
			$avoir = new \Models\FIN\Avoir();

			$avoir->fill([
				'Ref' => $this->ID . '_' . $this->NumRecu,
				'Promotion' => $this->Promotion,
				'Encaissement' => $this->ID,
				'NumRecuEncaissement' => $this->NumRecu,
				'Amount' => $this->Montant,
				'User' => $user,
				'Commentaire' => $comment,
				'Date' => date('Y-m-d H:i:s'),
			]);

			$avoir->saveHistory('add');
			$avoir->save();
		}

		if ($this->Caisse) {
			$this->Caisse->soustractModeSolde($this->PaiementMode, $this->Montant);
			$this->Caisse->soustraction($this->Montant);
		}

		$this->save();
		\Models\ActionLog::catchLog('Encaissement annulé par ' . ($user->getNomComplet()) . ' le ' . date('Y-m-d') . ' : Paiement de ' . ($this->Inscription ? $this->Inscription->Eleve->User->getNomComplet() : $this->EncaissementPar) . ' d\'un montant de ' . ($this->Montant) . ' DH', $this);
	}
}
