<?php
namespace Models;

class HDNatureDemandeQuestion extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'dsk_naturesquestions';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Nature' => array(
			'fk' => 'HDNatureDemande',
			'type' => 'int',
		),
		'Label' => array(
			'type' => 'varchar',
		),
		'Ordre' => array(
			'type' => 'int',
		),
		'Required' => array(
			'type' => 'boolean',
		),
		'Field' => array(
			'type' => 'varchar',
		),
		'Autre' => array(
			'type' => 'boolean',
		),
	);
	
	public function getReponses() {
		return  HDNatureDemandeReponse::getList(array('where' => array('Question'=> $this->get('ID'))));
	}
	
	public function deleteReponses() {
		return \DB::noQuery('DELETE FROM `hdnaturedemandereponses` WHERE `NatureDemandeQuestion` = ?', array($this->get('ID')));
	}
	
	
}
