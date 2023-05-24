<?php
namespace Models\TI;
use \Models\Model;

class TacheCommentaire extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'ti_tachecommentaires';
	
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Tache' => array(
			'fk' => 'TI\\Tache',
		),
		'User' => array(
			'fk' => 'User',
		),
		'Commentaire' => array(
			'type' => 'text',
		),
		'File' => array(
			'type' => 'varchar',
		),
		'Date' => array(
			'type' => 'datetime',
		),
	);

	public function getFileName() {
		$name = \Tools::getAlias($this->get('Tache')->get('Label'));
		$ext = pathinfo($this->get('File'), PATHINFO_EXTENSION);
		return \Tools::getAlias($name) . '.' . $ext;
	}
	public function getFileLink() {
		return 	\GoogleStorage::getUrl(\Config::get('path-docs-taches') . $this->get('File'));
	}
	public function getFile() {
		return 	\GoogleStorage::getUrl(\Config::get('path-docs-taches') . $this->get('File'));
	}
	
}
