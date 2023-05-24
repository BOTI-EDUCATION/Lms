<?php
namespace Models\DSK;
use \Models\Model;

class Nature extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'dsk_natures';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Label' => array(
			'type' => 'varchar',
		),
		'Ordre' => array(
			'type' => 'int',
		),
		'Interne' => array(
			'type' => 'boolean',
		),
		'DureeTraitement' => array(
			'type' => 'int',
		),
	);

	public function getQuestions() {
		return NatureQuestion::getList(array('where' => array('Nature'=> $this->get('ID')), 'order' => array('Ordre' => true)));
	}
}
