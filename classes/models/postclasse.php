<?php
namespace Models;

class PostClasse extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'com_postclasses';
	
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Post' => array(
			'fk' => 'Post',
		),
		'Classe' => array(
			'fk' => 'Classe',
		),
	);
	
}
