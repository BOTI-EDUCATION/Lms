<?php

namespace Models\TRANS;

class TransmissionRubrique extends \Models\Model
{

	protected static $sqlQueries = array();

	protected static $table = 'trans_rubriques';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Label' => array(
			'type' => 'varchar',
		),
		'Alias' => array(
			'type' => 'varchar',
		),
		'Icon' => array(
			'type' => 'varchar',
		),
		'Promotions' => array(
			'type' => 'text',
		),
		'Enabled' => array(
			'type' => 'text',
		),
		'Edit_history' => array(
			'type' => 'text',
		),
	);

	public function getPromotions($asc = true){
		if(!$this->get('Promotions'))
		return array();

		$promotions = array();
		foreach (json_decode($this->get('Promotions')) as $promoId) {
			try {
				$promotion = new \Models\Promotion($promoId);
			} catch (\Exception $th) {
				continue;
			}

			$promotions[] = $promotion;
		}

		if(!$asc){
			array_reverse($promotions);
		}

		return $promotions;
	}
	
	public function getIcon(){
		if(!$this->get('Icon'))
		return null;

		return \GoogleStorage::getUrl(\Config::get('path-images-rubriques') . $this->get('Icon'));
	}

	public function getIndicateurs(){
		$indicateurs = \Models\TRANS\TransmissionIndicateur::getList(array(
			'where' => array(
				'Rubrique' => $this->get('ID')
			)
		));

		return $indicateurs;
	}

	public function getCountIndicateurs(){
		$indicateurs = \Models\TRANS\TransmissionIndicateur::getCount(array(
			'where' => array(
				'Rubrique' => $this->get('ID')
			)
		));

		return $indicateurs;
	}


	public function getFrequence(){
		$frequences = [];

		foreach ($this->getIndicateurs() as $key => $indicateur) {
			if(!$indicateur->get('Frequency'))
			continue;

			foreach ($indicateur->getFrequence() as $key => $frequence) {
				$frequences[] = $frequence;
			}
		}

		return $frequences;
	}


	public function getCountFrequence(){
		$frequences = [];

		foreach ($this->getIndicateurs() as $key => $indicateur) {
			if(!$indicateur->get('Frequency'))
			continue;

			foreach ($indicateur->getFrequence() as $key => $frequence) {
				$frequences[] = $frequence;
			}
		}

		return count($frequences);
	}

}
