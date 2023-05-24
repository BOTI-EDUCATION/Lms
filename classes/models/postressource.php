<?php

namespace Models;

class PostRessource extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'com_ressources';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Label' => array(
			'type' => 'varchar',
		),
		'Introduction' => array(
			'type' => 'varchar',
		),
		'Keywords' => array(
			'type' => 'text',
		),
		'Content' => array(
			'type' => 'text',
		),
		'Image' => array(
			'type' => 'varchar',
		),
		'Niveau' => array(
			'fk' => 'Niveau',
		),
		'Unite' => array(
			'fk' => 'Unite',
		),
		'Matieres' => array(
			'type' => 'text',
		),
		'Cours' => array(
			'type' => 'text',
		),
		'TeacherByID' => array(
			'type' => 'int',
		),
		'TeacherByName' => array(
			'type' => 'varchar',
		),
		'CreationMeta' => array(
			'type' => 'text',
		),
		'EditHistory' => array(
			'type' => 'text',
		),
		'Enabled' => array(
			'type' => 'boolean',
		),
		'PlanificationPeriodStart' => array(
			'type' => 'varchar',
		),
		'PlanificationPeriodEnd' => array(
			'type' => 'varchar',
		),
		'Competences' => array(
			'type' => 'text',
		),
		'Deleted' => array(
			'type' => 'text',
		),
		'CreatedBy' => array(
			'fk' => 'User',
		)

	);


	public function getImage()
	{
		 $url =  $this->get('Image') ? \GoogleStorage::getUrl( \Config::get('path-images-ressources') . $this->get('Image')) : null;
	
		if(!$url) {
			return '';
		}

		return $url;
	}

	public function getNiveaux()
	{
		if (!count($this->getNiveauxIds())) {
			return array();
		}
		$items = Niveau::getList(array('where' => array('ID IN (' . implode(',', $this->getNiveauxIds()) . ')')));

		return $items;
	}

	public function getNiveauxIds()
	{
		return $this->get('Niveaux') ? explode(',', str_replace('"', '', $this->get('Niveaux'))) : array();
	}

	public function getMatieres()
	{
		if (!count($this->getMatieresIds())) {
			return array();
		}
		$items = Matiere::getList(array('where' => array('ID IN (' . implode(',', $this->getMatieresIds()) . ')')));

		return $items;
	}

	public function getMatieresIds()
	{
		return $this->get('Matieres') ? explode(',', str_replace('"', '', $this->get('Matieres'))) : array();
	}

	public function getQuestions()
	{
		return $this->get('Content') ? json_decode($this->get('Content'), true) : array();
	}

	public function getTracking()
	{
		return RessourceTraking::getList(array('where' => array('Ressource' => $this->get('ID'))));
	}
}
