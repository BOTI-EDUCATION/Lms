<?php

namespace Models\ETD;

use \Models\Model;

class SeanceTracking extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'sco_seance_tracking';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);

	protected static $fields = array(

		'Alias' => array(
			'type' => 'varchar',
		),
		'Edt' => array(
			'fk' => 'Etd\\Edt',
		),
		'Seance' => array(
			'fk' => 'ETD\\Seance',
			//'type' => 'int',
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
		'Activity' => array(
			'fk' => 'FIN\EncaissementRubrique',
		),
		'Salle' => array(
			'fk' => 'Salle',
		),
		'Classe' => array(
			'fk' => 'Classe',
		),
		'Day' => array(
			'type' => 'int',
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
		'Online' => array(
			'type' => 'boolean',
		),
		'OnlineLink' => array(
			'type' => 'varchar',
		),
		'Remarque' => array(
			'type' => 'varchar',
		),
		'Files' => array(
			'type' => 'varchar',
		),

		'Canceled' => array(
			'type' => 'text',
		),
		'Absences' => array(
			'type' => 'text',
		),
		'CahierTexte' => array(
			'type' => 'text',
		),
		'CahierTexteViews' => array(
			'type' => 'text',
		),
		'Competences' => array(
			'type' => 'text',
		),
		'Lecons' => array(
			'type' => 'text',
		),


	);

	static	function getSeanceTracking($seance, $date, $from, $to)
	{

		$seanceTracking = self::where(array(
			'Seance' => $seance->ID,
			'Classe' => $seance->Classe ? $seance->Classe->ID : null,
			'Date' => $date,
			'From' => $from,
			'To' => $to,
		))->first();

		if ($seanceTracking) {
			return $seanceTracking;
		}

		$seanceTracking = new  SeanceTracking();
		$seanceTracking->set('Alias', $seance->ID)
			->set('Edt', $seance->Edt)
			->set('Seance', $seance->ID)
			->set('Day', $seance->Day)
			->set('Date', $date)
			->set('From', $from)
			->set('To', $to)
			->set('Classe', $seance->Classe)
			->set('Enseignant', $seance->Enseignant)
			->set('Matiere', $seance->Matiere)
			->set('Unite', $seance->Unite)
			->set('Salle', $seance->Salle)
			->set('Online', $seance->Online)
			->set('OnlineLink', $seance->OnlineLink)
			->set('Remarque', $seance->Remarque)
			->set('Files', $seance->Files)
			->save();

		return $seanceTracking;
	}

	public function getSeanceLabel()
	{
		if ($this->get('Activity')) {
			return $this->get('Activity')->get('id');
		} else {
			return $this->get('Classe')->get('id');
		}
	}


	public function getinscriptions()
	{
		if ($this->get('Classe')) {
			return $this->get('Classe')->getInscriptions();
		} else {
			return  $this->get('Group')->getGroupesInscription();
		}
	}
	public function cahierText()
	{
		$seanceJournal = \Models\ETD\SeanceJournal::getList(array(
			'where' => array('Seance' => $this->ID)
		));

		return $seanceJournal ? $seanceJournal[0] : null;
	}

	public function absences()
	{
		$absences = \Models\Absence::getList(array('where' => array(
			$this->get('ID') . ' IN (Cours)',
		)));
		return $absences;
	}

	public function hasAbsences()
	{
		return count($this->absences()) || (!!$this->Absences);
	}

	public function hasCahierText()
	{
		return $this->cahierText() != null;
	}

	public function getLabel($short = false, $for_ens = false)
	{

		$label = $this->get('Unite') ? $this->get('Unite')->get('Label') : '';


		if ($this->get('Matiere')) {
			$label .= ' - ' . $this->get('Matiere')->get('Label');
		}

		if ($short) {
			return $label;
		}

		if ($this->get('Enseignant') &&  !$for_ens)
			$label .= ' - ' .  $this->get('Enseignant')->get('User')->getNomComplet();

		if ($for_ens)
			$label .= ' - ' .  $this->get('Classe')->get('Label');

		$label .= ' - ' . $this->getLabelHeure();

		if ($this->get('Salle'))
			$label .= ' - ' . $this->get('Salle')->get('Label');

		return $label;
	}



	public function getLabelUniteMatiere()
	{

		$label = $this->get('Unite') ? $this->get('Unite')->get('Label') : '';
		if ($this->get('Matiere')) {
			$label .= ' - ' . $this->get('Matiere')->get('Label');
		}

		return $label;
	}


	public function getLabelEleve()
	{

		$label = $this->get('Matiere') ? $this->get('Matiere')->get('Label') : '';

		$label .= $this->get('Remarque') && $this->get('Remarque') != '' ? ' - ' . $this->get('Remarque') : '';

		return $label;
	}

	public function getHeure($field)
	{
		$heure = $this->get($field);
		if (!$heure) return "--";
		$array = explode(':', $heure);
		return $array[0] . "H" . $array[1];
	}

	public function getLabelHeure()
	{
		return $this->getHeure('From') . '-' . $this->getHeure('To');
	}

	public function getPeriodeHeure()
	{
		$heureDebut = $this->get('From');
		$heureFin = $this->get('To');
		$heures = ['08', '09', '10', '11', '12'];
		if (!$heureDebut || !$heureFin) return "--";
		$heureDebut = explode(':', $heureDebut)[0];
		$heureFin = explode(':', $heureFin)[0];

		if (in_array($heureDebut, $heures) && in_array($heureFin, $heures)) {
			return "matin";
		}
		return "apres-midi";
	}

	public function getRemote()
	{
		return $this->Online ? json_decode($this->OnlineLink) : null;
	}

	public function getLink()
	{
		return strpos($this->getRemote()->link, 'http')  === false ? 'https://' . $this->getRemote()->link : $this->getRemote()->link;
	}

	public static function LienTypes()
	{
		$types = array(
			'Boti' =>	array(
				'alias' => 'boti',
				'label' => 'Boti',
				'img' => \URL::absolute(\URL::base('assets/icons/boti.png')),
			),
			'Zoom' =>	array(
				'alias' => 'zoom',
				'label' => 'Zoom',
				'img' => \URL::absolute(\URL::base('assets/icons/zoom.png')),
			),
			'Youtube' =>	array(
				'alias' => 'youtube',
				'label' => 'Youtube',
				'img' => \URL::absolute(\URL::base('assets/icons/youtube.png')),
			),
			'Teams' =>	array(
				'alias' => 'teams',
				'label' => 'Teams',
				'img' => \URL::absolute(\URL::base('assets/icons/teams.png')),
			),
			'Boti' =>	array(
				'alias' => 'meet',
				'label' => 'Google Meet',
				'img' => \URL::absolute(\URL::base('assets/icons/meet.png')),
			),
		);

		return $types;
	}

	public function getMeet($api = false)
	{

		$types = self::LienTypes();
		if (!$this->Online) {
			return null;
		}
		$type = $this->getRemote()->type;
		return  array(
			'text' => $type,
			'link' => $this->getLink(),
			'icon' => isset($types[$type]) ? $types[$type]['img'] : \URL::absolute(\URL::base('assets/mobile/cours_support/boti.png'))
		);
	}

	public function getMinutes()
	{
		$start = strtotime($this->From);
		$end = strtotime($this->To);
		return  $mins = ($end - $start) / 60;
	}
}
