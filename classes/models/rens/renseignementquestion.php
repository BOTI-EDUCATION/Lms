<?php
namespace Models\RENS;


class RenseignementQuestion extends \Models\Model {

	protected static $sqlQueries = array();

	protected static $table = 'crm_renseignement_questions';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Groupe' => array(
			'fk' => 'RENS\\RenseignementGroupe',
		),
		'Parent' => array(
			'fk' => 'RENS\\RenseignementQuestion',
		),
		'Label' => array(
			'type' => 'varchar',
		),
		'Alias' => array(
			'type' => 'varchar',
        ),
		'Type' => array(
			'type' => 'varchar',
        ),
		'Options' => array(
			'type' => 'varchar',
        ),
		'Value' => array(
			'type' => 'varchar',
		)
	);

    public function getChild(){
        $children = \Models\RENS\RenseignementQuestion::getList([
            'where' => [
                'Parent' => $this->ID
            ]
        ]);

        return ($children?$children[0]:null);
    }

    public function getChildren(){
        
    }
}
