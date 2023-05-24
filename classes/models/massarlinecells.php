<?php

namespace Models;

class MassarLineCells extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'massar_line_cells';
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
		'Matiere' => array(
			'fk' => 'Matiere',
		),
		'Promotion' => array(
			'fk' => 'Promotion',
		),
		'TypeEvaluation' => array(
			'type' => 'text',
		),
		'EvaluationRang' => array(
			'type' => 'int',
		),
		'Semestre' => array(
			'type' => 'int',
		),
		'LigneMassar' => array(
			'type' => 'varchar',
		),
		'ColonneMassar' => array(
			'type' => 'varchar',
		),
		'EditHistory' => array(
			'type' => 'text',
		),

	);


	public static function getIndices($niveau, $unite, $semestre, $matiere =  null, $type_evalaution = null, $evaluation_rang = null)
	{

		$where = array(
			'Niveau' => $niveau->ID,
			'Unite' => $unite->ID,
			'Semestre' => $semestre
		);
		if ($matiere) {
			$where['Matiere'] = $matiere->ID;
		}
		if ($type_evalaution) {
			$where['TypeEvaluation'] = $type_evalaution->Code;
		}
		if ($evaluation_rang) {
			$where['EvaluationRang'] = $evaluation_rang;
		}

		$items = self::getList(array('where' => $where));
		return count($items) ? $items[0] : null;
	}
}
