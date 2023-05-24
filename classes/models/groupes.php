<?php

namespace Models;

use Exception;

class Groupes extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'ins_groups';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Site' => array(
			'fk' => 'Site',
		),
		'Promotion' => array(
			'fk' => 'Promotion',
		),
		'Niveau' => array(
			'fk' => 'Niveau',
		),
		'Label' => array(
			'type' => 'varchar',
		),
		'Icone' => array(
			'type' => 'varchar',
		),
		'Responsable' => array(
			'fk' => 'Eleve',
		),
		'ResponsableHistory' => array(
			'type' => 'varchar',
		),
		'EditHistory' => array(
			'type' => 'text',
		),
	);


	public function getIcone()
	{

		$url =  $this->get('Icone') ? \GoogleStorage::getUrl(\Config::get('path-images-classes')) :  null;

		if (!$url) {
			return \URL::base() . 'assets/icons/classe.png';
		}

		return $url;
	}
	function addEtd($data)
	{
		$data['Group'] = $this->get('ID');
		return $this->add(ETD\Edt::class, $data);
	}
	public function  getGroupesInscription()
	{
		$groupes_inscriptions = GroupesInscription::where(['Groupe' => $this->get('ID')])->get();
		$inscriptions = [];
		foreach ($groupes_inscriptions as $ins) {
			array_push($inscriptions, $ins->get('Inscription'));
		}
		return $inscriptions;
	}
}
