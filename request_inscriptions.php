<?php

use Models\User;

/**
 * Controller Class 
 */

class ContentController
{
	function __construct()
	{
		// Session::getInstance()->requireLogin(true);
		if (Request::isPost()) {

			$_SESSION['flash_error'] = NULL;
			$_SESSION['previous_post'] = NULL;
		}
	}

	function index()
	{

		$data = array();
		if (isset($_SERVER['HTTP_REFERER'])) {
			$_SESSION['http_referer_inscription']  = $_SERVER['HTTP_REFERER'];
		}

		return loadView('request_inscriptions', isset($data) ? $data : NULL, '_layout-inscription');
	}


	function add()
	{
		if (Request::isPost()) {

			$exists = Models\RequestInscription::where(array('Email' => $_POST['email'], 'GSM' => $_POST['tel']))->first();

			if (!$exists) {
				$inscription  = new Models\RequestInscription();
				$inscription
					->set('Nom', $_POST['nom'])
					->set('Prenom', $_POST['prenom'])
					->set('NiveauEnseignement', $_POST['niveau'])
					->set('Adresse', $_POST['adresse'])
					->set('GSM', $_POST['tel'])
					->set('Email', $_POST['email'])
					->set('CreatedAt', date('Y-m-d h:i:s'));
				$inscription->save();


				$_SESSION['success_inscription'] = true;
				// mail to parrent 
				if ($inscription->get('Email')) {
					MailTo(
						$inscription->get('Email'),
						"Demande d'inscription envoyée avec succés",
						getView(
							'emails/email_inscription_parrent',
							array('inscription' => $inscription),
							"null_layout"
						),
						array('email' => 'noreply@boti.education', 'name' => Config::get('nom_ecole'))
					);
				}
				//mail to admin
				$admins = User::getList(array('where' => array("Role in(1)")));
				foreach ($admins as $admin) {

					if ($admin->hasAutorisation('receive_inscriptions_email_notifications')) {
						MailTo(
							$admin->get('Email'),
							Config::get('nom_ecole')	. ' | Une nouvelle demande d\'inscription',
							getView(
								'emails/email_inscription_admin',
								array('inscription' => $inscription, 'user' => $admin),
								"null_layout"
							),
							array('email' => 'noreply@boti.education', 'name' => Config::get('nom_ecole'))
						);
					}
				}

				MailTo(
					'ahmed@boti.education',
					Config::get('nom_ecole')	. ' | Une nouvelle demande d\'inscription',
					getView(
						'emails/email_inscription_admin',
						array('inscription' => $inscription, 'user' => $admin),
						"null_layout"
					),
					array('email' => 'noreply@boti.education', 'name' => Config::get('nom_ecole'))
				);

				MailTo(
					'taha@boti.education',
					Config::get('nom_ecole')	. ' | Une nouvelle demande d\'inscription',
					getView(
						'emails/email_inscription_admin',
						array('inscription' => $inscription, 'user' => $admin),
						"null_layout"
					),
					array('email' => 'noreply@boti.education', 'name' => Config::get('nom_ecole'))
				);
			};
		}

		URL::redirect(URL::base('/request_inscriptions'));
	}
}

/* Router options */
$action = Request::getArgs(0) ? Request::getArgs(0) : 'index';
$id = Request::getArgs(1);
// $args['id'] = $id;

#call the proper action
try {

	if (!method_exists('ContentController', $action))
		throw new Exception("Error Processing Request", 1);

	$controller = new ContentController;
	$controller->{$action}($id);
} catch (Exception $e) {

	print_r($e);
	exit;

	if (function_exists('http_response_code'))
		http_response_code(404);
	loadView404();
}
