<?php

namespace Models;


class LostObjectReq extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'sco_lost_objects_eleves';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Object' => array(
			'fk' => 'LostObject',
		),
		'Parent' => array(
			'fk' => 'Parentt',
		),
		'Eleve' => array(
			'fk' => 'Eleve',
		),
		'description' => array(
			'type' => 'text',
		),
		'CreatedAt' => array(
			'type' => 'datetime',
		),
	);


}
