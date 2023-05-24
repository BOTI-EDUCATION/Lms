<?php
namespace Models\TI;
use \Models\Model;

class Tache extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'ti_taches';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Type' => array(
			'type' => 'int',
			'fk' => 'TI\\Type',
		),
		'User' => array(
			'type' => 'int',
			'fk' => 'User',
		),
		'Etat' => array(
			'fk' => 'TI\\Etat',
		),
		'Label' => array(
			'type' => 'varchar',
			'required' => true,
		),
		'Content' => array(
			'type' => 'text',
		),
		'File' => array(
			'type' => 'file',
		),
		'DateDebut' => array(
			'type' => 'datetime',
		),
		'DateFin' => array(
			'type' => 'datetime',
		),
		'TouteJournee' => array(
			'type' => 'tinyint',
		),
		'ModifieEtat' => array(
			'type' => 'tinyint',
		),
		'AddCollaborateur' => array(
			'type' => 'tinyint',
		),
		'Private' => array(
			'type' => 'tinyint',
		),
		'Date' => array(
			'type' => 'datetime',
		),
	);

	public function getFileName() {
		$name = \Tools::getAlias($this->get('Label'));
		$ext = pathinfo($this->get('File'), PATHINFO_EXTENSION);
		return \Tools::getAlias($name) . '.' . $ext;
	}
	public function getFileLink() {
		return 	\GoogleStorage::getUrl(\Config::get('path-docs-taches') . $this->get('File'));
	}
	public function getFile() {
		return 	\GoogleStorage::getUrl(\Config::get('path-docs-taches') . $this->get('File'));
	}

	public function getDocument() {
		if($this->get('Content'))
			return 	\GoogleStorage::getUrl(\Config::get('path-docs-taches') . $this->get('Content'));
	}

	public static function userTaches() {
		
		$user = \Session::getInstance()->getCurUser();
		$query = Tache::sqlQuery(true). <<<END
	JOIN (SELECT `ID` AS `J1ID`, `Tache` AS `J1Tache`, `User` AS `J1User` FROM `ti_tachecollaborateurs`) AS `j1` ON `ti_taches`.`ID` = `j1`.`J1Tache` 
END;

		$items = Tache::getList(array('where' => array('J1User = '.$user->get('ID').' OR User = '.$user->get('ID'))),$query);
		
		return $items;
	}

	public function getCollaborateurs() {
		$tacheCollaborateurs = array();
		$items = TacheCollaborateur::getList(array('where' => array('Tache' => $this->get('ID',null,false))));
		if (!$items)
			return array();
		foreach ($items as $item)
			$tacheCollaborateurs[$item->get('User')->get('ID')] = $item;
		return $tacheCollaborateurs;
	}

	public function deleteCollaborateurs() {
		$tacheCollaborateurs = $this->getCollaborateurs();
		foreach ($tacheCollaborateurs as $item)
			$item->delete();
	}

	public function getClasses() {
		$tacheClasses = array();
		$items = TacheClasse::getList(array('where' => array('Tache' => $this->get('ID',null,false))));
		if (!$items)
			return array();
		foreach ($items as $item)
			$tacheClasses[$item->get('Classe')->get('ID')] = $item;
		return $tacheClasses;
	}

	public function deleteClasses() {
		$tacheClasses = $this->getClasses();
		foreach ($tacheClasses as $item)
			$item->delete();
	}

	public function getCommentaires() {
		$tacheCommentaires = array();
		$items = TacheCommentaire::getList(array('where' => array('Tache' => $this->get('ID',null,false))));
		if (!$items)
			return array();
		foreach ($items as $item)
			$tacheCommentaires[$item->get('ID')] = $item;
		return $tacheCommentaires;
	}

	public function deleteCommentaires() {
		$tacheCommentaires = $this->getCommentaires();
		foreach ($tacheCommentaires as $item)
			$item->delete();
	}

	public function getEtats() {
		$tacheEtats = array();
		$items = TacheEtat::getList(array('where' => array('Tache' => $this->get('ID',null,false))));
		if (!$items)
			return array();
		foreach ($items as $item)
			$tacheEtats[$item->get('ID')] = $item;
		return $tacheEtats;
	}

	public function deleteEtats() {
		$tacheEtats = $this->getEtats();
		foreach ($tacheEtats as $item)
			$item->delete();
	}
	
}
