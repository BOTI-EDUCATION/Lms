<?php

namespace Models;


class Pointage extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'pointages';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'User' => array(
			// 'fk' => 'RH\\Collaborateur',
			'fk' => 'User',
		),
		'DateEnter' => array(
			'type' => 'date',
		),
		'TimeEnter' => array(
			'type' => 'time',
		),
		'TypeEnter' => array(
			'type' => 'int',
		),
		'CreatedAt' => array(
			'type' => 'datetime',
		),
	);

}
