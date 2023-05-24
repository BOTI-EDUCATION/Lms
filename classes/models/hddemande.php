<?php
namespace Models;

class HDDemande extends Model {
	
	protected static $sqlQueries = array();
	
	protected static $table = 'dsk_demandes';
	
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'User' => array(
			'fk' => 'User',
		),
		'Inscription' => array(
			'fk' => 'Inscription',
		),
		'User' => array(
			'fk' => 'User',
		),
		'Responsable' => array(
			'fk' => 'User',
		),
		'Statut' => array(
			'fk' => 'HDStatutDemande',
		),
		'Nature' => array(
			'type' => 'int',
			'fk' => 'HDNatureDemande',
		),
		'Priorite' => array(
			'type' => 'int',
			'fk' => 'HDDegreUrgence',
		),
		'Label' => array(
			'type' => 'varchar',
		),
		'Notes' => array(
			'type' => 'text',
		),
		'DateMiseAjour' => array(
			'type' => 'datetime',
		),
		'Date' => array(
			'type' => 'datetime',
		),
		'Cloture' => array(
			'type' => 'datetime',
		),
	);
	
	
	public function checkDureeTraitement() {
		$dateNow = new \Datetime();
		
		$dateDemande = new \Datetime($this->get('Date'));
		if($this->get('NatureDemande')->get('DureeTraitement'))
			$dateDemande->add(new \DateInterval('P'.$this->get('NatureDemande')->get('DureeTraitement').'D'));
		else 
			return false;
		
		if($dateNow >= $dateDemande)
			return true;
		return false;
	}
	
	
}
