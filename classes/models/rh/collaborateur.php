<?php

namespace Models\RH;

use \Models\Model;
use Session;

class Collaborateur extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'rh_collaborateur';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Superieur' => array(
			'fk' => 'RH\Collaborateur',
		),
		'User' => array(
			'fk' => 'User',
		),
		'Superieurs' => array(
			'type' => 'text',
		),
		'Sites' => array(
			'type' => 'text',
		),
		'Chauffeur' => array(
			'type' => 'boolean',
		),
		'AideChauffeur' => array(
			'type' => 'boolean',
		),
		'NumeroPermis' => array(
			'type' => 'varchar',
		),
		'DateValiditePermis' => array(
			'type' => 'date',
		),
		'Fonction' => array(
			'type' => 'varchar',
		),
		'Horaire' => array(
			'type' => 'text',
		),
		'CodePointage' => array(
			'type' => 'varchar',
		),
		'DateEmbauche' => array(
			'type' => 'date',
		),
		'SalaireBase' => array(
			'type' => 'double',
		),
		'NumeroCnss' => array(
			'type' => 'text',
		),
		'MatriculeInterne' => array(
			'type' => 'varchar',
		),
		'Bank' => array(
			'type' => 'varchar',
		),
		'NemuroCompteBank' => array(
			'type' => 'varchar',
		),
		'DateDemission' => array(
			'type' => 'varchar',
		),

		'Files' => array(
			'type' => 'text',
		),
		'Salaires' => array(
			'type' => 'text',
		),
	);


	public static function getList($args = null, $query = null)
	{

		$authUser = Session::user();
		try {
			//code...
			$authUsercollab = $authUser->getCollaborateur();
		} catch (\Throwable $th) {
			$authUsercollab = null;
		}

		if (!is_array($args))
			$args = array();

		$args['where'][] = '(`User` NOT IN (SELECT `ID` FROM `users` WHERE `DeletedAt` IS NOT NULL))';

		// if (!roleIs('admin', 'assistant_rh', 'resp_pedago')) {
		if (!roleIs('admin', 'assistant_rh')) {

			if ($authUsercollab) {
				$args['where'][] =  '((`Superieurs` LIKE \'%"' . $authUsercollab ? $authUsercollab->getKey() : '' . '"%\'))';
			}
		}

		return parent::getList($args, $query);
	}


	function files()
	{
		return  $this->Files ? json_decode($this->Files, true) : array();
	}

	function getFiles($full_link = false)
	{
		return array_map(function ($item) use ($full_link) {

			$item['file'] =  \GoogleStorage::getUrl(\Config::get('path-images-rh-files') . $item['file']);
			return $item;
		}, $this->files());
	}

	function addFile($label, $file, $index = null)
	{
		$files = $this->files();
		if (is_null($index)) {
			$files[] = array(
				'label' => $label,
				'file' => $file
			);
		} else {
			$files[$index] = array(
				'label' => $label,
				'file' => $file
			);
		}
		$this->Files = json_encode($files);
	}


	function deleteFile($index)
	{
		$files = $this->files();
		unset($files[$index]);
		$this->Files = json_encode($files);
	}

	public function getImage($full_link = false)
	{

		$icon = $this->get('Image');
		if (!$icon) {
			$icon = 'default1.png';
		}
		return;
	}
}
