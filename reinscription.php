<?php

$promotion = new Models\Promotion(4);

if (Request::isPost()) {

    switch ($_POST['op']) {
		
        case 'reinscription':

            if(isset($_POST['eleves'])) {
				foreach($_POST['eleves'] as $item) {
					
					$eleve = new Models\Eleve($item);
					$inscription = $eleve->getInscription();
					
					$reinscription = Models\Inscription::getCount(array('where' => array('Eleve' => $eleve->get('ID'),'Promotion' => $promotion->get('ID'))));
					if($reinscription > 0) 
						continue;
					
					$newInscription = new Models\Inscription();
					$newInscription
						->set('Eleve', $eleve)
						->set('Promotion', $promotion)
						->set('Niveau', $inscription->get('Classe')->get('Niveau')->nextNiveau())
						->save()
						;
				}
			}

            URL::redirect(URL::link('reinscription/'.$curUser->get('ID')));
		
        case 'inscription':
		
			if (!isset($_POST['cf_token']) || !verify_token($_POST['cf_token'])) {
				header('Location:'.$_POST['return']);
				exit;
			}
			
			$utilisateur = new Models\User();
			$utilisateur
				->set('Key', \Tools::getRandChars(30, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'))
				->set('Role', 3)
				->set('Nom', $_POST['nom_eleve'])
				->set('Prenom', $_POST['prenom_eleve'])
				->set('Homme', isset($_POST['sexe_eleve'])?$_POST['sexe_eleve']:null)
				->set('Tel', isset($_POST['tel_eleve'])?$_POST['tel_eleve']:null)
				->set('Adresse', isset($_POST['adresse'])?$_POST['adresse']:null)
				->set('Date', date('Y-m-d H:i:s'))
				->save()
				;
				
			$eleve = new Models\Eleve();
			$eleve
				->set('User', $utilisateur)
				->save()
				;
			
			try {
				$niveau = new Models\Niveau($_POST['niveau']);
			} catch (Exception $e) {
				$niveau = null;
			}
			
			$inscription = new Models\Inscription();
				
			$champs = array();
			
			if(isset($_POST['branche_desiree']) && $_POST['branche_desiree'] != '0') {
				
				$champs['Branche désirée'] = $_POST['branche_desiree'];
			}
			
			if(isset($_POST['dernier_etablissement']) && $_POST['dernier_etablissement']) {
				
				$champs['Dernier établissement fréquenté'] = $_POST['dernier_etablissement'];
			}
			
			if(isset($_POST['moyenne']) && $_POST['moyenne']) {
				
				$champs['Moyenne de la dernière année'] = $_POST['moyenne'];
			}
			
			
			if(isset($_POST['branche_origine']) && $_POST['branche_origine'] != '0') {
				
				$champs['Branche d\'origine'] = $_POST['branche_origine'];
			}
			
			if($champs)
				$inscription->set('Champs', json_encode($champs));
			
			
			$inscription
				->set('Eleve', $eleve)
				->set('Promotion', $promotion)
				->set('Niveau', $niveau)
				->save()
				;
				
				
			$papa = new Models\Parentt();
			$userPapa = new Models\User();
			$userPapa
				->set('Key', \Tools::getRandChars(30, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'))
				->set('Role', 2)
				->set('Homme', (isset($_POST['type']) && $_POST['type'] == 2)?false:true)
				->set('Nom', $_POST['nom_parent'])
				->set('Prenom', $_POST['prenom_parent'])
				->set('Tel', $_POST['gsm'])
				->set('Adresse', isset($_POST['adresse'])?$_POST['adresse']:null)
				->set('Email', isset($_POST['email_tuteur'])?$_POST['email_tuteur']:null)
				->save()
				;
				
			$papa
				->set('User', $userPapa)
				->save()
				;

			$parrainage = new Models\Parrainage();
			$parrainage
						->set('Eleve', $eleve)
						->set('Type', (isset($_POST['type']))?$_POST['type']:1)
						->set('Parent', $papa)
						->save()
						;
						
			mailNouvelleInscription($eleve);

			$_SESSION['inscription_SJ'] = 'Ok';
           
			header('Location:'.$_POST['return']);

			exit;
    }

}

Session::getInstance()->setCurUser(new Models\User(Request::getArgs(0)));
Config::set('admin', 'parent');
$curUser = Session::getInstance()->getCurUser();
$tuteur = $curUser->getParent();

if(!$tuteur)
	URL::redirect(URL::link('login'));

$parrainages = Models\Parrainage::getList(array('where' => array('Parent' => $tuteur->get('ID'))));
$reinscriptionFaite = true;
$eleves = array();

foreach($parrainages as $item) {
	
	$inscription = $item->get('Eleve')->getInscription();
	
	if(!$inscription->get('Classe'))
		continue;
	
	if($inscription->get('Classe')->get('Niveau')->ifLastNiveau())
		continue;
	
	$reinscription = Models\Inscription::getCount(array('where' => array('Eleve' => $item->get('Eleve')->get('ID'),'Promotion' => $promotion->get('ID'))));
	if($reinscription == 0)
		$reinscriptionFaite = false;
	
	// if($reinscription > 0)
		// continue;
		
		
	$eleve = array();
	$eleve['eleve'] = $item->get('Eleve');
	$eleve['inscription'] = $inscription;
	
	
	$eleves[] = $eleve;
	
}

if(!$eleves && !$reinscriptionFaite)
	URL::redirect(URL::link('login'));

loadView('reinscription', array(
	'reinscriptionFaite' => $reinscriptionFaite,
	'eleves' => $eleves,
	'promotion' => $promotion,
	'navKey' => 'reinscription',
),'_layout-login');
