<?php

namespace Models;

class PostService extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'com_postservices';

	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Post' => array(
			'fk' => 'Post',
		),
		'Service' => array(
			'fk' => 'FIN\\EncaissementRubrique',
		),
	);
}
