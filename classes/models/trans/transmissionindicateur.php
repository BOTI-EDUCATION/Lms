<?php

namespace Models\TRANS;

class TransmissionIndicateur extends \Models\Model
{

	protected static $sqlQueries = array();

	protected static $table = 'trans_indicateurs';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Rubrique' => array(
			'fk' => 'TRANS\\TransmissionRubrique',
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
		'Icon' => array(
			'type' => 'varchar',
		),
		'Values' => array(
			'type' => 'text',
		),
		'Frequency' => array(
			'type' => 'text',
		),
		'Remarques' => array(
			'type' => 'text',
		),
		'Deleted' => array(
			'type' => 'varchar',
		),
	);


	public static function getList($args = null, $query = null)
	{
		if (!is_array($args))
			$args = array();

		$args['where'][] = '(`Deleted` IS NULL)';

		return parent::getList($args, $query);
	}
	
	public function getIcon(){
		if(!$this->get('Icon'))
		return null;

		return \GoogleStorage::getUrl(\Config::get('path-images-indicateurs') . $this->get('Icon'));
	}

	// public function getFrequence(){
	// 	if(!$this->get('Frequency'))
	// 	return [];

	// 	return json_decode($this->get('Frequency'),true);
	// }

	// public function getCountFrequence(){
	// 	if(!$this->get('Frequency'))
	// 	return [];

	// 	return count(json_decode($this->get('Frequency'),true));
	// }

	// public function getOptions(){
	// 	$values = json_decode($this->get('Values'),true);
	// 	$options = [];

	// 	foreach ($values['values'] as $value) {
	// 		$options[] = [
	// 			'val' => $value,
	// 			'default' => ($values['default']==$value?true:false)
	// 		];
	// 	}

	// 	return $options;

	// }

	// public function getDefaultValue(){
	// 	$values = json_decode($this->Values,true);

	// 	switch ($this->get('Type')) {
	// 		case 'texte':
	// 			return $values['values']?$values['values'][0]:null;
	// 			break;
	// 		case 'select':
	// 			return $values['default'];
	// 			break;
	// 		case 'check': 
	// 			return $values['values']?$values['values'][0]:null;
	// 			break;
	// 		case 'radio':
	// 			return $values['values']?$values['values'][0]:null;
	// 			break;
	// 		case 'time':
	// 			return $values['values']?$values['values'][0]:null;
	// 			break;
	// 		case 'number':
	// 			return $values['values']?$values['values'][0]:null;
	// 			break;
	// 		case 'boolean':
	// 			return $values['values']?$values['values'][0]:null;
	// 			break;
	// 		default: 
	// 			return null;
	// 			break;
	// 	}

	// }

	public function getFields($all=false){

		$where = [] ;
		
		$where['Indicateur'] = $this->ID;
		
		if(!$all)
		$where['Enabled'] = 1;

		$fields = \Models\TRANS\TransmissionIndicateurFields::getList([
			'where' => $where
		]);
		
		return $fields;
	}

}
