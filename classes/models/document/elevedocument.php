<?php

namespace Models\Document;

use Models\Model;

class EleveDocument extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'gen_eleve_documents';

	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Document' => array(
			'fk' => 'Document\\TypeDocument',
		),
		'Promotion' => array(
			'fk' => 'Promotion',
		),
		'Eleve' => array(
			'fk' => 'Eleve',
		),
		'Inscription' => array(
			'fk' => 'Inscription',
		),
		'Files' => array(
			'type' => 'varchar',
		),
		'ByWhom' => array(
			'fk' => 'User',
		),
		'Number' => array(
			'type' => 'int',
		),
		'Commentaire' => array(
			'type' => 'text',
		),
		'Date' => array(
			'type' => 'date',
		),
		'EditHistory' => array(
			'type' => 'text',
		)
	);



	public function getFileLink()
	{
		return 	\GoogleStorage::getUrl(\Config::get('path-documents-eleve') . $this->get('Files'));
	}

	public function beforeSave()
	{
		$history = $this->getArray('EditHistory') ?: array();
		$history[] = array(
			'user' => \Session::getInstance()->getCurUser()->ID,
			'action' => $this->saved ? 'update' : 'add',
			'date' => date('Y-m-d H:i:s'),
		);
		$this->setJson('EditHistory', $history);
	}
}
