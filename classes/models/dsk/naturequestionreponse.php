<?php
namespace Models\DSK;
use \Models\Model;

class NatureQuestionReponse extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'dsk_naturesquestionsreponses';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Question' => array(
			'fk' => 'DSK\\NatureQuestion',
			'type' => 'int',
		),
		'Label' => array(
			'type' => 'varchar',
		),
		'Ordre' => array(
			'type' => 'int',
		),
	);
}
