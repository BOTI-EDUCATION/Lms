<?php
namespace Models;

class PostEleve extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'com_posteleves';
	
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Post' => array(
			'fk' => 'Post',
		),
		'Eleve' => array(
			'fk' => 'Eleve',
		),
	);
}
