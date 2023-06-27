<?php

namespace Models\LMS;


use \Models\Model;
use Session;

class Lecons extends Model
{



	protected static $sqlQueries = [];

	protected static $table = 'lms_lecons';

	protected static $pk = [
		'ID' => [
			'auto' => true,
		],
	];

	protected static $fields = [
		'Icone' => [
			'type' => 'varchar',
		],
		'Niveau' => [
			'fk' => 'Niveau',
		],
		'Unite' => [
			'fk' => 'Unite',
		],
		'Matiere' => [
			'fk' => 'Matiere',
		],
		'Rubrique' => [
			'fk' => 'LMS\LeconRubrique',
		],
		'Label' => [
			'type' => 'varchar',
		],
		'Alias' => [
			'type' => 'varchar',
		],
		'Introduction' => [
			'type' => 'text',
		],
		'Objectifs' => [
			'type' => 'text',
		],
		'Syllabus' => [
			'type' => 'text',
		],
		'Instructions' => [
			'type' => 'text',
		],
		'Prerequis' => [
			'type' => 'text',
		],
		'Ordre' => [
			'type' => 'int',
		],
		'Duree' => [
			'type' => 'int',
		],
		'Code' => [
			'type' => 'varchar',
		],
		'Enabled' => [
			'type' => 'boolean',
		],
		'Date' => [
			'type' => 'datetime',
		]
	];
	public function getIcone()
	{
		if (!$this->Icone) {
			// return 'https://via.placeholder.com/300/004c68/FFF?text=Image';
			return null;
		}

		return \URL::base() . \Config::get('path-lms-files') . '/lecons_files/'  . $this->get('Icone');
	}
	public function nextLecon($lec)
	{
		$order = (int)$this->Ordre + 1;
		$next_lecon = Lecons::getList(['where' => ['Rubrique' => $this->Rubrique->ID, 'Ordre' => $order]]);
		$next_lecon = isset($next_lecon) && isset($next_lecon[0]) ? $next_lecon[0] : null;
		// echo '<pre>';
		// // print_r($this->Rubrique->ID);
		// print_r($lec);
		// echo '</pre>';
		// exit;
		if (!$next_lecon) {
			$rubriques = LeconRubrique::getList();
			$rubrique_id = null;
			foreach ($rubriques as $key => $rubrique) {
				# code...
				if ($rubrique->ID == $this->Rubrique->ID) {
					$rubrique_id = isset($rubriques[$key + 1]) ? $rubriques[$key + 1]->ID : $rubriques[$key]->ID;
					break;
				}
			}
			if ($rubrique_id) {
				$whereNot = 'Rubrique = ' . $rubrique_id;
				$next_lecons = Lecons::getList(['where' => $whereNot]);
				foreach ($next_lecons as $key => $next_lecon_) {
					if ($next_lecon_->ID ==  $lec) {
						$next_lecon = isset($next_lecons[$key + 1]) ? $next_lecons[$key + 1] : $next_lecons[$key];
						break;
					} else if ($next_lecon_->ID !=  $lec) {
						$next_lecon = isset($next_lecons[$key]) ? $next_lecons[$key] : null;
						break;
					} else {
						$next_lecon = isset($next_lecons[count($next_lecons) - 1]) ? $next_lecons[count($next_lecons) - 1] : null;
						break;
					}
				}
			}
			// echo '<pre>';
			// print_r($next_lecon);
			// // print_r($rubriques);
			// echo '</pre>';
			// exit;
		}
		return $next_lecon ? $next_lecon->ID : null;
	}
	public function getPercent()
	{
		$trackings = null;
		if (Session::user()) {

			$trackings = Tracking::getList(['where' => ['Lecon' => $this->ID, 'Teacher' => Session::user()->ID, 'ID IN (SELECT Tracking FROM lms_teacher_tracking_history ORDER BY Tracking DESC)']]);
		}
		$percent = 0;
		if ($trackings) {
			$percent = $trackings[count($trackings) - 1]->Percent > 100 ? 100 : $trackings[count($trackings) - 1]->Percent;
		}
		return $percent;
	}
	public function getLastContent()
	{
		$trackings = null;
		if (Session::user()) {
			$trackings = Tracking::getList(['where' => ['Lecon' => $this->ID, 'Teacher' => Session::user()->ID, 'ID IN (SELECT Tracking FROM lms_teacher_tracking_history ORDER BY Tracking DESC)']]);
		}
		$get_last_content = null;
		if ($trackings && $trackings[count($trackings) - 1]) {
			$get_last_content = $trackings[count($trackings) - 1]->Step ? $trackings[count($trackings) - 1]->Step->ID : null;
		}
		return $get_last_content;
	}
	public function getLastRessource()
	{
		$trackings = null;
		if (Session::user()) {
			$trackings = Tracking::getList(['where' => ['Lecon' => $this->ID, 'Teacher' => Session::user()->ID, 'ID IN (SELECT Tracking FROM lms_teacher_tracking_history ORDER BY Tracking DESC)']]);
		}
		$get_last_ressource = null;
		if ($trackings && $trackings[count($trackings) - 1]) {
			$get_last_ressource = $trackings[count($trackings) - 1]->Ressource ? $trackings[count($trackings) - 1]->Ressource->ID : null;
		}
		return $get_last_ressource;
	}
	public function getRessourcesDuree()
	{
		$lecon_ressources = Ressource::where(array('Lecon' => $this->ID))->get();
		$duree = 0;
		foreach ($lecon_ressources as $lecon_ressource) {
			$lecon_ressource_contents = RessourceContent::where(array('Ressource' => $lecon_ressource->ID))->get();
			foreach ($lecon_ressource_contents as $lecon_ressource_content) {

				$duree = $duree + $lecon_ressource_content->get('Duree');
			}
		}
		return $duree;
	}
}
