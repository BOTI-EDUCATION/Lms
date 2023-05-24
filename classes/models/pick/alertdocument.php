<?php

namespace Models\Pick;

use \Models\Model;

class AlertDocument extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'pick_documents_alerts';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);

	protected static $fields = array(

		'Vehicule' => array(
			'fk' => 'Pick\\Vehicule',
		),
		'Document' => array(
            'type' => 'varchar'
		),
		'OperationDate' => array(
			'type' => 'date',
		),
		'Periode' => array(
			'type' => 'int',
		),
		'Price' => array(
			'type' => 'float',
		),
        'NextOperationDate' => array( 
            'type' => 'date',
        ),
        'User' => array(
            'fk' =>  'User'
        ),
		'Date' => array(
			'type' => 'datetime',
		),

	);



	function find_numbers_of_days_beet($current_day,$target){

		$cur_d = date_create($current_day);
		$tr    = date_create($target);
		$result  = date_diff($cur_d,$tr);
		

		if($target < $current_day){
			return 'Date Expirée';

		}else{
			return $result->format('%a') > 1 ? $result->format('%a').' jours' : $result->format('%a').' jour';
		}

	}
}
