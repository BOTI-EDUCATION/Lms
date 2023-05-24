<?php

/**
 * Controller Class 
 */

class ContentController
{

	function __construct()
	{
		//Session::getInstance()->requireLogin(true);
		if (Request::isPost()) {

			$_SESSION['flash_error'] = NULL;
			$_SESSION['previous_post'] = NULL;
		}
	}

	private $config = array(
		'label' => array(
			'label' => 'Label',
			'filter' => 'trim|strip_tags'
		)
	);


	function cours($id = 0)
	{

		global $app;

		//$eleve = Session::getInstance()->getCurUser()->getEleve();
		//$inscription = $eleve->getInscription();

		if ($id) {
			try {
				$cours = new Models\Cours(hash_decode($id));
			} catch (Exception $e) {
				throw new Exception("Not Found Item", 404);
			}

			$meetId = $cours->get('MeetID');
			$app['video_conference']['roomName'] = $meetId;
			$app['video_conference']['serveur'] 	= Config::has('serveurvisio') ? Config::get('serveurvisio') : 'https://meet.jit.si';
			if (Session::getInstance()->getCurUser()) {
				$app['video_conference']['avatarUrl'] = (Session::getInstance()->getCurUser()->get('Image') ? URL::absolute(Session::getInstance()->getCurUser()->getImage()) : '');
				$app['video_conference']['displayName'] = Session::getInstance()->getCurUser()->getNomComplet();
				$app['video_conference']['email'] = Session::getInstance()->getCurUser()->get('Email');
			}
			$data['cours'] = $cours;
			$data['classe'] = $cours->get('Classe');


			/*
			$dateNow = date('H:i');

			$periode = '';
			if ($dateNow < Models\Seance::matinHeureFin()) {
				$periode = 'matin';
			} else {
				$periode = 'apres-midi';
			}

			$presences = Models\Presence::getCount(array('where' => array(
				'Inscription' => $inscription->get('ID'),
				'Periode' => $periode,
				'Cours' => $cours->get('ID'),
			)));

			if (!$presences) {

				$presence = new Models\Presence();
				$presence
					->set('Inscription', $inscription)
					->set('Cours', $cours->get('ID'))
					->set('Periode', $periode)
					->set('UserBy', Session::getInstance()->getCurUser())
					->set('Date', date('Y-m-d H:i:s'))
					->save();
			}
			*/

			return loadView('cours/cours-item', isset($data) ? $data : NULL, '_layout-meet');
		} else {
			throw new Exception("Cannot view Item details", 1);
		}
	}
}


/* Router options */
$action = Request::getArgs(0) ? Request::getArgs(0) : 'index';
$id = Request::getArgs(1);
// $args['id'] = $id;


if (!method_exists('ContentController', $action))
	throw new Exception("Error Processing Request", 1);

$controller = new ContentController;
$controller->{$action}($id);
