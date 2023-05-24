<?php

namespace Models\FIN;

use \Models\Model;

class DiscountMotif extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'fnc_discount_motifs';

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
		'Public' => array(
			'type' => 'boolean',
		),
		'Sites' => array(
			'type' => 'text',
		),
		'EditHistory' => array(
			'type' => 'text',
		),
	);


	public function getIcone()
	{
		if (!$this->get('Icon')) {
			return 'https://cdn-icons-png.flaticon.com/512/7514/7514355.png';
		}
		return \GoogleStorage::getUrl(\Config::get('path-encaissements') . 'discounts_motif/'  . $this->get('Icon'));
	}


	public function sites()
	{
		return  $this->getArray('Sites') ?: [];
	}
}
