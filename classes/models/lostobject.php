<?php

namespace Models;


class LostObject extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'sco_lost_objects';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'type' => array(
			'fk' => 'LostObjectType',
		),
		'image' => array(
			'type' => 'varchar',
		),
		'description' => array(
			'type' => 'text',
		),
		'picked' => array(
			'type' => 'datetime',
		),
		'pickedComment' => array(
			'type' => 'text',
		),
		'pickedEleve' => array(
			'fk' => 'Eleve',
		),
		'userActionPick' => array(
			'fk' => 'User',
		),
		'deleted' => array(
			'type' => 'json',
		),
		'StateUpdatedAt' => array(
			'type' => 'datetime',
		),
		'CreatedBy' => array(
			'fk' => 'User',
		),
		'CreatedAt' => array(
			'type' => 'datetime',
		),
		'Found' => array(
			'type' => 'datetime',
		),
		'Notification' => array(
			'type' => 'json',
		),
	);

	public function getImage()
	{
		$url = $this->get('image') ? \GoogleStorage::getUrl(\Config::get('path-lost-objects-files') . $this->get('image')) : null;

		if (!$url) {
			return $this->type->getImage();
		}

		return \GoogleStorage::getUrl(\Config::get('path-lost-objects-files') . $this->get('image'));
	}
}
