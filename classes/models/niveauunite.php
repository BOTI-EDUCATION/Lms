<?php

namespace Models;

class NiveauUnite extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'sco_niveaux_unites';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Niveau' => array(
			'fk' => 'Niveau',
			'type' => 'int',
		),
		'Unite' => array(
			'fk' => 'Unite',
			'type' => 'int',
		),
		'Extremite' => array(
			'type' => 'int',
		),
		'Coefficient_Ecole' => array(
			'type' => 'int',
		),
		'Coefficient_Massar' => array(
			'type' => 'int',
		),
		'Evaluations' => array(
			'type' => 'varchar', //json
		),
		'EvaluationsS2' => array(
			'type' => 'varchar', //json
		),
		'Ordre' => array(
			'type' => 'int',
		)
	);




	public function getProgrammation($semestre = 1)
	{
		//return array ('TypeEvaluation_Code', array('Nombre', 'Coefficient')
		$evals_array = explode('_', ($semestre == 1) ? $this->get('Evaluations') : $this->get('EvaluationsS2'));
		$programmation = array();

		foreach ($evals_array as $line) {
			$eval_array = explode(',', $line);
			$code_type_evaluation = $eval_array[0];
			$type_evaluation = TypeEvaluation::getByCode($code_type_evaluation);

			if (!$type_evaluation)
				continue;

			$programmation[$code_type_evaluation] =  array('Nombre' => $eval_array[1], 'Coefficient'  => $eval_array[2]);
		}
		return $programmation;
	}

	public function getMatieres()
	{
		$matieres = array();
		$items = NiveauUniteMatiere::getList(
			array(
				'where' => array('Niveau' => $this->get('Niveau')->get('ID'), 'Unite' => $this->get('Unite')->get('ID')),
				'order' => array('Ordre' => true)
			)

		);
		if (!$items)
			return null;
		foreach ($items as $item)
			$matieres[$item->get('Matiere')->get('ID')] = $item;
		return $matieres;
	}


	public function getProgramationEvaluationsControles($semestre)
	{
		$evaluation_programme = array();
		$evaluations = $this->getProgrammation($semestre);
		foreach ($evaluations as $key => $evaluation) {
			$type_evaluation = TypeEvaluation::getByCode($key);
			if ($evaluation['Nombre'] != 0) {
				$evaluation_programme[$type_evaluation->get('ID')] = (object)array(
					'code' => $key,
					'id' => $type_evaluation->get('ID'),
					'count' => $evaluation['Nombre'],
					'range' => range(0, $evaluation['Nombre'] - 1),
					'coefficient' => $evaluation['Coefficient'],
					'label' => $type_evaluation->get('Label'),
					'labelAr' => $type_evaluation->get('LabelAr')
				);
			}
		}
		return $evaluation_programme;
	}
}
