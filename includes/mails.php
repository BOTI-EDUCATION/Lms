<?php
$css = 'body{color:#333;font-family:\'Segoe UI\', Arial, sans-serif;max-width:600px;font-size:13px} h1,h2,h3{font-size: 14px; font-weight: bold; color: #010101;} h1{font-size:1.6em;} h3{font-size:1.3em;} ul{list-style:none;padding-left:15px;} a{color:#66C;text-decoration:none} a:hover{color:#339;text-decoration:underline}';

function nouveauMessage($message = null)
{
	loadLib('sendgrid');

	$email = new \SendGrid\Email();

	$email
		// Template de l'email
		->setTemplateId('be52113c-fcff-4b5c-8d66-e1e7badb8593') // crée sur le Dashboard SendGrid
		// ->setTemplateId('b7d3ba6b-6149-4a4d-9ea9-b2ca54ca83cc') // crée sur le Dashboard SendGrid
		->addFilter('bypass_list_management', 'enabled', 1) // Only for important messages !! like password resets
		// To
		// ->addSmtpapiTo($inscription->get('Etudiant')->get('User')->get('Email'), $inscription->get('Etudiant')->get('User')->getNomComplet())
		// ->addSmtpapiTo('a.semoud@gmail.com', 'SEMOUD Ahmed')
		// ->addSmtpapiTo('hanaa@smartux.ma', 'Hanaa KARDOUDI')
		->addSmtpapiTo(Config::get('mailing_admin'), Config::get('mailing_admin'))
		// From
		->setFrom('marketing@smartux.ma')
		->setFromName('BOTI.EDUCATION')
		->setReplyTo('marketing@smartux.ma')
		// Content, vide si une template est utilisé, le contenu sera remplacé par les variables ci-dessous
		->setSubject('Vous avez reçu un nouveau message')
		->setText(' ')
		->setHtml(' ')
		// Variables
		->addSubstitution("%message%", array('Vous avez reçu un nouveau message de la part de  <strong>' . $message->get('User')->getNomComplet() . '</strong> papa de  <strong>' . $message->get('Inscription')->get('Eleve')->get('User')->getNomComplet() . '</strong> de la classe ' . ($message->get('Inscription')->get('Classe') ? $message->get('Inscription')->get('Classe')->get('Label') : '-') . '.'))
		->addSubstitution("%link%", array(URL::absolute(URL::admin('messages/inbox/' . $message->get('ID')))))
		->addSubstitution("%link_title%", array('Consulter le message'))
		->addSubstitution("%img%", array(URL::absolute($message->get('Inscription')->get('Eleve')->get('User')->getImage())))
		// Tracking
		// ->addUniqueArg('ClientID', $inscription->get('ID'))
		->addUniqueArg('ClientID', $message->get('ID'))
		->addCategory('BOTI-Nouveau-Message');

	Mail::send($email);
}
function MailTo($to, $subject, $message = "", $from = null)
{
	myMail($subject, $message, $to, $from);
}

function mailNouvelleInscription($eleve)
{
	$css = 'body{color:#333;font-family:\'Segoe UI\', Arial, sans-serif;max-width:600px;font-size:13px} h1,h2,h3{font-size: 14px; font-weight: bold; color: #010101;}   h3{font-size:1.3em;} ul{list-style:none;padding-left:15px;} a{color:#66C;text-decoration:none} a:hover{color:#339;text-decoration:underline}';

	$lien = '<a href="' . URL::absolute(URL::link('')) . '">ce lien.</a>';

	$message  = '<html><head><meta charset="utf-8"><style>' . $css . '</style></head><body>';
	$message .= '<h1>Bonjour,</h1>';
	$message .= '<h1>Nouvelle inscription à été effectuée le ' . Tools::dateFormat($eleve->get('User')->get('Date'), '%d %b %Y %H:%M') . ' </h1>';
	$message .= '<ul>';
	$message .= '<li><b>Nom complet</b> : ' . $eleve->get('User')->getNomComplet() . '</li>';
	$message .= '<li><b>GSM</b> : ' . $eleve->get('User')->get('Tel') . '</li>';
	$message .= '</ul>';
	$message .= '<p>Pour plus de détails, allez sur votre espace admin sur gs-alyawm.com ou cliquez sur ' . $lien . '</p>';
	$message .= '</body></html>';

	// $to = $candidat->get('email');
	$subject = 'Nouvelle inscription : ' . Tools::dateFormat($eleve->get('User')->get('Date'), '%d %b %Y %H:%M');
	$from = 'NoReply <noreply@gs-alyawm.com>';

	$headers  = 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/html; charset=UTF-8' . "\r\n" . 'From: ' . $from . "\r\n";
	return myMail($subject, $message);
}


//*********************************************************** Mail Test

function mailTest()
{
	global $css;

	$subject = 'Test Messagerie';

	$message  = '<html><head><meta charset="utf-8"><style>' . $css . '</style></head><body>';
	$message .= '<h1>Test</h1>';
	$message .= '<p><small>envoyé le ' . Tools::dateFormat(new Datetime()) . '</small></p>';
	$message .= '<h3>Info</h3>';
	$message .= '<p>Bonjour cet email est juste pour tester l\'envoi, ainsi que la réception, pour les emails envoyé<p>';
	$message .= '</body></html>';

	return myMail($subject, $message);
}

//*********************************************************** Envoi Mail
function myMail($subject, $message, $to = null, $from = null)
{
	global $depth;
	require_once 'includes/phpmailer/PHPMailerAutoload.php';

	$mail = new PHPMailer;
	$mail->XMailer = ' ';

	// $mail->SMTPDebug = 3;				// Enable verbose debug output

	$mail->isSMTP();							// Set mailer to use SMTP
	$mail->Host = Config::get('smtpHost');			// Specify main and backup SMTP servers
	$mail->Port = Config::get('smtpPort');				// TCP port to connect to
	$mail->Username = Config::get('smtpUsername');		// SMTP username
	$mail->Password = Config::get('smtpPassword');		// SMTP password
	$mail->SMTPAuth = true;						// Enable SMTP authentication
	$mail->SMTPSecure = 'ssl';					// Enable TLS encryption, `ssl` also accepted

	$fromEm = $from ?: Config::get('fromEmail');
	
	if (is_array($fromEm)) {
		$mail->setFrom(array_key_exists('email', $fromEm) ? $fromEm['email'] : Config::get('smtpUsername'), $fromEm['name'] ? $fromEm['name'] : '');
		$mail->addReplyTo(array_key_exists('email', $fromEm) ? $fromEm['email'] : Config::get('smtpUsername'), $fromEm['name'] ? $fromEm['name'] : '');
	}

	if (!$to)
		$to = Config::get('adminEmail');
	if (is_array($to)) {
		foreach ($to as $k => $v) {
			if (is_array($v))
				if (array_key_exists('email', $v))
					if (array_key_exists('name', $v))
						$mail->addAddress($v['email'], $v['name']);
					else
						$mail->addAddress($v['email']);
				else
					$mail->addAddress($v);
		}
		if (array_key_exists('email', $to))
			if (array_key_exists('name', $to))
				$mail->addAddress($to['email'], $to['name']);
			else
				$mail->addAddress($to['email']);
	} else
		$mail->addAddress($to);

	$mail->WordWrap = 120;
	$mail->isHTML(true);
	$mail->CharSet = 'UTF-8';

	$mail->Subject = $subject;
	$mail->Body = $message;

	$sent = $mail->send();

	if (!$sent) {
		echo 'Mail Error: ' . $mail->ErrorInfo;
	}
	return $sent;
}
