<?php

namespace Models\MGMT\Team;

use Models\Model as Model;

class Absence extends Model
{
	protected static $sqlQueries = array();

	protected static $table = 'mgmt_absences';

	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Absence' => array(
			'type' => 'varchar',
		),
		'User' => array(
			'fk' => 'User',
		),
		'Retard' => array(
			'type' => 'int',
		),
		'Date' => array(
			'type' => 'datetime',
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
		'JustifieLe' => array(
			'type' => 'datetime',
		),
		'MotifAbsAlias' => array(
			'type' => 'varchar',
		),
		'MotifAbsLabel' => array(
			'type' => 'text',
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
		'Validation' => array(
			'type' => 'json',
		),
		'Deleted' => array(
			'type' => 'json',
		),
	);

	public function getJustifieFileName()
	{
		$name = 'Justification d\'absence ' . $this->get('User')->getNomComplet();
		$ext = pathinfo($this->get('JustifieFile'), PATHINFO_EXTENSION);
		return \Tools::getAlias($name) . '.' . $ext;
	}

	public function getJustifieFileLink()
	{
		return 	\GoogleStorage::getUrl(\Config::get('path-mgmt-docs-absences') . $this->get('JustifieFile'));
	}

	public static function minRetardsParPeriode($periode)
	{
		$minRetards = \DB::scallar('SELECT (SUM(`Retard`)) FROM `mgmt_absences` WHERE Retard > 0 AND Date BETWEEN \'' . $periode[0] . '\' AND \'' . $periode[1] . '\'');

		return $minRetards ? $minRetards : 0;
	}

	public static function getList($args = null, $query = null)
	{
		if (!is_array($args))
			$args = array();

		$args['where'][] = 'Deleted IS NULL';

		return parent::getList($args, $query);
	}
	static function  getMotifs()
	{
		$items =  [
			'maladie' => 'Maladie',
			'Administratif' => 'Administratif',
			'Naissance' => 'Naissance',
			'deces' => 'Décès',
			'Mariage' => 'Mariage',
			'Examen' => 'Examen',
			'Voyage' => 'Voyage',
			'Autres' => 'Autres',
		];


		return  $items;
	}
	public static function countAbsences($dateDebut, $dateFin)
	{
		$query = <<<END
		SELECT DATE_FORMAT(Date,'%Y-%m') AS Date, count(*) AS Count
		FROM mgmt_absences
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
		FROM mgmt_absences
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
}
