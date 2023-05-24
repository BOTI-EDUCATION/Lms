<?php

namespace Models;

class PostNiveau extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'com_postniveaux';

	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Post' => array(
			'fk' => 'Post',
		),
		'Niveau' => array(
			'fk' => 'Niveau',
		),
		'Site' => array(
			'fk' => 'Site',
		),
	);
}
