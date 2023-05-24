<?php

namespace Models\LMS;


use \Models\Model;

class RessourceContent extends Model
{



	protected static $sqlQueries = [];

	protected static $table = 'lms_ressource_contents';

	protected static $pk = [
		'ID' => [
			'auto' => true,
		],
	];

	protected static $fields = [
		'Ressource' => [
			'fk' => 'LMS\Ressource',
		],
		'Type' => [
			'fk' => 'LMS\RessourceType',
		],
		'Content' => [
			'type' => 'text',
		],
		'Answer' => [
			'type' => 'text',
		],
		'Link' => [
			'type' => 'varchar',
		],
		'File' => [
			'type' => 'varchar',
		],
		'Audio' => [
			'type' => 'varchar',
		],
		'Duree' => [
			'type' => 'int',
		],
		'Ordre' => [
			'type' => 'int',
		],
		'Date' => [
			'type' => 'datetime',
		]
	];


	public function getFile()
	{
		if (!$this->File)
			return null;
		return \URL::base() . \Config::get('path-lms-files') . '/lecons_files/'  . $this->get('File');
	}


	public function getFiles()
	{
		$array = array();
		$folderName = explode('.', $this->File);
		$path_image =  \Config::get('path-lms-files') . '/lecons_files/' . $folderName[0];
		if (!file_exists($path_image)) {
			return $array;
		}

		$dir_handle = opendir(_basepath . $path_image);
		$count = 0;
		while (($file_name = readdir($dir_handle)) !== false) {
			if (!in_array($file_name, ['.', '..'])) {
				$array[] =  \URL::base($path_image . '/' . $file_name);
			}
		}
		return $array;
	}
	public function getAudio()
	{
		if (!$this->Audio)
			return null;
		return \URL::base() . \Config::get('path-lms-files') . '/lecons_files/'  . $this->get('Audio');
	}
}
