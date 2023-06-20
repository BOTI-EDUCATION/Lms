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
			return 'https://via.placeholder.com/300/004c68/FFF?text=Image';
		}

		return \URL::base() . \Config::get('path-lms-files') . '/lecons_files/'  . $this->get('Icone');
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
