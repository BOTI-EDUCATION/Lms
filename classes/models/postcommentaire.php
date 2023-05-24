<?php
namespace Models;

class PostCommentaire extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'com_postcommentaires';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Post' => array(
			'fk' => 'Post',
		),	
		'Parent' => array(
			'fk' => 'Parentt',
		),
		'Inscription' => array(
			'fk' => 'Inscription',
		),
		'User' => array(
			'fk' => 'User',
		),
		'Commentaire' => array(
			'type' => 'text',
		),
		'Date' => array(
			'type' => 'datetime',
		),
	);
	
}
