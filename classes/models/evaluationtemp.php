<?php

namespace Models;

class EvaluationTemp extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'sco_evaluations_temp';
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
		'Ennonce' => array(
			'type' => 'varchar',
		),
		'Valide' => array(
			'type' => 'datetime',
		),
		'ValideBy' => array(
			'fk' => 'User',
		),
		'Rejected' => array(
			'type' => 'datetime',
		),
		'RejectedBy' => array(
			'fk' => 'User',
		),
		'Date' => array(
			'type' => 'datetime',
		),
		'By' => array(
			'fk' => 'User',
		),
	);


	public static function getList($args = null, $query = null)
	{
		if (!is_array($args))
			$args = array();

		$user = \Session::getInstance()->getCurUser();

		if ($user && $user->get('Classes')) {
			$classes =  $user->get('Classes');
			$request = "(Classe IN (" . $classes . "))";
			$args['where'][] = $request;
		}

		return parent::getList($args, $query);
	}

	public static function getEvaluationValider()
	{
		$where =  array('(`Rejected` IS NULL) AND (`Valide` IS NULL)');
		return self::getList(array('where' => $where));
	}

	public static function getCountEvaluationValider()
	{
		$where =  array('(`Rejected` IS NULL) AND (`Valide` IS NULL)');
		return self::getCount(array('where' => $where));
	}


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


	public static function getEvaluations($classe, $matiere, $semestre, $type_evaluation = null)
	{

		$where =  array(
			'Classe' => $classe->ID,
			'Matiere' => $matiere->ID,
			'Semestre' => $semestre,
		);

		if ($type_evaluation) {
			$where['TypeExam'] = $type_evaluation;
		}

		return self::getList(array('where' => $where));
	}

	public function getRang()
	{
		return (Evaluation::where(
			array(
				'Classe' => $this->Classe->ID,
				'Matiere' => $this->Matiere->ID,
				'Semestre' => $this->Semestre,
				'TypeExam' => $this->TypeExam->ID,
			)
		)->count());
	}
}
