<?php

namespace Models\ETD;

use Models\Absence as ModelsAbsence;
use \Models\Model;
use Models\RH\Absence;
use Mpdf\Shaper\Sea;

class Seance extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'sco_seance';
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
		'Promotion' => array(
			'fk' => 'Promotion',
		),
		'Classe' => array(
			'fk' => 'Classe',
		),
		'Group' => array(
			'fk' => 'Groupes',
		),

		'Day' => array(
			'type' => 'int',
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
		'Genereation' => array(
			'type' => 'varchar',
		),
		'EditHistory' => array(
			'type' => 'text',
		),


	);

	public function getSeanceLabel()
	{
		if ($this->get('Activity')) {
			return $this->get('Activity')->get('Label');
		} else {
			return $this->get('Unite')->get('Label');
		}
	}
	public function getInscriptions()
	{
		if ($this->get('Classe')) {
			return $this->get('Classe')->getInscriptions();
		} else {
			return  $this->get('Group')->getGroupesInscription();
		}
	}
	public static function getList($args = null, $query = null)
	{
		if (!is_array($args))
			$args = array();

		$args['where'][] = '`Edt` IN (SELECT `ID` FROM `sco_edt` WHERE `Active` = 1 )';
		return parent::getList($args, $query);
	}

	public function getLabel($short = false, $for_ens = false, $show_ens = true, $hide_time = false)
	{

		$label = $this->get('Unite') ? $this->get('Unite')->get('Label') : '';

		if ($this->get('Matiere')) {
			$label .= ' - ' . $this->get('Matiere')->get('Label');
		}

		if ($short) {
			return $label;
		}

		if ($this->get('Enseignant') &&  !$for_ens && $show_ens)
			$label .= ' - ' .  $this->get('Enseignant')->get('User')->getNomComplet();

		if ($for_ens)
			$label .= ' - ' .  $this->get('Classe')->get('Label');

		if (!$hide_time)
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

	public function getMinutes()
	{
		$start = strtotime($this->From);
		$end = strtotime($this->To);
		return  $mins = ($end - $start) / 60;
	}



	static function  getDistinctSeance()
	{
		return  \DB::reader("SELECT DISTINCT `Day`,`From`,`To` FROM sco_seance");
	}

	static function checkSeance($classe, $day, $from, $to, $teacher = null, $unite = null, $salle = null)
	{
		$where = array(
			'Day' => $day,
			'From' => $from,
			'To' => $to,
		);
		$where[] = 'Classe != ' . $classe;
		if ($teacher)
			$where['Enseignant'] = $teacher;
		if ($unite)
			$where['Unite'] = $unite;
		if ($salle)
			$where['Salle'] = $salle;

		// var_dump($where);
		// exit;
		$seances = Seance::getList(array('where' => $where));

		return $seances;
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
}
