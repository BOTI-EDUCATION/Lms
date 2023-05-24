<?php

namespace Models;

class PostCycle extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'com_postcycles';

	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Post' => array(
			'fk' => 'Post',
		),
		'Cycle' => array(
			'fk' => 'Cycle',
		),
		'Site' => array(
			'fk' => 'Site',
		),
	);
}
