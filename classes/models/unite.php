<?php

namespace Models;

class Unite extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'sco_unites';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Label' => array(
			'type' => 'varchar',
		),
		'Discipline' => array(
			'type' => 'varchar',
		),
		'LabelAr' => array(
			'type' => 'varchar',
		),
		'Function' => array(
			'type' => 'varchar',
		),
		'IsRTL' => array(
			'type' => 'boolean',
		),
		'Icone' => array(
			'type' => 'varchar',
		),
		'Ordre' => array(
			'type' => 'int',
		),
		'Enabled' => array(
			'type' => 'boolean',
		),
		'Color' => array(
			'type' => 'varchar',
		),
		'TextColor' => array(
			'type' => 'varchar',
		),
		'MassarOrderLigne' => array(
			'type' => 'varchar',
		),
	);



	public function maxNote($niveau)
	{
		$uns = NiveauUnite::getList(array('where' => array('Unite' => $this->get('ID'), 'Niveau' => $niveau)));
		if (count($uns))
			return $uns[0]->get('Extremite');
		return 10;
	}

	public function getMatieres()
	{
		$matieres = Matiere::getList(array('where' => array('Unite' => $this->get('ID'))));

		return $matieres;
	}


	public function  getEnseignants()
	{
		$items = EnseignantUnite::getList(array('where' => array('Unite' => $this->get('ID'))));

		$enseignants = [];
		foreach ($items as $key => $item) {

			try {
				$ens = $item->get('Enseignant');
				if ($ens->User->DeletedAt) {
					continue;
				}

				$enseignants[] = $ens;
			} catch (\Exception $th) {
				continue;
			}
		}

		return $enseignants;
	}

	public function  getClasseEnseignants($classe)
	{
		$query = EnseignantUnite::sqlQuery(true);
		$query .= <<<END
			JOIN (SELECT `ID` AS `J1ID`,  `Enseignant` AS `J1Enseignant` ,   `Classe` AS `J1Classe` FROM `sco_enseignantclasses` where Classe = $classe) AS `j1` ON `sco_enseignantunite`.`Enseignant` = `j1`.`J1Enseignant` 
END;

		$items = EnseignantUnite::getList(array('where' => array('Unite' => $this->get('ID'))), $query);


		$enseignants = [];
		foreach ($items as $key => $item) {

			try {
				$ens = $item->get('Enseignant');
				if ($ens->User->DeletedAt) {
					continue;
				}

				$enseignants[] = $ens;
			} catch (\Exception $th) {
				continue;
			}
		}

		return $enseignants;
	}


	public function  getClassesEnseignants($classes)
	{
		$query = EnseignantUnite::sqlQuery(true);
		$query .= <<<END
			JOIN (SELECT `ID` AS `J1ID`,  `Enseignant` AS `J1Enseignant` ,   `Classe` AS `J1Classe` FROM `sco_enseignantclasses` where Classe IN ("$classes")) AS `j1` ON `sco_enseignantunite`.`Enseignant` = `j1`.`J1Enseignant` 
END;

		return EnseignantUnite::getList(array('where' => array('Unite' => $this->get('ID'))), $query);
	}


	public function getProgramtionMatieres($niveau, $type_coefficient = null)
	{

		$where =   array('Niveau' => $niveau, 'Unite' => $this->get('ID'));
		if ($type_coefficient) {
			$where[] = $type_coefficient . ' IS NOT NULL';
		} else {
			$where[] = 'Coefficient_Ecole IS NOT NULL';
		};
		$items = NiveauUniteMatiere::getList(array('where' => $where, 'order' => array('Ordre' => true)));
		return array_map(function ($item) {
			return $item->get('Matiere');
		}, $items);
	}


	public function getNiveauxUnite()
	{
		$items = NiveauUnite::getList(array('where' => array('Unite' => $this->get('ID'))));

		return count($items) ? $items[0] : null;
	}


	public function getClasses()
	{
		$classes = array();
		$items = Classe::getList(array('where' => array('Unite' => $this->get('ID'), 'Promotion' => $_SESSION['promotion_actuelle'])));
		if (!$items)
			return null;
		foreach ($items as $item)
			$classes[$item->get('Unite')->get('ID')] = $item;
		return $classes;
	}


	public function nextUnite()
	{
		$items = null;
		if ($this->get('Ordre'))
			$items = Unite::getList(array('where' => array('Ordre > ' . $this->get('Ordre')), 'order' => array('Ordre' => true)));

		if (!$items)
			return null;
		return $items[0];
	}

	public function ifLastUnite()
	{
		$items = Unite::getCount(array('where' => array('Ordre > ' . $this->get('Ordre')), 'order' => array('Ordre' => true)));
		if ($items > 0)
			return false;
		return true;
	}

	public function getCountElevesNonAffectes($promotion = null)
	{
		if (!$promotion)
			$promotion = Promotion::promotion_actuelle();
		return   Inscription::getCount(array('where' => array('Unite' => $this->get('ID'), 'Promotion' => $promotion->get('ID'), 'Classe IS NULL')));
	}
	public function getCountClasses($promotion = null)
	{
		if (!$promotion)
			$promotion = Promotion::promotion_actuelle();
		return   Classe::getCount(array('where' => array('Unite' => $this->get('ID'), 'Promotion' => $promotion->get('ID'))));
	}
	public function getCountEleves($promotion = null)
	{
		if (!$promotion)
			$promotion = Promotion::promotion_actuelle();
		return  Inscription::getCount(array('where' => array('Unite' => $this->get('ID'), 'Promotion' => $promotion->get('ID'))));
	}
	public function getElevesNonAffectes($promotion = null)
	{
		if (!$promotion)
			$promotion = Promotion::promotion_actuelle();
		return  Inscription::getList(array('where' => array('Unite' => $this->get('ID'), 'Promotion' => $promotion->get('ID'),  'Classe IS NULL')));
	}

	public function getInscriptions($promotion = null)
	{
		if (!$promotion)
			$promotion = Promotion::promotion_actuelle();
		return  Inscription::getList(array('where' => array('Unite' => $this->get('ID'), 'Promotion' => $promotion->get('ID')), 'order' => array('Ordre' => true)));
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

	public function getIcone($full_link = false)
	{

		$icon = $this->get('Icone');
		if (!$icon || !file_exists(_basepath . \Config::get('path-matieres') . $this->get('Icone'))) {
			$icon = 'default1.png';
		}

		return ($full_link ? \URL::AbsoluteLink() : \URL::base('')) . \Config::get('path-matieres') . $icon;
	}

	public function getCoefficient($niveau, $type = 'Coefficient_Ecole')
	{
		$coefficient = \DB::scallar('SELECT ' . static::wrapField($type) . ' FROM ' . static::wrapField('sco_niveaux_unites') . ' WHERE Unite = ? and Niveau=?', array($this->get('ID'), $niveau->get('ID')));

		if (!$coefficient)
			return null;

		return $coefficient;
	}


	static function  disciplines($key = null)
	{
		$items =  [
			'litteraire' => 'Littéraire',
			'scientifique' => 'Scientifique',
		];

		if ($key) {
			return isset($items[trim($key)]) ? $items[trim($key)] : '---';
		}

		return  $items;
	}

	function isDispensed($inscription)
	{
		if ($this->Function != 'sport') {
			return false;
		}

		return	$inscription->Dispense;
	}
}
