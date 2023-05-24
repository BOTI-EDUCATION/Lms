<?php

namespace Models\ETD;

use \Models\Model;

class SeanceJournal extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'sco_seance_journal';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);

	protected static $fields = array(
		'Seance' => array(
			'fk' => 'ETD\\SeanceTracking',
		),
		'Enseignant' => array(
			'fk' => 'Enseignant',
		),

		'Matiere' => array(
			'fk' => 'Matiere',
		),
		'Unite' => array(
			'fk' => 'Unite',
		),

		'Salle' => array(
			'fk' => 'Salle',
		),
		'Promotion' => array(
			'fk' => 'Promotion',
		),
		'Classe' => array(
			'fk' => 'Classe',
		),

		'Date' => array(
			'type' => 'date',
		),
		'From' => array(
			'type' => 'time',
		),
		'To' => array(
			'type' => 'time',
		),

		'JournalValue' => array(
			'type' => 'text',
		),
		'JournalFiles' => array(
			'type' => 'text',
		),
		'Validation' => array(
			'type' => 'text',
		),
		'Views' => array(
			'type' => 'text',
		),
		'EditHistory' => array(
			'type' => 'text',
		),
	);


	static	function getJournal($cours)
	{

		$items = self::getList(array('where' => array(
			'Seance' => $cours->ID,
			'Classe' => $cours->Classe->ID,
			'From' =>   $cours->From,
			'To' =>     $cours->To,
			'Date' =>    $cours->Date
		)));

		if (count($items)) {
			return $items[0];
		}

		return  new self();
	}

	public function getJournalFiles($abs = null)
	{
		if (!$this->JournalFiles) {
			return null;
		}
		return \GoogleStorage::getUrl(\Config::get('path-journalseance-files') . $this->JournalFiles);
		
	}
}
