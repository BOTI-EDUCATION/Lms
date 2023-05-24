<?php
namespace Models;

class PostDevoir extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'com_postdevoirs';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Post' => array(
			'fk' => 'Post',
		),	
		'Parent' => array(
			'fk' => 'Parentt',
		),
		'Eleve' => array(
			'fk' => 'Eleve',
		),
		'Inscription' => array(
			'fk' => 'Inscription',
		),
		'Fait' => array(
			'type' => 'boolean',
		),
		'Rendu' => array(
			'type' => 'text',
		),
		'Files' => array(
			'type' => 'text',
		),
		'Date' => array(
			'type' => 'datetime',
		),
		'Views' => array(
			'type' => 'text',
		),
	);
	
	// Retourn les fichiers (liens absolus) du message en cours
	public function getFichiers($api = false) {
		$fichiers = array();
		if(!$this->get('Files'))
			return $fichiers;
		
		$files = explode(',',$this->get('Files'));
		$pathfiles = \Config::get('path-uploads');
		
		$filesApi = array();
		foreach($files as $item) {
			$filesApi[] = array(
				'link' =>  \GoogleStorage::getUrl($pathfiles. $item),
				'name' => $item
			);
		}
		
		return $filesApi;
	}

	public function getFileLink()
	{
		return \GoogleStorage::getUrl(\Config::get('path-docs-posts') . $this->get('Files'));
	}
	
	public function getFile()
	{
		return \GoogleStorage::getUrl(\Config::get('path-docs-posts') . $this->get('Files'));
	}
	
}
