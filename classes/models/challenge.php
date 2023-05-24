<?php

namespace Models;

class Challenge extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'discpline_challenges';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Label' => array(
			'type' => 'varchar',
		),
		'Intro' => array(
			'type' => 'text',
		),
		'Value' => array(
			'type' => 'int',
		),
		'Flag' => array(
			'type' => 'boolean',
		),
		'Image' => array(
			'type' => 'varchar',
		),
		'Notification' => array(
			'type' => 'text'
		)
	);


	public function getImage()
	{
		if (!$this->get('Image'))
			return null;
		return \GoogleStorage::getUrl(\Config::get('path-images-challenges') . $this->get('Image'));
	}
}
