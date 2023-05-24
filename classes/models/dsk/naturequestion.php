<?php
namespace Models\DSK;
use \Models\Model;

class NatureQuestion extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'dsk_naturesquestions';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Nature' => array(
			'fk' => 'DSK\\Nature',
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
		return NatureQuestionReponse::getList(array('where' => array('Question'=> $this->get('ID')), 'order' => array('Ordre' => true)));
	}

	public function getReponse($demande) {
		$reponses = DemandeReponse::getList(array('where' => array('Question' => $this->get('ID'), 'Demande' => $demande->get('ID'))));
		if ($reponses)
			return $reponses[0]->get('Value');
		return null;
	}

	public function deleteReponses() {
		return \DB::noQuery('DELETE FROM `dsk_naturesquestionsreponses` WHERE `Question` = ?', array($this->get('ID')));
	}
}
