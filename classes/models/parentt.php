<?php

namespace Models;

use Exception;

class Parentt extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'parents';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'User' => array(
			'fk' => 'User',
		),
		'Visible' => array(
			'type' => 'boolean',
		),
		'Banner' => array(
			'type' => 'varchar',
		),
		'ForeignLang' => array(
			'type' => 'varchar',
		),
		'SituationFamiliale' => array(
			'type' => 'varchar',
		),
		'Fonction' => array(
			'type' => 'varchar',
		),
		'Lang' => array(
			'type' => 'varchar',
		),
		'CRMApplicationFields' => array(
			'type' => 'varchar',
		),
	);

	public function getCrmField($field)
	{
		$fields = $this->getArray('CRMApplicationFields');
		return $fields && property_exists($fields, $field) && $fields->$field ? $fields->$field : null;
	}

	public function setCrmField($field, $value)
	{
		$fields = $this->getArray('CRMApplicationFields', false, true);
		$fields[$field] = $value;
		$this->setJson('CRMApplicationFields', $fields);
	}

	public function getBanner()
	{
		if (!$this->get('Banner'))
			return \URL::base() . \Config::get('path-images-parents') . 'default.jpg';
		else
			return \GoogleStorage::getUrl(\Config::get('path-images-parents') . $this->get('Banner'));
	}

	public function getSituation()
	{
		$situation = null;
		$homme = $this->get('User')->get('Homme');
		switch ($this->get('SituationFamiliale')) {
			case 'marie':
				$situation = $homme ? 'Marié' : 'Mariée';
				break;
			case 'divorce':
				$situation = $homme ? 'Divorcé' : 'Divorcée';
				break;
			case 'veuf':
				$situation = $homme ? 'Veuf' : 'Veuve';
				break;
		}
		return $situation;
	}

	public function CleanBeforeDelete()
	{
		$count = 0;

		$parrainages = Parrainage::getList(array('where' => array('Parent' => $this->get('ID'))));
		$count += count($parrainages);

		$count += $this->get('User')->CleanBeforeDelete();

		foreach ($parrainages as $parrainage)
			$parrainage->delete();

		return $count;
	}

	public static function phones()
	{
		$items = Parentt::getList();
		$phones = array();
		if (!$items)
			return null;
		foreach ($items as $item) {
			try {
				if ($item->get('User'))
					$phones[] = $item->get('User')->get('Tel');
			} catch (\Exception $th) {
				continue;
			}
		}
		return $phones;
	}

	public function	eleves()
	{
		$eleves = array();
		$parrinages = $this->ID ? Parrainage::getList(array('where' => array('Parent' => $this->ID))) : array();
		foreach ($parrinages as $p) {
			try {
				$eleves[] = $p->Eleve;
			} catch (Exception $th) {
			}
		}
		return $eleves;
	}

	public function	getInscriptions($promotion =  null)
	{
		$inscriptions = array();
		foreach ($this->eleves() as $e) {
			if ($e->getInscription($promotion)) {

				$inscriptions[] = $e->getInscription($promotion);
			} else {
				continue;
			}
		}
		return $inscriptions;
	}

	// crm
	public function	lastAction()
	{
		$actions =  CRM\Action::where(array('Parent' => $this->get('ID')))->order(array('ActionDate' => false))->limit(1)->get();
		return $actions ? $actions[0] : null;
	}

	public function	lastVisite()
	{
		$actions =  CRM\Action::where(array('Parent' => $this->get('ID'), 'Action' => 'visite'))->order(array('ActionDate' => false))->get();
		return $actions ? $actions[0] : null;
	}

	public function	lastInterview()
	{
		$actions =  CRM\Action::where(array('Parent' => $this->get('ID'), 'Action' => 'interview'))->order(array('ActionDate' => false))->get();
		return $actions ? $actions[0] : null;
	}

	function getActionAdvancement($action)
	{

		if ($action && in_array($action->get('Action'), array('visite', 'interview', 'call', 'form', 'letter', 'contract'))) {
			$details  = $action->getArray('ActionDetails');

			switch ($action->get('Action')) {
				case 'visite':
					$result = array(
						'text' =>  property_exists($details, 'status') && $details->status ? \Models\CRM\Action::visiteStatus($details->status) : 'A programmer',
						'icon' => 'fa-university',
						'color' => 'l-warning'
					);
					break;
				case 'interview':
					$result = array(
						'text' =>   property_exists($details, 'status') && $details->status ? \Models\CRM\Action::interviewStatus($details->status) : 'A programmer',
						'icon' => 'fa-users',
						'color' => property_exists($details, 'status') && $details->status ? \Models\CRM\Action::interviewColors($details->status) : 'l-danger'
					);
					break;
				case 'call':
					$result = array(
						'text' => property_exists($details, 'feedback') &&  property_exists($details->feedback, 'result') ? \Models\CRM\Action::callStatus($details->feedback->result) : 'A programmer',
						'icon' => 'fa-phone',
						'color' => property_exists($details, 'feedback') &&  property_exists($details->feedback, 'result') ? \Models\CRM\Action::callColors($details->feedback->result) : 'l-danger'
					);
					break;
				case 'form':
					$result = array(
						'text' =>   property_exists($details, 'status') && $details->status ? \Models\CRM\Action::formStatus($details->status) : 'A programmer',
						'icon' => 'fa-users',
						'color' => property_exists($details, 'status') && $details->status ? \Models\CRM\Action::formColors($details->status) : 'l-danger'
					);
					break;
				case 'letter':
					$result = array(
						'text' =>   property_exists($details, 'status') && $details->status ? \Models\CRM\Action::letterStatus($details->status) : 'A programmer',
						'icon' => 'fa-users',
						'color' => property_exists($details, 'status') && $details->status ? \Models\CRM\Action::letterColors($details->status) : 'l-neutre'
					);
					break;
				case 'contract':
					$result = array(
						'text' =>   property_exists($details, 'status') && $details->status ? \Models\CRM\Action::contractStatus($details->status) : 'A programmer',
						'icon' => 'fa-users',
						'color' => property_exists($details, 'status') && $details->status ? \Models\CRM\Action::contractColors($details->status) : 'l-neutre'
					);
					break;
				case 'test':
					$result = array(
						'text' =>   property_exists($details, 'status') && $details->status ? \Models\CRM\Action::testStatus($details->status) : 'A programmer',
						'icon' => 'fa-users',
						'color' => property_exists($details, 'status') && $details->status ? \Models\CRM\Action::testColors($details->status) : 'l-danger'
					);
					break;
			}


			return $result;
		}

		if ($action && in_array($action->get('Action'), array('email', 'send_email', 'sms')))
			return null;

		$result = array(
			'text' => 'A programmer',
			'color' => 'l-warning'
		);

		if (!$action) {
			return $result;
		}

		$details  = $action->getArray('ActionDetails');
		if (!property_exists($details, 'feedback')) {
			$result = array(
				'text' => 'Programmé',
				'color' => 'l-info'
			);
		} else {
			switch ($details->feedback->canceled) {
				case '0':
					$result = array(
						'text' => 'Fait',
						'color' => 'l-success'
					);
					break;
				case '1':
					$result = array(
						'text' => 'Annulé',
						'color' => 'l-danger'
					);
					break;
			}
		}

		return $result;
	}

	function getActionFeedback($action)
	{
		$result = array(
			'text' => 'En attente d\'un retour',
			'icon' => 'fa-university',
			'color' => 'l-warning'
		);

		if (!$action) {
			return $result;
		}

		$details  = $action->getArray('ActionDetails');

		if ($action) {
			switch ($action->get('Action')) {
				case 'visite':
					$result = array(
						'text' =>  property_exists($details, 'feedback') &&  property_exists($details->feedback, 'result') ? \Models\CRM\Action::visiteResultFeedback($details->feedback->result) : 'En attente d\'un retour',
						'icon' => 'fa-university',
						'color' =>  property_exists($details, 'feedback') &&  property_exists($details->feedback, 'result') ? \Models\CRM\Action::visitColors($details->feedback->result) : 'l-neutre',
					);
					break;
				case 'interview':
					$result = array(
						'text' =>   property_exists($details, 'feedback') &&  property_exists($details->feedback, 'result') ? \Models\CRM\Action::interviewResultFeedback($details->feedback->result) : 'En attente d\'un retour',
						'icon' => 'fa-users',
						'color' => property_exists($details, 'feedback') &&  property_exists($details->feedback, 'result') ? \Models\CRM\Action::interviewColors($details->feedback->result) : 'l-neutre'
					);
					break;
				case 'test':
					$result = array(
						'text' =>   property_exists($details, 'feedback') &&  property_exists($details->feedback, 'result') ? \Models\CRM\Action::testResultFeedback($details->feedback->result) : 'En attente des résultats',
						'icon' => 'fa-users',
						'color' => property_exists($details, 'feedback') &&  property_exists($details->feedback, 'result') ? \Models\CRM\Action::testColors($details->feedback->result) : 'l-neutre'
					);
					break;
				case 'call':
					$result = array(
						'text' => null,
						'icon' => 'fa-phone',
						'color' => property_exists($details, 'feedback') &&  property_exists($details->feedback, 'result') ? \Models\CRM\Action::callColors($details->feedback->result) : 'l-neutre'
					);
					break;
				case 'form':
					$result = array(
						'text' =>   property_exists($details, 'feedback') &&  property_exists($details->feedback, 'result') ? \Models\CRM\Action::formResultFeedback($details->feedback->result) : 'En attente d\'un retour',
						'icon' => 'fa-users',
						'color' => property_exists($details, 'feedback') &&  property_exists($details->feedback, 'result') ? \Models\CRM\Action::formColors($details->feedback->result) : 'l-neutre'
					);
					break;
				case 'letter':
					$result = array(
						'text' =>   property_exists($details, 'feedback') &&  property_exists($details->feedback, 'result') ? \Models\CRM\Action::letterResultFeedback($details->feedback->result) : 'En attente d\'un retour',
						'icon' => 'fa-users',
						'color' => property_exists($details, 'feedback') &&  property_exists($details->feedback, 'result') ? \Models\CRM\Action::letterColors($details->feedback->result) : 'l-neutre'
					);
					break;
				case 'contract':
					$result = array(
						'text' =>   property_exists($details, 'feedback') &&  property_exists($details->feedback, 'result') ? \Models\CRM\Action::contractResultFeedback($details->feedback->result) : 'En attente d\'un retour',
						'icon' => 'fa-users',
						'color' => property_exists($details, 'feedback') &&  property_exists($details->feedback, 'result') ? \Models\CRM\Action::contractColors($details->feedback->result) : 'l-neutre'
					);
					break;
				case 'email':
					$result = array(
						'text' => '',
						'icon' => 'fa-envelope-open-o',
						'color' => 'l-warning'
					);
					break;
				case 'sms':
					$result = array(
						'text' => '',
						'icon' => 'fa-mobile-phone',
						'color' => 'l-warning'
					);
					break;
			}
		}


		if ($action->get('Action') == 'email') {
			if (property_exists($details, 'state')) {
				switch ($details->state) {
					case '0':
						$result['text'] = 'Mauvaise adresse mail';
						$result['color'] = 'l-danger';
						break;
					case '1':
						$result['text'] = 'Envoyé et reçu';
						$result['color'] = 'l-info';
						break;
				}
			}
			return $result;
		}

		if ($action->get('Action') == 'send_email') {
			$result['text'] = 'E-mail sent';
			$result['color'] = 'l-success';
			return $result;
		}


		if (property_exists($details, 'feedback') && property_exists($details->feedback, 'flag')) {
			switch ($details->feedback->flag) {
				case '0':
					$result['text'] = 'Negative';
					$result['color'] = 'l-danger';
					break;
				case '1':
					$result['text'] = 'Positive';
					$result['color'] = 'l-success';
					break;
				case '2':
					$result['text'] = $action->get('Action') == 'interview' ? 'Undecided' : 'Hesitant';
					$result['color'] = 'l-neutre';
					break;
			}
		}

		return $result;
	}

	static function  interest($key = null)
	{
		$items =  [
			'summer_camp_uk' => ' Summer camp UK',
			'summer_camp_morocco' => 'Summer camp Morocco',
			'after_school_program,' => 'After school program',
			'smart_school_program' => 'Smart school program',
			'british_school' => 'British school',
			'english_support' => 'English support',
		];

		if ($key) {
			return isset($items[trim($key)]) ? $items[trim($key)] : '---';
		}

		return  $items;
	}

	static function  channels($key = null)
	{
		$items =  [
			'facebook' => 'facebook',
			'google' => 'google',
			'Phone' => 'Phone',
			'visit' => 'Visit',
			'recommadation' => 'Recommadation',
			'radio' => 'Radio',
			'press' => 'press',
			'offline_ads' => 'Offline ads',
			'parteners' => 'Parteners',
			'website' => 'Website',
		];

		if ($key) {
			return isset($items[trim($key)]) ? $items[trim($key)] : '---';
		}

		return  $items;
	}

	static function contracts($key = null)
	{

		$items =  [
			(object)[
				'alias' => 'services_content_rules',
				'label' => 'PAYMENTS, TAXES, AND REFUNDS',
				'description' => 'You can acquire Content on our Services for free or for a charge, either of which is referred to as a “Transaction.',
				'file' => '',
			],
			(object)[
				'alias' => 'pyaments_taxes_refunds',
				'label' => 'SERVICES AND CONTENT USAGE RULES',
				'description' => 'You can acquire Content on our Services for free or for a charge, either of which is referred to as a “Transaction.”.',
				'file' => '',
			]
		];

		if ($key) {
			return isset($items[trim($key)]) ? $items[trim($key)] : '---';
		}

		return  $items;
	}

	static function autorisations($key = null)
	{

		$items =  [
			(object)[
				'alias' => 'activities',
				'label' => 'Activities',
				'description' => 'I authorize my child to participate in activities organized by the school outside of school in an educational context.',
			],
			(object)[
				'alias' => 'pictures',
				'label' => 'Pictures',
				'description' => 'I authorize my child to participate in activities organized by the school outside of school in an educational context..',
			]
		];

		if ($key) {
			return isset($items[trim($key)]) ? $items[trim($key)] : '---';
		}

		return  $items;
	}

	static function interestsStatus($key = null)
	{

		$items =  [
			'new' => (object)[
				'alias' => 'en_attente',
				'label' => 'En attente',
				'color' => 'neutre',
			],
			'not_interested' => (object)[
				'alias' => 'mon_interesse',
				'label' => 'Non intéressé',
				'color' => 'danger',
			],
			'interested_out' => 	(object)[
				'alias' => 'interesse',
				'label' => 'Intéressé',
				'color' => 'warning',
			],
			'interested_keep' => (object)[
				'alias' => 'Indecis',
				'label' => 'Indécis',
				'color' => 'warning',
			],
		];

		if ($key) {
			return isset($items[trim($key)]) ? $items[trim($key)] : $items['new'];
		}

		return  $items;
	}

	// end the crm


}
