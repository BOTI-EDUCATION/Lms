<?php

namespace Models\TRANS;

class TransmissionIndicateurFields extends \Models\Model
{

	protected static $sqlQueries = array();

	protected static $table = 'trans_indicateurs_fields';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Indicateur' => array(
			'fk' => 'TRANS\\TransmissionIndicateur',
			'type' => 'int',
		),
		'Label' => array(
			'type' => 'varchar',
		),
		'Alias' => array(
			'type' => 'varchar',
		),
		'Type' => array(
			'type' => 'varchar',
		),
		'Options' => array(
			'type' => 'text',
		),
		'Default' => array(
			'type' => 'text',
		),
		'Enabled' => array(
			'type' => 'boolean',
		),
	);

	public function getOptions(){
		return json_decode($this->get('Options'),true);
	}

	public function getDefaultValue(){
		$values = json_decode($this->Options,true);
		return ($this->Default && isset($values[$this->Default]))?$values[$this->Default]:null;
	}

}
