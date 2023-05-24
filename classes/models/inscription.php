<?php

namespace Models;

use Exception;
use Models\ETD\SeanceTracking;

class Inscription extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'ins_inscriptions';

	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);

	protected static $fields = array(
		'Site' => array(
			'fk' => 'Site',
		),
		'Eleve' => array(
			'fk' => 'Eleve',
		),
		'Promotion' => array(
			'fk' => 'Promotion',
		),
		'Niveau' => array(
			'fk' => 'Niveau',
		),
		'Classe' => array(
			'fk' => 'Classe',
		),
		'FraisInscription' => array(
			'type' => 'double',
		),
		'Mensualite' => array(
			'type' => 'double',
		),
		'AffectationClasseUser' => array(
			'fk' => 'User',
		),
		'AffectationClasseDT' => array(
			'type' => 'datetime',
		),
		'Champs' => array(
			'type' => 'text',
		),
		'Ordre' => array(
			'type' => 'int',
		),
		'DateInscripiton' => array(
			'type' => 'date',
		),
		'InscriptionBy' => array(
			'type' => 'varchar',
		),
		'Commentaire' => array(
			'type' => 'varchar',
		),
		'Dispense' => array(
			'type' => 'varchar',
		),
		'Redoublant' => array(
			'type' => 'boolean',
		),
		'Depart' => array(
			'type' => 'boolean',
		),
		'DepartDate' => array(
			'type' => 'date',
		),
		'DepartBy' => array(
			'type' => 'User',
		),
		'DepartMotif' => array(
			'type' => 'varchar',
		),
		'DepartCommentaire' => array(
			'type' => 'varchar',
		),
		'Validated' => array(
			'type' => 'varchar',
		),
	);


	public function beforeSave()
	{
		$this->set('Site', $this->Classe  && $this->Classe->Site ? $this->Classe->Site : Site::query()->first());
	}

	public function getSite()
	{
		if (is_null($this->Site)) {
			$this->set('Site', $this->Classe && $this->Classe->Site ? $this->Classe->Site : Site::query()->first());
			$this->save();
		}
		return $this->Site;
	}

	public function solde()
	{

		$promotions = Promotion::getList(array('where' => array('Actuelle' => true)));

		$actionsPositive = \DB::scallar('SELECT SUM(`Valeur`) FROM `disciplineactions` JOIN (SELECT `ID` AS `J1ID`, `Flag` AS `J1Flag` FROM `disciplineactiontypes`) AS `j1` ON `disciplineactions`.`DisciplineActionType` = `j1`.`J1ID`  WHERE J1Flag = TRUE AND Reseted IS NULL AND Compense IS NULL AND Inscription =' . $this->get('ID'));
		if (!$actionsPositive)
			$actionsPositive = 0;

		$actionsNegative = \DB::scallar('SELECT SUM(`Valeur`) FROM `disciplineactions` JOIN (SELECT `ID` AS `J1ID`, `Flag` AS `J1Flag` FROM `disciplineactiontypes`) AS `j1` ON `disciplineactions`.`DisciplineActionType` = `j1`.`J1ID`  WHERE J1Flag = FALSE AND Reseted IS NULL AND Compense IS NULL AND Inscription =' . $this->get('ID'));
		if (!$actionsNegative)
			$actionsNegative = 0;

		return $actionsPositive - $actionsNegative;
	}


	public static function getInscriptions($eleve)
	{
		return Inscription::getList(array('where' => array('Eleve' => $eleve->getPK(true)), 'order' => array('Promotion' => false)));
	}



	public function total_encaissements($mois, $service = null)
	{

		$query = <<<END
		SELECT SUM(`Montant`) AS Montant FROM `fnc_encaissementlignes`

		JOIN (SELECT `ID` AS `J1ID`, `Label` AS `J1Label` FROM `fnc_encaissementrubriques`) AS `j1` ON `fnc_encaissementlignes`.`EncaissementRubrique` = `j1`.`J1ID`

		JOIN (SELECT `ID` AS `J2ID`, `Inscription` AS `J2Inscription` FROM `fnc_encaissements`) AS `j2` ON `fnc_encaissementlignes`.`Encaissement` = `j2`.`J2ID`

END;

		if ($service) {
			$query .=  " WHERE J2Inscription = ? AND Mois = ? AND  J1ID = ? ";
			$params = array($this->get('ID'), $mois, $service->get('ID'));
		} else {
			$query .= " WHERE J2Inscription = ? AND Mois = ? ";
			$params = array($this->get('ID'), $mois);
		}

		$result = \DB::scallar($query, $params);
		return ($result) ? $result : 0;
	}

	public function _total_encaissements($mois, $service = null)
	{

		$query = <<<END
		SELECT SUM(`Montant`) AS Montant FROM `fnc_encaissementlignes`

		JOIN (SELECT `ID` AS `J1ID`, `Label` AS `J1Label` FROM `fnc_encaissementrubriques`) AS `j1` ON `fnc_encaissementlignes`.`EncaissementRubrique` = `j1`.`J1ID`

		JOIN (SELECT `ID` AS `J2ID`, `Inscription` AS `J2Inscription` FROM `fnc_encaissements`) AS `j2` ON `fnc_encaissementlignes`.`Encaissement` = `j2`.`J2ID`

END;

		if (!$service && $mois == 9) {
			$query .= <<<END

		WHERE J2Inscription = ? AND ( Mois = ? OR Mois IS NULL )

END;

			$params = array($this->get('ID'), $mois);
		} elseif (!$service) {
			$query .= <<<END

		WHERE J2Inscription = ? AND Mois = ?

END;

			$params = array($this->get('ID'), $mois);
		} elseif ($mois == 9) {
			$query .= <<<END

		WHERE J2Inscription = ? AND ( Mois = ? OR Mois IS NULL ) AND  J1ID = ?

END;

			$params = array($this->get('ID'), $mois, $service->get('ID'));
		} else {
			$query .= <<<END

		WHERE J2Inscription = ? AND Mois = ? AND  J1ID = ?

END;


			$params = array($this->get('ID'), $mois, $service->get('ID'));
		}

		$result = \DB::scallar($query, $params);

		return ($result) ? $result : 0;
	}


	public static function CountCurrentInscription()
	{
		$promotion = Promotion::promotion_actuelle();
		$args = array();
		$args['where']['Promotion'] = $_SESSION['promotion_actuelle'];
		$user = \Session::getInstance()->getCurUser();
		if ($user && $user->get('Classes')) {
			$classes =  $user->get('Classes');
			$args['where'][] = "Classe IN (" . $classes . ")";
			$args['where'][] = "(`Validated` IS NOT NULL)";
		}

		return Inscription::getCount($args, null);
	}

	public function total_services($mois, $service = null)
	{

		$query = <<<END
		SELECT
		SUM(
		CASE
			WHEN fnc_encaissementinscriptions.Montant IS NOT NULL AND fnc_encaissementrubriques.Mensuel = true THEN fnc_encaissementinscriptions.Montant
			WHEN fnc_encaissementinscriptions.Montant IS NOT NULL AND fnc_encaissementrubriques.Mensuel = false AND fnc_encaissementrubriques.Mois = :mois THEN fnc_encaissementinscriptions.Montant
			WHEN fnc_rubriqueprices.Frais IS NOT NULL AND fnc_encaissementrubriques.Optionnel = false AND fnc_encaissementrubriques.Mensuel = true THEN fnc_rubriqueprices.Frais
			WHEN fnc_rubriqueprices.Frais IS NOT NULL AND fnc_encaissementrubriques.Optionnel = false AND fnc_encaissementrubriques.Mensuel = false AND fnc_encaissementrubriques.Mois = :mois THEN fnc_rubriqueprices.Frais
			WHEN fnc_encaissementrubriques.Optionnel = false AND fnc_encaissementrubriques.Mensuel = true THEN fnc_encaissementrubriques.MontantDefaut
			WHEN fnc_encaissementrubriques.Optionnel = false AND fnc_encaissementrubriques.Mensuel = false AND fnc_encaissementrubriques.Mois = :mois THEN fnc_encaissementrubriques.MontantDefaut
			ELSE 0 END ) AS total
		FROM fnc_encaissementrubriques
		LEFT JOIN fnc_rubriqueprices ON fnc_encaissementrubriques.ID = fnc_rubriqueprices.EncaissementRubrique AND fnc_rubriqueprices.Niveau = :niveau
		LEFT JOIN fnc_encaissementinscriptions ON fnc_encaissementrubriques.ID = fnc_encaissementinscriptions.EncaissementRubrique AND fnc_encaissementinscriptions.`Inscription` = :inscription
END;

		if ($service) {
			$query .= <<<END

		WHERE fnc_encaissementrubriques.ID =  :service

END;

			$params = array('inscription' => $this->get('ID'), 'niveau' => $this->get('Niveau')->get('ID'), 'mois' => $mois, 'service' => $service->get('ID'));
		} else {

			$params = array('inscription' => $this->get('ID'), 'niveau' => $this->get('Niveau')->get('ID'), 'mois' => $mois);
		}

		$result = \DB::scallar($query, $params);

		return ($result ? $result : 0);
	}


	public static function getList($args = null, $query = null)
	{
		if (!is_array($args))
			$args = array();

		$user = \Session::getInstance()->getCurUser();

		if ($user && $user->get('Classes')) {
			$classes =  $user->get('Classes');
			$request = "(Classe IN (" . $classes . ") OR Classe IS NULL)";
			$args['where'][] = $request;
			$args['where'][] = "(`Validated` IS NOT NULL)";
		}

		return parent::getList($args, $query);
	}


	public static function getAll($args = null, $query = null)
	{
		return parent::getList($args, $query);
	}

	public function preSuiInscription()
	{
		$sui =  null;
		$pre  = null;
		if ($this->Classe) {
			$ins = array();
			$inscriptions = Inscription::getList(array('where' => array('Classe' => $this->Classe->ID)));


			foreach ($inscriptions as $key => $item) {
				$ins[strtolower($item->get('Eleve')->get('User')->get('Nom') . '_' . $item->get('Eleve')->get('User')->get('Prenom'))] = $item;
			}
			ksort($ins, SORT_STRING | SORT_NATURAL);



			$ins  = array_values($ins);
			$inscriptions = array();
			foreach ($ins as $key => $item) {
				$inscriptions[$item->ID] = $key;
			}



			$key  = $inscriptions[$this->ID];
			if (isset($ins[$key + 1])) {
				$sui = $ins[$key + 1];
			}
			if (isset($ins[$key - 1])) {
				$pre = $ins[$key - 1];
			}

			return (object)array(
				'sui' => $sui,
				'pre' => $pre,
			);
		}
	}

	public function etatDossier($espaceImpression = null)
	{

		// Si $espaceImpression = TRUE ca veut dire qu'on veut pas prendre en consideration les photos.. ID = 2

		$query = DepotDocument::sqlQuery() . <<<END
	JOIN (SELECT `ID` AS `J1ID`, `PromFiliere` AS `J1PromFiliere` FROM `inscriptions`) AS `j1` ON `depotdocuments`.`Inscription` = `j1`.`J1ID`
	JOIN (SELECT `ID` AS `J2ID`, `Promotion` AS `J2Promotion` FROM `promfilieres`) AS `j2` ON `j1`.`J1PromFiliere` = `j2`.`J2ID`
END;
		$inscriptionActuelle = $this;

		$inscriptions = Inscription::getList(
			array('where' => array(
				'Etudiant' => $this->get('Etudiant')->get('ID', null, false),
				'Valide' => true,
				'J1Niveau' => 2,
			)),
			Inscription::sqlQuery() . <<<END
	 JOIN (SELECT `ID` AS `J1ID`, `Promotion` AS `J1Promotion`, `Niveau` AS `J1Niveau` FROM `promfilieres`) AS `j1` ON `inscriptions`.`PromFiliere` = `j1`.`J1ID`
	 JOIN (SELECT `ID` AS `J2ID`, `PromotionActuelle` AS `J2PromotionActuelle` FROM `promotions`) AS `j2` ON `j1`.`J1Promotion` = `j2`.`J2ID`
END
		);

		if ($inscriptions && $this->get('PromFiliere')->get('Niveau')->get('ID') != 1)
			$inscriptionActuelle = $inscriptions[0];

		$totalAdeposer = 0;
		$totalDepose = 0;
		$totalAdeposerCopie = 0;
		$totalDeposeCopie = 0;
		$totalAdeposerFST = 0;
		$totalDeposeFST = 0;
		$documents = array();
		$documents['dossiers'] = array();
		foreach (Document::getList() as $d) {

			if ($espaceImpression && $d->get('ID') == 2)
				continue;

			$depotdocuments = DepotDocument::getList(array('where' => array(
				'Inscription' => $inscriptionActuelle->get('ID'),
				'Document' => $d->get('ID'),
			)), $query);

			$niveauDocuments = array();
			$niveauDocumentsList = DocumentNiveau::getList(array('where' => array('Document' => $d->get('ID'))));
			foreach ($niveauDocumentsList as $item)
				$niveauDocuments[] = $item->get('Niveau')->get('ID');

			if (!$depotdocuments && $niveauDocuments && !in_array($inscriptionActuelle->get('PromFiliere')->get('Niveau')->get('ID'), $niveauDocuments))
				continue;

			if ($d->get('RemplacePar')) {
				$depotdocumentsRemplace = DepotDocument::getCount(array('where' => array(
					'Inscription' => $inscriptionActuelle->get('ID'),
					'Document IN ( ' . $d->get('RemplacePar') . ' )',
				)), $query);

				if (!$depotdocuments && $depotdocumentsRemplace > 0)
					continue;
			}


			$statut = DepotDocument::statut($inscriptionActuelle->get('ID'), $d->get('ID'));
			$reception_fst = DepotDocument::reception_fst($inscriptionActuelle->get('ID'), $d->get('ID'));
			$depot_fst = DepotDocument::depot_fst($inscriptionActuelle->get('ID'), $d->get('ID'));
			$document_envoye = DepotDocument::document_envoye($inscriptionActuelle->get('ID'), $d->get('ID'));

			if (!$niveauDocuments || ($niveauDocuments && in_array($inscriptionActuelle->get('PromFiliere')->get('Niveau')->get('ID'), $niveauDocuments))) {

				$totalAdeposer += $d->get('Nombre');
				$totalAdeposerCopie += 1;
				$totalDepose += ($statut > $d->get('Nombre')) ? $d->get('Nombre') : $statut;
				$totalDeposeCopie += ($statut > 0) ? 1 : 0;

				$totalAdeposerFST += $depot_fst;
				$totalDeposeFST += ($reception_fst > $depot_fst) ? $depot_fst : $reception_fst;
			}


			$documentsItem = array();
			$documentsItem['label'] = $d->get('Label');
			$documentsItem['documents'] = $depotdocuments;
			$documentsItem['document'] = $d;
			$documentsItem['statut'] = $statut;
			$documentsItem['depot_fst'] = $depot_fst;
			$documentsItem['reception_fst'] = $reception_fst;
			$documentsItem['document_envoye'] = $document_envoye;

			$documents['dossiers'][] = $documentsItem;
		}
		if ($totalAdeposer > 0)
			$documents['pct'] = (int) (($totalDepose * 100) / $totalAdeposer);
		else
			$documents['pct'] = 0;

		if ($totalAdeposer > 0)
			$documents['pct_fst'] = (int) (($totalDeposeFST * 100) / $totalAdeposerFST);
		else
			$documents['pct_fst'] = 0;

		if ($totalAdeposer > 0)
			$documents['pct_copie'] = (int) (($totalDeposeCopie * 100) / $totalAdeposerCopie);
		else
			$documents['pct_copie'] = 0;


		return $documents;
	}


	public function  services()
	{
		$inscription_id =  $this->get('ID');

		$services = array();

		$required_services = FIN\EncaissementRubrique::getList(array(
			'where' => array(
				"Optionnel" => false,
			),
		));

		foreach ($required_services as $s) {
			$services[$s->get('ID')] = $s;
		}

		$afected_service = FIN\EncaissementRubrique::getList(
			array(
				'where' => array(
					"ID IN (SELECT `EncaissementRubrique`  FROM `fnc_encaissementinscriptions` where Inscription = $inscription_id)",
				),
			)
		);
		foreach ($afected_service as $s) {
			$services[$s->get('ID')] = $s;
		}

		return array_values($services);
	}




	// return all month  of services
	public function etatEncaisements($service)
	{
		$fnc  =  FIN\EncaissementRubriqueInscription::getList(array('where' => array(
			'EncaissementRubrique' => $service->get('ID'),
			'Inscription' => $this->get('ID')
		)));
		return $fnc;
	}

	// return service inscription of month
	public function inscriptionServices($service, $month)
	{
		$fnc = FIN\EncaissementRubriqueInscription::getList(array('where' => array(
			'EncaissementRubrique' => $service->get('ID'),
			'Inscription' => $this->get('ID'),
			'Mois' => $month,
		)));
		return count($fnc) ? $fnc[0] : null;
	}


	public function lastPaiment($paiement_mode)
	{
		$fncs =	Fin\Encaissement::getList(array('where' => array("PaiementMode = '$paiement_mode'")));
		if (count($fncs)) {
			return $fncs[count($fncs) - 1];
		}
		return null;
	}

	public function  servicesOfMonth($month)
	{

		$inscription_id =  $this->get('ID');

		$services = array();
		$required_services = array();

		// if ($month == 9) {
		// 	$required_services = FIN\EncaissementRubrique::getList(array(
		// 		'where' => array(
		// 			"Optionnel" => false,
		// 		),
		// 	));
		// } else {
		// 	$required_services = FIN\EncaissementRubrique::getList(array(
		// 		'where' => array(
		// 			"Optionnel" => false,
		// 			"Mensuel" => true
		// 		),
		// 	));
		// }

		$required_services = FIN\EncaissementRubrique::getList(array(
			'where' => array(
				"Optionnel" => false,
			),
		));

		foreach ($required_services as $s) {
			$services[$s->get('ID')] = $s;
		}

		$afected_service = FIN\EncaissementRubrique::getList(
			array(
				'where' => array(
					"ID IN (SELECT `EncaissementRubrique`  FROM `fnc_encaissementinscriptions` where Inscription = $inscription_id and Mois = $month)",
				),
			)
		);

		foreach ($afected_service as $s) {
			$services[$s->get('ID')] = $s;
		}

		return array_values($services);;
	}



	public function hasService($service)
	{
		// if (!$service->get('Optionnel')) {
		// 	return $service;
		// }

		$fnc = FIN\EncaissementRubriqueInscription::getList(array('where' => array(
			'EncaissementRubrique' => $service->get('ID'),
			'Inscription' => $this->get('ID'),
		)));
		return count($fnc) ? $fnc[0] : null;
	}



	public function amountOfService($service, $month, $grille)
	{
		$niveau =  $this->get('Niveau');
		$_s = $this->inscriptionServices($service, $month);
		// default montant
		$service_amount =  $service->get('MontantDefaut');
		$rubriquePrice = null;
		if (isset($grille[$niveau->get('ID')]) && isset($grille[$niveau->get('ID')][$service->get('ID')])) {
			$rubriquePrice  = $grille[$niveau->get('ID')][$service->get('ID')];
		} else {
			$rubriquePrice = \Models\FIN\RubriquePrice::rubriquePrice($niveau, $service);
		}
		//  get the montant from grille
		if ($rubriquePrice) {
			$service_amount = $rubriquePrice->get('Frais');
		}
		// get the montant from rubriqueInscription
		if ($_s) {
			$service_amount = $_s->get('Montant');
		}
		return $service_amount;
	}



	// return  all months of services
	public function  servicesMonths()
	{
		$fnc  =  FIN\EncaissementRubriqueInscription::getList(array('where' => array(
			'Inscription' => $this->get('ID')
		)));
		return array_unique(array_map(function ($item) {
			return $item->get('Mois');
		}, $fnc));
	}

	// return  all Month of a service
	public function  monthsOfService($service)
	{
		$months_list = _months_keys;

		$months =  array_unique(array_map(function ($item) {
			return $item->get('Mois');
		}, $this->etatEncaisements($service)));

		return array_filter($months_list, function ($item) use ($months) {
			return in_array($item, $months);
		});
	}


	// return payed month
	public function payedServicesMonth($service)
	{
		$months = array(); // format  $month_order => price ;
		$lignes = FIN\EncaissementLigne::getList(array('where' => array(
			'Inscription' => $this->get('ID'),
			'EncaissementRubrique' => $service->get('ID')
		)));
		foreach ($lignes as $item) {
			if ($item->get('Mois'))
				$months[$item->get('Mois')] = $item->get('Montant');
		}
		return $months;
	}


	public function  yearlyServices()
	{
		return array_filter($this->services(), function ($item) {
			return $item->get('Mensuel') == false;
		});
	}



	public function  monthlyServices()
	{
		return array_filter($this->services(), function ($item) {
			return $item->get('Mensuel') == true;
		});
	}


	public function sumAmountOfYealyService($paiement_mode = null)
	{
		$inscription_id = $this->get('ID');
		$query = "SELECT SUM(Montant) as Amount from fnc_encaissementlignes where  where Canceled IS NULL Inscription = $inscription_id  AND  EncaissementRubrique IN (SELECT ID  FROM  fnc_encaissementrubriques WHERE  Mensuel = 0) AND Mois = 0";
		if ($paiement_mode) {
			$query .= " AND Encaissement IN(select ID from fnc_encaissements where PaiementMode = '$paiement_mode')";
		}
		$result = \DB::reader($query);

		$amount = isset($result[0]) ? ($result[0]['Amount'] ? $result[0]['Amount'] : 0) : 0;

		return $amount;
	}



	// return total of amount of yealyservice
	public function defaultAmountOfYealyServices($niveau, $grille)
	{
		$total = 0;
		foreach ($this->yearlyServices() as $s) {
			if ($s->get('Mensuel')) {
				continue;
			}

			$_s = $this->inscriptionServices($s, 9);
			// default montant
			$service_amount =  $s->get('MontantDefaut');
			$rubriquePrice = null;

			if (isset($grille[$niveau->get('ID')]) && isset($grille[$niveau->get('ID')][$s->get('ID')])) {
				$rubriquePrice  = $grille[$niveau->get('ID')][$s->get('ID')];
			} else {
				$rubriquePrice = Fin\RubriquePrice::rubriquePrice($niveau, $s);
			}
			//  get the montant from grille
			if ($rubriquePrice) {
				$service_amount = $rubriquePrice->get('Frais');
			}

			// get the montant from rubriqueInscription
			if ($_s) {
				$service_amount = $_s->get('Montant');
			}
			$total  += $service_amount;
		}

		return $total;
	}



	public function sumAmountOfMonthOfService($service, $month = null, $paiement_mode = null)
	{
		$service  =  $service->get('ID');
		$inscription_id = $this->get('ID');
		$query = "SELECT SUM(Montant) as Amount from fnc_encaissementlignes where Canceled IS NULL AND EncaissementRubrique = $service and Inscription = $inscription_id " . (!is_null($month) ? "AND Mois = $month" : '');

		if ($paiement_mode) {
			$query .= " AND Encaissement IN(select ID from fnc_encaissements where PaiementMode = '$paiement_mode')";
		}

		$result = \DB::reader($query);

		$amount = isset($result[0]) ? ($result[0]['Amount'] ? $result[0]['Amount'] : 0) : 0;

		return $amount;
	}



	public function sumAmountOfMonth($month)
	{
		$inscription_id = $this->get('ID');
		$query = "SELECT SUM(Montant) as Amount from fnc_encaissementlignes where Canceled IS NULL AND Inscription = $inscription_id and Mois = $month";
		$result = \DB::reader($query);

		$amount = isset($result[0]) ? ($result[0]['Amount'] ? $result[0]['Amount'] : 0) : 0;

		return $amount;
	}


	public function sumAmountOfMonthlyService($month, $paiement_mode = null)
	{
		$inscription_id = $this->get('ID');
		$query = "SELECT SUM(Montant) as Amount from fnc_encaissementlignes where Canceled IS NULL AND  Inscription = $inscription_id AND EncaissementRubrique IN (SELECT ID  FROM  fnc_encaissementrubriques WHERE  Mensuel = 1) and Mois = $month ";
		if ($paiement_mode) {
			$query .= " AND Encaissement IN(select ID from fnc_encaissements where PaiementMode = '$paiement_mode')";
		}
		$result = \DB::reader($query);

		$amount = isset($result[0]) ? ($result[0]['Amount'] ? $result[0]['Amount'] : 0) : 0;

		return $amount;
	}

	// return total of amount of month
	public function defaultAmountOfMonthlyService($month, $niveau, $grille)
	{
		$total = 0;
		foreach ($this->servicesOfMonth($month) as $s) {
			if (!$s->get('Mensuel')) {
				continue;
			}
			$_s = $this->inscriptionServices($s, $month);
			// default montant
			$service_amount =  $s->get('MontantDefaut');
			$rubriquePrice = null;

			if (isset($grille[$niveau->get('ID')]) && isset($grille[$niveau->get('ID')][$s->get('ID')])) {
				$rubriquePrice  = $grille[$niveau->get('ID')][$s->get('ID')];
			} else {
				$rubriquePrice = Fin\RubriquePrice::rubriquePrice($niveau, $s);
			}
			//  get the montant from grille
			if ($rubriquePrice) {
				$service_amount = $rubriquePrice->get('Frais');
			}

			// get the montant from rubriqueInscription
			if ($_s) {
				$service_amount = $_s->get('Montant');
			}
			$total  += $service_amount;
		}

		return $total;
	}

	// return total of amount of month include yealy service
	public function defaultAmountOfMonth($month, $niveau, $grille)
	{
		$total = 0;
		foreach ($this->servicesOfMonth($month) as $s) {
			$_s = $this->inscriptionServices($s, $month);
			// default montant
			$service_amount =  $s->get('MontantDefaut');
			$rubriquePrice = null;

			if (isset($grille[$niveau->get('ID')]) && isset($grille[$niveau->get('ID')][$s->get('ID')])) {
				$rubriquePrice  = $grille[$niveau->get('ID')][$s->get('ID')];
			} else {
				$rubriquePrice = Fin\RubriquePrice::rubriquePrice($niveau, $s);
			}
			//  get the montant from grille
			if ($rubriquePrice) {
				$service_amount = $rubriquePrice->get('Frais');
			}

			// get the montant from rubriqueInscription
			if ($_s) {
				$service_amount = $_s->get('Montant');
			}
			$total  += $service_amount;
		}

		return $total;
	}

	// return payed month
	public function EncaisementsMonthPayed($service)
	{
		return array_unique(array_map(function ($item) {
			return $item->get('Mois');
		}, $this->etatEncaisements($service)));
	}

	// return all status month of a service  
	public function etatEncaisementsMonth($service)
	{
		$payed_month =  $this->payedServicesMonth($service);
		$service_month =  $this->monthsOfService($service);
		$months_list = _months_list;
		return array_map(function ($month) use ($payed_month, $months_list) {
			return (object)array(
				'order' => $month,
				'month' => $months_list[$month],
				'payed' => key_exists($month, $payed_month),
				'montant' => key_exists($month, $payed_month) ? $payed_month[$month] : null,
			);
		}, $service_month);
	}

	// old not used
	public function _etatEncaisementMonthsServices()
	{
		$months_list = $this->Promotion->months()->zero_list;

		$etatMonth = array();
		foreach ($months_list as $key => $month) {
			$statusService =  array();
			foreach ($this->servicesOfMonth($key) as $s) {
				$statusService[] = (object)array(
					'service' => $s,
				);
			}
			$etatMonth[$key] = (object)array(
				'month' => $month,
				'services' => $statusService,
			);
		}
		return $etatMonth;
	}

	//  return all the month width services of each month 
	public function etatEncaisementMonthsServices()
	{
		$months_list = $this->Promotion->months()->zero_list;;
		$etatMonth = array();
		foreach ($months_list as $key => $month) {
			$services = $this->servicesOfMonth($key);
			// separate yearly service from month 9 
			// if you read this code  look the comment
			// if the month key is 9 
			if ($key == 0) {
				// Annuels services
				$yearly_services = array_filter($services, function ($item) {
					return $item->get('Mensuel') == false;
				});

				$monthly_services_of_9 = array_filter($services, function ($item) {
					return $item->get('Mensuel') == true;
				});


				// yearly services 
				//////////////////////////////////
				$statusService =  array();
				foreach ($yearly_services as $s) {
					$statusService[] = (object)array(
						'service' => $s,
					);
				}
				$etatMonth[0] = (object)array(
					'month' => "Frais annuels",
					'services' => $statusService,
				);
				////////////////////////////////////////


				// monthly services  9
				/////////////////////////////////////////
				$statusService =  array();
				foreach ($monthly_services_of_9 as $s) {
					$statusService[] = (object)array(
						'service' => $s,
					);
				}
				$etatMonth[$key] = (object)array(
					'month' => $month,
					'services' => $statusService,
				);
				///////////////////////////////////////////

			} else {
				// after month 9 
				//////////////////////////////////////
				$statusService =  array();
				foreach ($this->servicesOfMonth($key) as $s) {
					if ($s->get('Mensuel')) {
						$statusService[] = (object)array(
							'service' => $s,
						);
					}
				}
				$etatMonth[$key] = (object)array(
					'month' => $month,
					'services' => $statusService,
				);
				//////////////////////////////////////////
			}
		}
		return $etatMonth;
	}
	// part of notes 
	public function  noteMatiere($matiere, $examens, $type_controle, $index_controle)
	{
		$evaluation = isset($examens[$matiere->get('ID')][$type_controle][$index_controle]) ? $examens[$matiere->get('ID')][$type_controle][$index_controle] : null;
		if ($evaluation) {
			$note =  Note::hasNote($evaluation->get('ID'), $this->get('ID'));
			if ($note) {
				return  $note->get('Valeur') ?: 0;
			}
		}
		return null;
	}

	public function  moyeneMatiereReleveNote($matiere, $examens, $evatuations)
	{
		$note_matiere  = 0;
		$somme_cf = 0;
		foreach ($evatuations as $key_evaluation => $evatuation) {
			$note_controles = 0;
			$count_evalautions = 0;
			for ($i = 0; $i < $evatuation->count; $i++) {
				$note = $this->noteMatiere($matiere, $examens, $key_evaluation, $i);
				if (!is_null($note)) {
					$note_controles += $note;
					$count_evalautions++;
				}
			}

			if ($count_evalautions) {
				$somme_cf += $evatuation->coefficient;
				$note_matiere += ($evatuation->coefficient * $note_controles) / ($count_evalautions ?: 1);
			}
		}

		$somme_cf = $somme_cf ?: 1;
		return $note_matiere / $somme_cf;
	}

	public function  moyeneUniteReleveNote($niveau, $matieres, $examens, $evatuations, $type_coefficient = 'Coefficient_Ecole')
	{
		$somme_notes = 0;
		$somme_cf = 0;
		foreach ($matieres as $item) {
			$cf = $item->getCoefficient($niveau, $type_coefficient);
			$somme_cf += $cf;
			$somme_notes += $cf * $this->moyeneMatiereReleveNote($item, $examens, $evatuations);
		}

		$somme_cf = $somme_cf ?: 1;
		return $somme_notes / $somme_cf;
	}
	public function  moyeneMatiere($matiere, $examens, $evatuations)
	{
		$note_matiere  = 0;
		$somme_cf = 0;
		foreach ($evatuations as $key_evaluation => $evatuation) {
			$note_controles = 0;
			for ($i = 0; $i < $evatuation->count; $i++) {
				$note = $this->noteMatiere($matiere, $examens, $key_evaluation, $i);
				if (!is_null($note)) {
					$note_controles += $note;
				}
			}
			$somme_cf += $evatuation->coefficient;
			$note_matiere += $evatuation->coefficient * $note_controles / $evatuation->count;
		}
		$somme_cf = $somme_cf ?: 1;
		return $note_matiere / $somme_cf;
	}

	public function  moyeneUnite($niveau, $matieres, $examens, $evatuations, $type_coefficient = 'Coefficient_Ecole')
	{
		$somme_notes = 0;
		$somme_cf = 0;;
		foreach ($matieres as $item) {
			$cf = $item->getCoefficient($niveau, $type_coefficient);
			$somme_cf += $cf;
			$somme_notes += $cf * $this->moyeneMatiere($item, $examens, $evatuations);
		}

		$somme_cf = $somme_cf ?: 1;
		return $somme_notes / $somme_cf;
	}

	public function  _moyeneUnite($matieres, $examens, $type_controle, $index_controle, $type_coefficient = 'Coefficient_Ecole')
	{
		$somme = 0;
		$count_matiere = 0;;
		foreach ($matieres as $item) {
			$evaluation = isset($examens[$item->get('ID')][$type_controle][$index_controle]) ? $examens[$item->get('ID')][$type_controle][$index_controle] : null;
			if ($evaluation) {
				$coefficient = $item->getCoefficient($this->get('Niveau'), $type_coefficient) ?: 0;
				$note =   $this->noteMatiere($item, $examens, $type_controle, $index_controle);
				if ($note) {
					$somme += $coefficient * $note;
				}
				$count_matiere++;
			}
		}
		$count_matiere = $count_matiere ?: 1;
		return $somme / $count_matiere;
	}

	public function absences($date = null)
	{
		$where = array('Inscription' => $this->get('ID'), 'ValidateAt IS NOT NULL');
		if ($date) {
			$where[] = 'Date LIKE \'%' . $date . '%\'';
			$where[] = 'Retards IS NULL';
		}
		$absences = \Models\Absence::getList(array('where' => $where));

		$result = array(
			'matin' => array(),
			'apres-midi' => array(),

		);
		foreach ($absences as $ab) {
			try {
				$seanceTracking = new SeanceTracking(explode(',', $ab->get('Cours'))[0]);
			} catch (\Exception $e) {
				continue;
			}
			$result[$seanceTracking->getPeriodeHeure()][] = $ab;
		}

		return  $result;
	}


	function retards($date = null)
	{

		$where = array('Inscription' => $this->get('ID'), 'ValidateAt IS NOT NULL');

		if ($date) {
			$where[] = 'Date LIKE \'%' . $date . '%\'';
			$where[] = 'Retards IS NOT NULL';
		}

		$retards = \Models\Absence::getList(array('where' => $where));

		$result = array(
			'matin' => array(),
			'apres-midi' => array(),

		);

		foreach ($retards as $re) {
			try {
				$seanceTracking = new SeanceTracking(explode(',', $re->get('Cours'))[0]);
				$result[$seanceTracking->getPeriodeHeure()][] = $re;
			} catch (\Exception $e) {
				continue;
			}
		}

		return  $result;
	}

	function convertMinutesToHours($time, $format = '%02d,%02d')
	{
		if ($time > 60) {
			$hours = floor($time / 60);
			$minutes = ($time % 60);
			return sprintf($format, $hours, $minutes);
		}
	}


	public function getAbsenceInMinute($date = null)
	{

		$where = array('Inscription' => $this->get('ID'), 'ValidateAt IS NOT NULL');
		if ($date) {
			$where[] = 'Date LIKE \'%' . $date . '%\'';
			$where[] = 'Retards IS NULL';
		}
		$absences = \Models\Absence::getList(array('where' => $where));
		$result = array(
			'matin' => array(),
			'apres-midi' => array(),

		);

		$minutes = 0;

		foreach ($absences as $ab) {
			try {
				$seanceTracking = new SeanceTracking(explode(',', $ab->get('Cours'))[0]);
			} catch (\Exception $e) {
				continue;
			}
			$minutes += $seanceTracking->getMinutes();
		}

		return  $minutes;
	}



	public function defaultAmountOfAllService($month)
	{
		$total = 0;
		foreach ($this->servicesOfMonth($month) as $s) {
			$_s = $this->inscriptionServices($s, $month);
			if ($_s) {
				$service_amount = $_s->get('Montant');
			}
			$total  += $service_amount;
		}

		return $total;
	}

	public function getCA($mois, $service)
	{
		$inscription_service = $this->inscriptionServices($service, $mois);
		$total = 0;
		if ($inscription_service) {
			return $inscription_service->get('Montant');
		}

		return $total;
	}
	public function countAbsences($date = null)
	{
		$where = array('Inscription' => $this->get('ID'), 'ValidateAt IS NOT NULL');
		if ($date) {
			$where[] = 'Date LIKE \'%' . $date . '%\'';
		}
		$absences = \Models\Absence::getCount(array('where' => $where));

		return $absences;
	}

	public function afterSave()
	{
		// ActionLog::catchLog("Modification inscription Of " . $this->Eleve->User->getNomComplet() . "  ", array(
		// 	'server' => $_SERVER,
		// 	'post' => $_POST,
		// 	'get' => $_GET,
		// ), false);
	}

	public function  notifyAdmin()
	{
		try {

			$sendgrid = new \SendGridMail();
			$mail = $sendgrid->mail();
			$mail->addContent("text/html", " ");
			$mail->setTemplateId('d-3b5856069b474477b485e30b9404a0a5');
			$mail->setFrom('contact@boti.education');
			$mail->setReplyTo('contact@boti.education');
			$mail->addSubstitution("dateinscription", (date('Y-m-d H:i:s')));
			$mail->addSubstitution("nomcomplet", $this->Eleve->User->getNomComplet());
			$mail->addSubstitution("niveau", $this->Niveau->Label);
			$mail->addSubstitution("promotion", $this->Promotion->Label);

			$admins = \Models\User::getList(array('where' => array("Role IN (1)")));

			if ($admins) {
				foreach ($admins as $admin) {
					if ($admin->hasAutorisation('receive_inscriptions_email_notifications')) {
						$mail->addTo(trim($admin->get('Email')), $admin->getNomComplet());
					}
				}
			} else {
				if ($email = trim(\Config::get('email')))
					$mail->addTo($email, \Config::get('nom_ecole'));
			}

			$sendgrid->send($mail);
		} catch (Exception $th) {
		}
	}

	public function lastInscription()
	{
		$inscription =  null;
		$promotion = $this->Promotion->lastPromo();
		if ($promotion) {
			$inscription = $this->Eleve->getInscription($this->Promotion->lastPromo());
		}

		return $inscription;
	}

	public function applyRequiredServices()
	{

		$inscription = $this;
		$promotion  =  $inscription->Promotion;
		$months_list    =  $promotion->months()->list;

		$rubriques  = \Models\FIN\EncaissementRubrique::getList(array(
			'where' => array('Optionnel' => false)
		));

		\DB::begin();
		foreach ($rubriques as $key => $rubrique) {
			$montant = $rubrique->getGrilleAmount($promotion, $inscription->Niveau, $inscription->getSite());
			if ($rubrique->get('Mensuel')) {
				foreach ($months_list as $month_key => $month) {
					$rubriqueInscription = \Models\FIN\EncaissementRubriqueInscription::inscriptionHasRubrique($inscription, $rubrique, $month_key);
					if (!$rubriqueInscription) {
						$rubriqueInscription = new \Models\FIN\EncaissementRubriqueInscription();
						$rubriqueInscription
							->set('Inscription', $inscription)
							->set('Promotion', $inscription->Promotion)
							->set('Eleve', $inscription->Eleve)
							->set('Niveau', $inscription->Niveau->get('ID'))
							->set('Classe', ($inscription->Classe ? $inscription->Classe->get('ID') : null))
							->set('Promotion',  $inscription->Promotion)
							->set('EncaissementRubrique', $rubrique->get('ID'))
							->set('Montant', $montant)
							->set('Remarques', date('Y_m_d') . '_affecatation')
							->set('Mois', $month_key)
							->set('User', \Session::user())
							->set('DateAjout', date('Y-m-d'))
							->save();
					}
				}
			} else {
				$rubriqueInscription = \Models\FIN\EncaissementRubriqueInscription::inscriptionHasRubrique($inscription, $rubrique, 0);
				if (!$rubriqueInscription) {
					$rubriqueInscription = new \Models\FIN\EncaissementRubriqueInscription();
					$rubriqueInscription
						->set('Inscription', $inscription)
						->set('Promotion', $inscription->Promotion)
						->set('Eleve', $inscription->Eleve)
						->set('Niveau', $inscription->Niveau->get('ID'))
						->set('Classe', ($inscription->Classe ? $inscription->Classe->get('ID') : null))
						->set('Promotion',  $inscription->Promotion)
						->set('EncaissementRubrique', $rubrique->get('ID'))
						->set('Montant', $montant)
						->set('Remarques', date('Y_m_d') . '_affecatation')
						->set('Mois', 0)
						->set('User',  \Session::user())
						->set('DateAjout', date('Y-m-d'))
						->save();
				}
			}
		}

		\DB::commit();
	}

	public function applyOptionnalServices()
	{

		$inscription = $this;
		$promotion  =  $inscription->Promotion;
		$months_list    =  $promotion->months()->list;
		$rubriques  = \Models\FIN\EncaissementRubrique::getList(array(
			'where' => array('Optionnel' => true)
		));

		\DB::begin();
		$lastInscritpion  = $inscription->lastInscription();
		if ($lastInscritpion) {

			$rubriques = \Models\FIN\EncaissementRubriqueInscription::rubriques($lastInscritpion);

			foreach ($rubriques as $key => $rubrique) {

				if ($rubrique->Function == 'admission') {
					continue;
				}

				if ($rubrique->Optionnel) {
					// last_montant
					$last_montant = $rubrique->getGrilleAmount($promotion, $lastInscritpion->Niveau, $lastInscritpion->getSite());

					$montant = $rubrique->getGrilleAmount($promotion, $inscription->Niveau, $inscription->getSite());


					if ($rubrique->get('Mensuel')) {
						foreach ($months_list as $month_key => $month) {

							$rubriqueInscription = \Models\FIN\EncaissementRubriqueInscription::inscriptionHasRubrique($inscription, $rubrique, $month_key);

							$lastRubriqueInscription = \Models\FIN\EncaissementRubriqueInscription::inscriptionHasRubrique($lastInscritpion, $rubrique, $month_key);

							if ($lastRubriqueInscription) {
								// diff
								$diff_montant  =  ($last_montant - $lastRubriqueInscription->Montant);

								if (!$rubriqueInscription) {
									$rubriqueInscription = new \Models\FIN\EncaissementRubriqueInscription();
									$rubriqueInscription
										->set('Inscription', $inscription)
										->set('Promotion', $inscription->Promotion)
										->set('Eleve', $inscription->Eleve)
										->set('Niveau', $inscription->Niveau->get('ID'))
										->set('Classe', ($inscription->Classe ? $inscription->Classe->get('ID') : null))
										->set('Promotion',  $inscription->Promotion)
										->set('EncaissementRubrique', $rubrique->get('ID'))
										->set('Montant', $montant - $diff_montant)
										->set('Remarques', date('Y_m_d') . 'bash_auto_update')
										->set('Mois', $month_key)
										->set('User', \Session::user())
										->set('DateAjout', date('Y-m-d'))
										->save();
								}
							}
						}
					} else {
						$rubriqueInscription = \Models\FIN\EncaissementRubriqueInscription::inscriptionHasRubrique($inscription, $rubrique, 0);

						$lastRubriqueInscription = \Models\FIN\EncaissementRubriqueInscription::inscriptionHasRubrique($lastInscritpion, $rubrique, 0);
						if ($lastRubriqueInscription) {
							// diff
							$diff_montant  =  ($last_montant - $lastRubriqueInscription->Montant);

							if (!$rubriqueInscription) {
								$rubriqueInscription = new \Models\FIN\EncaissementRubriqueInscription();
								$rubriqueInscription
									->set('Inscription', $inscription)
									->set('Promotion', $inscription->Promotion)
									->set('Eleve', $inscription->Eleve)
									->set('Niveau', $inscription->Niveau->get('ID'))
									->set('Classe', ($inscription->Classe ? $inscription->Classe->get('ID') : null))
									->set('Promotion',  $inscription->Promotion)
									->set('EncaissementRubrique', $rubrique->get('ID'))
									->set('Montant', ($montant - $diff_montant))
									->set('Remarques', date('Y_m_d') . '_bash_auto_update')
									->set('Mois', 0)
									->set('User',  \Session::user())
									->set('DateAjout', date('Y-m-d'))
									->save();
							}
						}
					}
				}
			}
		}

		\DB::commit();
	}

	public function depart($data)
	{


		if ($this->get('Depart')) {
			return null;
		}


		$inscriptionDepart  =  \Models\InscriptionDeparts::create(array(
			'Promotion' => $this->Promotion->ID,
			'Inscription' => $this->ID,
			'Eleve' =>  $this->Eleve->User->getNomComplet(),
			'Classe' =>  $this->Classe ? $this->Classe->ID : null,
			'Depart' => 1,
			'DepartDate' => isset($data['depart_date']) ? $data['depart_date'] : null,
			'DepartMotif' =>  isset($data['depart_motif']) ? $data['depart_motif'] : null,
			'DepartCommentaire' =>  isset($data['depart_commentaire']) ? $data['depart_commentaire'] : null,
			'DepartBy' => \Session::user()->ID,
		));

		$classe_label =  $this->get('Classe') ? $this->get('Classe')->get('Label') : $this->get('Niveau')->Label;

		$this
			->set('Depart', 1)
			->set('DepartMotif',  $inscriptionDepart->DepartMotif)
			->set('DepartCommentaire',  $inscriptionDepart->DepartCommentaire)
			->set('DepartBy',	$inscriptionDepart->DepartBy->ID)
			->set('DepartDate', $inscriptionDepart->DepartDate)
			->set('Classe', null)
			->save();


		// desactiver comptes
		$this->get('Eleve')->get('User')->set('Enabled', 0)->save();
		if (count($this->get('Eleve')->frero()) == 0) {
			$parents = $this->get('Eleve')->parents();
			foreach ($parents as $p) {
				$p->User->set('Enabled', null)->save();
			}
		}

		// Delete next months services
		$monthsList = _months_keys;
		$nextMonth  = date('m', strtotime('first day of +1 month'));
		$startRemoveService = false;
		foreach ($monthsList as $keyMonth) {
			if ($keyMonth == $nextMonth) {
				$startRemoveService = true;
			}
			if ($startRemoveService) {
				$fncInscriptions =  \Models\FIN\EncaissementRubriqueInscription::where(array('Inscription' => $this->get('ID'), 'Mois' => $keyMonth))->get();
				foreach ($fncInscriptions as $fncInscription) {
					$fncInscription->delete();
				}
			}
		}



		// Desacfter from  picks
		$itineraires = \Models\Pick\Itineraire::query()->whereInJson('Eleves', [$this->Eleve->ID])->get();
		foreach ($itineraires as $iti) {
			$eleves = $iti->getArray('Eleves', false, true);
			unset($eleves[array_search($this->Eleve->ID, $eleves)]);
			$iti->setJson('Eleves', $eleves);
			$iti->save();
		}



		// Send mails
		$sendgrid = new \SendGridMail();
		$mail = $sendgrid->mail();
		$mail->addContent("text/html", " ");
		$mail->setTemplateId('d-b24d7f33b1214acfb2c0084807e79221');
		$mail->addTo('ahmed@boti.education', 'Ahmed Semoud');
		$mail->setFrom('ahmed@boti.education');
		$mail->setReplyTo('ahmed@boti.education');
		$mail->setSubject('Départ élève : ' . \Config::get('nom_ecole'));
		$mail->addSubstitution("user", (\Session::user()->getNomComplet()));
		$mail->addSubstitution("datetime", (date('Y-m-d H:i:s')));
		$mail->addSubstitution("nom_eleve", ($inscriptionDepart->Eleve));
		$mail->addSubstitution("classe", ($classe_label));
		$mail->addSubstitution("motif", ($inscriptionDepart->DepartMotif));

		$admins = \Models\User::getList(array('where' => array("Role IN (1)")));
		if ($admins) {
			foreach ($admins as $admin) {
				$mail->addTo(trim($admin->get('Email')), $admin->getNomComplet());
			}
		} else {
			if ($email = trim(\Config::get('email')))
				$mail->addTo($email, \Config::get('nom_ecole'));
		}


		$sendgrid->send($mail);

		//dd($inscriptionDepart);

		return 	$inscriptionDepart;
	}


	public function Undepart($data)
	{

		if (!$this->get('Depart')) {
			return null;
		}

		$inscriptionDepart = \Models\InscriptionDeparts::where(['Inscription' => $this->ID])->first();

		$this
			->set('Depart', null)
			->set('DepartMotif',  null)
			->set('DepartCommentaire',  null)
			->set('DepartBy',	null)
			->set('DepartDate', null)
			->set('Classe', $inscriptionDepart->Classe)
			->save();


		$inscriptionDepart->setJson('Canceled', array(
			'date' => date('Y-m-d H:i:s'),
			'comment' => $data['comment'],
			'user' =>  \Session::user()->ID,
		));


		$inscriptionDepart->save();

		\Models\ActionLog::catchLog("Annulation du départ de l’élève " . ($this->Eleve->getNomComplet()));

		return 	$inscriptionDepart;
	}
}
