<?php

namespace Models;

class Partenaire extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'partenaires';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Label' => array(
			'type' => 'varchar',
		),
		'LabelAr' => array(
			'type' => 'varchar',
		),
		'RIB' => array(
			'type' => 'text',
		),
		'Adresse' => array(
			'type' => 'varchar',
		),
		'Ville' => array(
			'type' => 'varchar',
		),
		'Directrice' => array(
			'type' => 'boolean',
		),
		'Tel' => array(
			'type' => 'varchar',
		),
		'Fixe' => array(
			'type' => 'varchar',
		),
		'Email' => array(
			'type' => 'varchar',
		),
		'ICE' => array(
			'type' => 'varchar',
		),
		'TP' => array(
			'type' => 'varchar',
		),
		'IF' => array(
			'type' => 'varchar',
		),
		'AutorisationNum' => array(
			'type' => 'varchar',
		),
		'AutorisationDate' => array(
			'type' => 'date',
		),
		'Logo' => array(
			'type' => 'varchar',
		),
	);



	public function getImage()
	{
		if (!$this->get('Logo') || !file_exists(_basepath . \Config::get('path-images-partenaires') . $this->get('Logo'))) {

			return (\Config::get('logo') ? \URL::base('assets/images/schools/' . \Config::get('logo')) : \URL::base('assets/images/logo.103983.png'));
		}

		return \URL::base() . \Config::get('path-images-partenaires') . $this->get('Logo');
	}


	public function _getImage()
	{

		$url =  $this->get('Logo') ? \GoogleStorage::getUrl(\Config::get('path-images-partenaires') . $this->get('Logo')) : null;

		if (!$url) {

			return (\Config::get('logo') ? \URL::base('assets/images/schools/' . \Config::get('logo')) : \URL::base('assets/images/logo.103983.png'));
		}

		return $url;
	}

	protected static $list = null;
	public static function getListCached()
	{
		if (static::$list === null) {
			static::$list = static::getList();
		}
		return static::$list;
	}


	public function countSites()
	{

		return \Models\Site::where(['Etablissement' => $this->getKey()])->count();
	}
}
