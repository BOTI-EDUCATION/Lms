<?php
namespace Models\DSK;
use \Models\Model;

class Demande extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'dsk_demandes';

	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Nature' => array(
			'fk' => 'DSK\\Nature',
		),
		'User' => array(
			'fk' => 'User',
		),
		'Responsable' => array(
			'fk' => 'User',
		),
		'Inscription' => array(
			'fk' => 'Inscription',
		),
		'UserInterne' => array(
			'fk' => 'User',
		),
		'Statut' => array(
			'fk' => 'DSK\\Statut',
		),
		'Priorite' => array(
			'fk' => 'DSK\\Priorite',
		),
		'Label' => array(
			'type' => 'varchar',
		),
		'Notes' => array(
			'type' => 'text',
		),
		'NotesAdministration' => array(
			'type' => 'text',
		),
		'Date' => array(
			'type' => 'datetime',
		),
		'DateMiseAjour' => array(
			'type' => 'datetime',
		),
		'Validation' => array(
			'type' => 'datetime',
		),
		'ValidationUser' => array(
			'fk' => 'User',
		),
		'Cloture' => array(
			'type' => 'datetime',
		),
		'ClotureUser' => array(
			'fk' => 'User',
		),
	);

	public function checkDureeTraitement() {
		$dateNow = new \Datetime();

		$dateDemande = new \Datetime($this->get('Date'));
		if ($this->get('Nature')->get('DureeTraitement'))
			$dateDemande->add(new \DateInterval('P'.$this->get('Nature')->get('DureeTraitement').'D'));
		else
			return false;

		return $dateNow >= $dateDemande;
	}

	public function deleteReponses() {
		return \DB::noQuery('DELETE FROM `dsk_demandesreponses` WHERE `Demande` = ?', array($this->get('ID')));
	}


	public static function getList($args = null, $query = null)
	{
		if (!is_array($args))
			$args = array();
		$user = \Session::getInstance()->getCurUser();
		if ($user && $user->get('Classes')) {
			$classes =  $user->get('Classes');
			$args['where'][] = "Inscription IN (SELECT ID FROM ins_inscriptions where Classe IN (" . $classes . "))";
		}

		return parent::getList($args, $query);
	}

}
