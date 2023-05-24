<?php

namespace Models;

class Notif extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'notifs';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Users' => array(
			'type' => 'text'
		),
		'Responsable' => array(
			'fk' => 'User'
		),
		'Classe' => array(
			'fk' => 'Classe',
		),
		'Inscription' => array(
			'fk' => 'Inscription',
		),
		'Label' => array(
			'type' => 'varchar',
		),
		'Message' => array(
			'type' => 'text',
		),
		'Date' => array(
			'type' => 'datetime',
		),
		'TypeRessource' => array(
			'type' => 'varchar',
		),
		'IDRessource' => array(
			'type' => 'int',
		),
	);

	function beforeSave()
	{
		$this->set('Responsable', \Session::user());
	}
}
