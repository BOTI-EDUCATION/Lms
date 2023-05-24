<?php
namespace Models;

class PostQuestionnaire extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'com_postquestionnaires';
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
		'Reponse' => array(
			'type' => 'boolean',
		),
		'Date' => array(
			'type' => 'datetime',
		),
	);

	public static function getReponses($user, $post){
		$reponses = PostQuestionnaire::getList(array('where' => array('User' => $user->getPK('true'), 'Post' => $post->getPK('true')), 'order' => array('Date' => false)));

		return $reponses;
	}

	
}
