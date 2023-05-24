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

	function parent($parent)
	{
		$pk_parent = hash_decode($parent);

		if (!$pk_parent) {
			ob_end_clean();
			exit('Object not found');
		}

		try {
			$parent = new Models\Parentt($pk_parent);
			$next_promotion =  Models\Promotion::promotion_overte_pour_inscriptions();
		} catch (Exception $e) {
			ob_end_clean();
			exit('Object not found');
		}

		if (Request::isPost()) {
			foreach (Request::get('eleves') as $eleve) {
				$eleve   = new Models\Eleve($eleve);
				$inscription = $eleve->getInscription();

				$request_inscription = Models\RequestInscription::where(['ReInscriptionEleve' => $eleve->ID, 'ReInscriptionPromotion' => $next_promotion->ID])->first();
				if (!$eleve->getInscription($next_promotion) && !$request_inscription) {

					$req_inscription  = new Models\RequestInscription();
					$req_inscription
						->set('ReInscriptionEleve', $eleve->ID)
						->set('ReInscriptionPromotion', $next_promotion->ID)
						->set('CreatedAt', date('Y-m-d h:i:s'))
						->save();


					//SEND MAIL TO ADMIN
					$sendgrid = new SendGridMail();
					$mail = $sendgrid->mail();
					$mail->addContent("text/html", " ");
					$mail->setTemplateId('d-b24d7f33b1214acfb2c0084807e79221');
					$mail->setFrom('ahmed@boti.education');
					$mail->setReplyTo('ahmed@boti.education');
					$mail->setSubject('Validation en ligne de la réinscription');
					$mail->addSubstitution("user", ($parent->User ? $parent->User->getNomComplet() : ''));
					$mail->addSubstitution("datetime", (date('Y-m-d H:i:s')));
					$mail->addSubstitution("nom_eleve", ($inscription->Eleve));
					$mail->addSubstitution("classe", ($inscription->Classe->Label));

					$admins = User::getList(array('where' => array("Role in(1)")));

					foreach ($admins as $admin) {
						if ($admin->hasAutorisation('receive_reinscriptions_email_notifications')) {
							$mail->addTo($admin->get('Email'), $admin->getNomComplet());
						}
					}
					$sendgrid->send($mail);

					fcm_web_send(\Models\User::firstAdmin()->simpleToken(), "Réinscription en ligne", "Eleve " . $inscription->Eleve->getNomComplet() . " a une demande re-inscription");
				}
			}

			$_SESSION['success_inscription'] = true;
			URL::back();
		}

		$data  =  [
			'parent' => $parent,
			'next_promotion' =>	$next_promotion
		];

		return loadView('reinscriptions', isset($data) ? $data : NULL, '_layout-inscription');
	}
}

/* Router options */
$action = Request::getArgs(0) ? Request::getArgs(0) : 'index';
$id 	= Request::getArgs(1);
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
