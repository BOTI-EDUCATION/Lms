<?php

namespace Models\TRANS;

use Models\Promotion;

class TransmissionTracking extends \Models\Model
{

	protected static $sqlQueries = array();

	protected static $table = 'trans_tracking';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Promotion' => array(
			'fk' => 'Promotion',
			'type' => 'int',
		),
		'Inscription' => array(
			'fk' => 'Inscription',
			'type' => 'int',
		),
		'Indicateur' => array(
			'fk' => 'TRANS\\TransmissionIndicateur',
			'type' => 'int',
		),
		'Tracking' => array(
			'type' => 'text',
		),
		'DateAction' => array(
			'type' => 'date',
		),
		'Comment' => array(
			'type' => 'text',
		),
		'Values' => array(
			'type' => 'text',
		),
		'Validation' => array(
			'type' => 'text',
		),
		'Deleted' => array(
			'type' => 'text',
		),
		'Edit_history' => array(
			'type' => 'text',
		),
	);

	public static function getTracking( $indicateur , $inscription , $promotion = null ){
		
		if(!$promotion){
			$promotion = \Models\Promotion::actuelle();
		}

		$tracking = \Models\TRANS\TransmissionTracking::getList(
			array(
				'where' => array(
					'Indicateur' => $indicateur->get('ID'),
					'Inscription' => $inscription->get('ID'),
					'Promotion' => $promotion->get('ID')
				),
				'limit' => 1
			)
		);

		return ($tracking?$tracking[0]:null);
	}

	public static function getTrackings($date= null , $inscription = null , $indicateur = null , $promotion = null , $rubrique = null ){
		
		$where = array();

		$query = null;

		if(!$promotion){
			$where['Promotion'] = \Models\Promotion::actuelle()->get('ID');
		}else{
			$where['Promotion'] = $promotion->get('ID');
		}

		if($date){
			$where[] = '(`DateAction` LIKE \''.$date.'%\')';
		}

		if($inscription){
			$where['Inscription'] = $inscription->get('ID');
		}

		if($indicateur){
			$where['Indicateur'] = $indicateur->get('ID');
		}

		if($rubrique){
			$query = \Models\TRANS\TransmissionTracking::sqlQuery(true).' JOIN (SELECT `ID` AS `J1ID` , `Rubrique` AS `J1Rubrique` FROM `trans_indicateurs`) AS `J1` ON `J1`.`J1ID` = `trans_tracking`.`Indicateur`';
			$where['J1Rubrique'] = $rubrique->get('ID');
		}

		

		$tracking = \Models\TRANS\TransmissionTracking::getList(
			array(
				'where' => $where,
				'order'=> array(
					'DateAction' => false
				)
			),$query
		);

		return $tracking;
	}


	public static function getValue($inscription,$indicateur,$frequence,$date){

		$promotion = \Models\Promotion::actuelle();

		$tracking = \Models\TRANS\TransmissionTracking::getList(
			array(
				'where' => array(
					'Indicateur' => $indicateur->get('ID'),
					'Inscription' => $inscription->get('ID'),
					'DateAction' => $date,
					'Promotion' => $promotion->get('ID')
				),
				'limit' => 1
			)
		);

		if(!$tracking)
		return null;

		$values = json_decode($tracking[0]->get('Values'),true);

		// {"avant-repas":"t1","apres-repas":"t2","avant-activite":"t3","apres-activite":"t4"}
		if(isset($values[$frequence])){
			return $values[$frequence];
		}else{
			return null;
		}

		
	}

	public function getValues(){

		if( !$this->Values || !is_array(json_decode($this->Values,true) ))
		return null;

		$values = [];
		foreach (json_decode($this->Values,true) as $idField => $value) {
			try {
				$field = new \Models\TRANS\TransmissionIndicateurFields($idField);
				$values[] = [
					'obj' => $field,
					'id' => $idField,
					'label' => $field->Label,
					'value' => $value	
				];
			} catch (\Exception $th) {
				continue;
			}
		}

		return $values;
	}
	
}
