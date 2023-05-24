<?php

namespace Models;

use Exception;

class 	GroupesInscription extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'ins_group_inscriptions';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Groupe' => array(
			'fk' => 'Groupes',
		),
		'Inscription' => array(
			'fk' => 'Inscription',
		),
		'Eleve' => array(
			'fk' => 'Eleve',
		),
		'Months' => array(
			'type' => 'text',
		),
	);


	public function getIcone()
	{

		$url =  $this->get('Icone') ? \GoogleStorage::getUrl(\Config::get('path-images-classes')) :  null;

		if (!$url) {
			return \URL::base() . 'assets/icons/classe.png';
		}

		return $url;
	}
}
