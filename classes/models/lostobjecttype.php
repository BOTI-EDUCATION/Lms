<?php

namespace Models;


class LostObjectType extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'sco_lost_objects_types';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Alias' => array(
			'type' => 'varchar',
		),
		'Label' => array(
			'type' => 'varchar',
		),
		'icon' => array(
			'type' => 'varchar',
		),
		'description' => array(
			'type' => 'text',
		),
		'CreatedBy' => array(
			'fk' => 'User',
		),
		'CreatedAt' => array(
			'type' => 'datetime',
		),
	);
	public function getImage()
	{
		if (!$this->get('icon') || !file_exists(_basepath . \Config::get('path-repository-icons') . '/lost_objects/' . $this->get('icon'))) {
			return "https://cdn-images.farfetch-contents.com/18/48/41/55/18484155_39838748_1000.jpg";
		}

		return \URL::absolute(\URL::base() . \Config::get('path-repository-icons') . '/lost_objects/' . $this->get('icon'));
	}


	public function getNomComplet()
	{
		return implode(' ', array($this->get('Nom'), $this->get('Prenom')));
	}
}
