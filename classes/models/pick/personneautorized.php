<?php

namespace Models\Pick;

use \Models\Model;

class PersonneAutorized extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'pick_autorized_to_pickup';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);

	protected static $fields = array(

		'CIN' => array(
			'type' => 'varchar',
		),
		'GSM' => array(
			'type' => 'varchar',
		),
		'Nom' => array(
			'type' => 'varchar',
		),
		'Prenom' => array(
			'type' => 'varchar',
		),
		'Photo' => array(
			'type' => 'varchar',
		),
		'Creation' => array(
			'type' => 'varchar',
		),
		'SMSCodeValidation' => array(
			'type' => 'varchar',
		),
		'ValidationParent' => array(
			'type' => 'varchar',
		),
		'ValidationAdmin' => array(
			'type' => 'varchar',
		),
		'Eleves' => array(
			'type' => 'text',
		),
		'Parents' => array(
			'type' => 'text',
		),
	);

	public function getImage()
	{
		$url  = $this->get('Photo') ?  \GoogleStorage::getUrl(\Config::get('path-images-users') . $this->get('Photo')) : null;
		
		if (!$url) {
			$image = 'no-user-man.svg';
			return \URL::base() . 'assets/icons/' . $image;
		}

		return $url;
	}

	public function getNomComplet()
	{
		return implode(' ', array($this->get('Nom'), $this->get('Prenom')));
	}
}
