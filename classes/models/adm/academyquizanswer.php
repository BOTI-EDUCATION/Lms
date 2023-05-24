<?php
namespace Models\ADM;

class AcademyQuizAnswer extends \Models\Model {

	protected static $sqlQueries = array();

	protected static $table = 'adm_quiz_answers';
    
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);

	protected static $fields = array(
		'User' => array(
			'fk' => 'User',
		),
		'Question' => array(
			'type' => 'varchar',
		),
		'Answer' => array(
			'type' => 'text',
		),
		'Correct' => array(
			'type' => 'Boolean',
        ),
		'Submission' => array(
			'type' => 'text',
		),
		'Date' => array(
			'type' => 'date',
		)
	);
	
	
}
