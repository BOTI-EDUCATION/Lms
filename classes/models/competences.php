<?php

namespace Models;

class Competences extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'sco_competences';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);


	protected static $fields = array(

		'SupCompetence' => array(
			'fk' => 'Competences',
		),
		'Niveaux' => array(
			'type' => 'text',
		),
		'Unites' => array(
			'type' => 'text',
		),
		'Matiere' => array(
			'fk' => 'Matiere',
		),
		'Titre' => array(
			'type' => 'varchar',
		),
		'Titrearabe' => array(
			'type' => 'varchar',
		),
		'Description' => array(
			'type' => 'text',
		),
		'MasseHoraire' => array(
			'type' => 'int',
		),
		'DateDebut' => array(
			'type' => 'date',
		),
		'DateFin' => array(
			'type' => 'date',
		),
		'Semestre' => array(
			'type' => 'varchar',
		),
		'Created' => array(
			'type' => 'text',
		),
		'Updated' => array(
			'type' => 'text',
		),
		'LastUpdatedAt' => array(
			'type' => 'datetime',
		),
		'Date' => array(
			'type' => 'datetime',
		),
	);


	public function hasChild()
	{
		$competences = Competences::getCount(array('where' => array('SupCompetence' => $this->get('ID'))));
		return $competences > 0 ? true : false;
	}

	public function subCompetences()
	{
		$competences = Competences::getList(array('where' => array('SupCompetence' => $this->get('ID'))));
		return $competences;
	}

	public function subCompetencesByMasse()
	{

		return Competences::getList(array('where' => array('SupCompetence' => $this->get('ID'))));
	}
}
