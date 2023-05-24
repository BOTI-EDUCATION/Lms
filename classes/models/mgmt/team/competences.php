<?php

namespace Models\MGMT\Team;

use Models\Model as Model;

class Competences extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'mgmt_team_competences';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(

		'Alias' => array(
			'type' => 'varchar',
		),
		'Parent' => array(
			'fk' => 'MGMT\Team\Competences',
		),
		'Titre' => array(
			'type' => 'varchar',
		),
		'Description' => array(
			'type' => 'text',
		),
		'Coefficient' => array(
			'type' => 'int',
		),
		'MasseHoraire' => array(
			'type' => 'int',
		),
		'Created' => array(
			'type' => 'text',
		),
		'UpdateHistory' => array(
			'type' => 'text',
		),
		'Date' => array(
			'type' => 'datetime',
		),
		'Ordre' => array(
			'type' => 'int',
		),
	);


	public static function parent_competences($parentsList = null)
	{
		$where = array();
		if ($parentsList) {
			$where[] = "ID IN $parentsList";
		} else {
			$where[] = 'Parent IS NULL';
		}

		return self::getList(array('where' => $where));
	}

	public static function child_competences($parentsList = null)
	{
		$where = array();
		if ($parentsList) {
			$where[] = "Parent IN $parentsList";
		} else {
			$where[] = 'Parent IS NOT NULL';
		}

		return self::getList(array('where' => $where));
	}

	public static function competences_matrix($parentsList = null)
	{
		$competences = array();
		$where = array();
		$where[] = 'Parent IS NULL';
		if ($parentsList) $where[] = "ID IN $parentsList";

		$parents = self::getList(array('where' => $where));
		foreach ($parents as $parent) {
			$where = array();
			$where['Parent'] = $parent->ID;
			$competences[$parent->ID] = array();
			$competences[$parent->ID]['parent'] = $parent;
			$competences[$parent->ID]['childs'] = self::getList(array('where' => $where));
		}

		return $competences;
	}

	public static function evaluations_competences_matrix()
	{
		$evaluation_competences = array();
		$evaluation_types = EvaluationType::getList();

		foreach ($evaluation_types as $type) {

			$competences = array();

			$eval_type_comps = EvaluationCompetence::getList(array('where' => array(
				'TypeEvaluation' => $type->ID,
				"Competence IN(select ID From mgmt_team_competences where Parent IS NULL) "
			)));

			foreach ($eval_type_comps as $eval_comp) {
				$compID = $eval_comp->Competence->ID;
				$where = array();
				$where['Parent'] = $eval_comp->Competence->ID;
				$competences[$compID] = array();
				$competences[$compID]['parent'] = $eval_comp->Competence;
				$competences[$compID]['childs'] = self::getList(array('where' => $where));
			}

			$evaluation_competences[] = array(
				'evaluation_type' => $type,
				'competences' => $competences
			);
		}

		return $evaluation_competences;
	}

	public static function evaluation_type_competences($typeID)
	{
		$competences = array();

		$eval_type_comps = EvaluationCompetence::getList(array('where' => array('TypeEvaluation' => $typeID, "Competence IN(select ID From mgmt_team_competences where Parent IS NULL)")));

		if (empty($eval_type_comps)) {
			$evaluation_type = null;
			try {
				$evaluation_type = new EvaluationType($typeID);
			} catch (\Throwable $th) {
				//throw $th;
			}
			return array(
				'evaluation_type' => $evaluation_type,
				'competences' => array()
			);
		}

		$type = $eval_type_comps[0]->TypeEvaluation;

		foreach ($eval_type_comps as $eval_comp) {
			$compID = $eval_comp->Competence->ID;
			$where = array();
			$where['Parent'] = $eval_comp->Competence->ID;
			$competences[$compID] = array();
			$competences[$compID]['parent'] = $eval_comp->Competence;
			$competences[$compID]['childs'] = self::getList(array('where' => $where));
		}

		return array(
			'evaluation_type' => $type,
			'competences' => $competences
		);
	}

	public static function competences_matrix_filter($filter)
	{
		$competences = array();
		$where = array();
		$where[] = 'Parent IS NULL';
		$parents = self::getList(array('where' => $where));

		foreach ($parents as $parent) {
			$where = array();
			// $where['Parent'] = $parent->ID;
			$where[] = "`Parent` = $parent->ID AND (`Titre` LIKE '%$filter%' OR `Description` LIKE '%$filter%')";
			$competences[$parent->ID] = array();
			$competences[$parent->ID]['parent'] = $parent;
			$competences[$parent->ID]['childs'] = self::getList(array('where' => $where));
		}

		// $k = $parentID, $v = array('parent','childs')
		$competences = array_filter($competences, function ($v, $k) use ($filter) {
			if (
				count($v['childs']) ||
				stripos($v['parent']->Titre, $filter) !== false ||
				stripos($v['parent']->Description, $filter) !== false
			)
				return true;
		}, ARRAY_FILTER_USE_BOTH);

		return $competences;
	}

	public function subCompetencesByMasse()
	{
		return Competences::getList(array('where' => array('Parent' => $this->get('ID'))));
	}
}
