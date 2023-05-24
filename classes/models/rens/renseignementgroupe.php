<?php
namespace Models\RENS;


class RenseignementGroupe extends \Models\Model {

	protected static $sqlQueries = array();

	protected static $table = 'crm_renseignement_groupes';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Label' => array(
			'type' => 'varchar',
		),
        'Cycle' => array(
            'fk' => 'Cycle',
        ),
		'Alias' => array(
			'type' => 'varchar',
        ),
	);


	public static function getByCycle($cycle) {
		$groupes = \Models\RENS\RenseignementGroupe::getList(['where'=>[
            'Cycle' => $cycle->ID
        ]]);
		return $groupes;
	}

    public function getAllQuestions(){
        $questions = \Models\RENS\RenseignementQuestion::getList([
            'where' => [
                'Groupe' => $this->ID,
            ]
        ]);

        return $questions;
    }

    public function getQuestions(){
        $questions = \Models\RENS\RenseignementQuestion::getList([
            'where' => [
                'Groupe' => $this->ID,
                'Parent IS NULL',
            ]
        ]);

        return $questions;
    }
}
