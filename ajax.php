<?php

function send($response)
{
	exit(json_encode($response));
}

function sendResult($result)
{
	$response = array();
	$response['type'] = 'OK';
	$response['data'] = $result;
	$response['cf_token'] = cf_token();
	send($response);
}
function sendError($msg, $code = null)
{
	$response = array();
	$response['type'] 	= 'ERR';
	$response['msg'] 	= $msg;
	$response['code'] 	= $code;
	send($response);
}

if (Request::isPost()) {
	if (!isset($_POST['op']))
		sendError('', 13);
	switch ($_POST['op']) {
		case 'get-question-html':

			$result = array();
			$question = $_POST['question'];
			$image = $_POST['image'];
			$reponse_correct = $_POST['reponse_correct'];
			$temps_reponse = $_POST['temps_reponse'];
			$reponses = implode('##', explode(',', $_POST['reponses']));

			if (isset($_FILES['file']) && $_FILES['file']['error'] != UPLOAD_ERR_NO_FILE) {

				$fileError = Upload::checkUpload('file');
				errorPage($fileError);

				$image = Upload::store('file', Config::get('path-images-ressources'));
			}

			ob_start();
?>
			<tr>
				<td class="text-left">
					<?php echo $question ?>
					<input type="hidden" name="questions[]" value="<?php echo $question ?>" />
					<input type="hidden" name="images[]" value="<?php echo $image ? $image : '' ?>" />
					<input type="hidden" name="images_path[]" value="<?php echo URL::absolute(\URL::base() . \Config::get('path-images-ressources') . $image) ?>" />
					<input type="hidden" name="temps_reponse[]" value="<?php echo $temps_reponse ? $temps_reponse : '' ?>" />
					<input type="hidden" name="reponses_correct[]" value="<?php echo $reponse_correct ?>" />
					<input type="hidden" name="reponses[]" value="<?php echo $reponses ?>" />
				</td>
				<td>
					<div class="actions text-xs-center">
						<a data-toggle="tooltip" data-placement="top" title="Modifier" data-index="<?php echo $_POST['formIndex'] ?>" class="btn btn-float btn-square btn-float-md btn-outline-red edit-quiz-question">
							<i class="fa fa-edit"></i>
						</a>
					</div>
				</td>
			</tr>
		<?php
			$html = ob_get_contents();
			ob_clean();

			$result['html'] = $html;

			sendResult($result);

		case 'save-ordre-global':
			$class = '\\Models\\' . $_POST['class'];
			// $ordres = json_decode($_POST['ordres']);
			$ordres = $_POST['ordres'];

			foreach ($ordres as $id => $ordre) {
				if (!$id)
					continue;
				$id = explode(',', $id);
				$obj = new $class($id);
				$obj->set('Ordre', $ordre)->save();
			}

			sendResult(array('OK'));



		case 'programmation_unite_coef':

			$result = array();

			$niveauUnite = new Models\NiveauUnite($_POST['id']);
			$niveauUnite
				->set('Extremite', $_POST['extrimite'])
				->set('Coefficient_Ecole', $_POST['extr-coeff-ecole'])
				->set('Coefficient_Massar', $_POST['extr-coeff-massar']);

			$types = array();
			$types_evaluations = Models\TypeEvaluation::getList(array('where' => array('Enabled' => true)));
			foreach ($types_evaluations as $item) {
				$type = array();
				$type[] = $item->get('Code');
				$type[] = (isset($_POST['nombre-' . $item->get('Code')]) && $_POST['nombre-' . $item->get('Code')]) ? $_POST['nombre-' . $item->get('Code')] : 0;
				$type[] = (isset($_POST['coeff-' . $item->get('Code')]) && $_POST['coeff-' . $item->get('Code')]) ? $_POST['coeff-' . $item->get('Code')] : 0;
				$types[] = implode(',', $type);
			}

			$niveauUnite
				->set(($_POST['semestre'] == 1) ? 'Evaluations' : 'EvaluationsS2', implode('_', $types))
				->save();

			$result['msg'] = 'La modification a été faite avec succés.';


			sendResult($result);

		case 'programmation_unite_matiere':

			$result = array();

			$uniteMatiere = null;
			$uniteMatieres = Models\NiveauUniteMatiere::getList(array('where' => array('Niveau' => $_POST['niveau'], 'Unite' => $_POST['unite'], 'Matiere' => $_POST['matiere'])));
			if ($uniteMatieres)
				$uniteMatiere = $uniteMatieres[0];
			else
				$uniteMatiere = new Models\NiveauUniteMatiere();

			$uniteMatiere
				->set('Unite', $_POST['unite'])
				->set('Matiere', $_POST['matiere'])
				->set('Niveau', $_POST['niveau'])
				->set('Coefficient_Ecole', isset($_POST['enabled_ecole']) ? $_POST['coef_ecole'] : null)
				->set('Coefficient_Massar', isset($_POST['enabled_massar']) ? $_POST['coef_massar'] : null)
				->set('Ordre', isset($_POST['order']) ? $_POST['order'] : null)
				->save();

			$result['msg'] = 'La modification a été faite avec succés.';


			sendResult($result);

		case '_programmation_add_examen':

			$result = array();

			$cours = new Models\Cours();
			$cours
				->set('Examen', true)
				->set('Enseignant', $_POST['enseignant'])
				->set('Classe', $_POST['classe'])
				->set('Date', $_POST['date'])
				->set('Matiere', $_POST['matiere'])
				->set('Creation', date('Y-m-d H:i:s'))
				->set('CreationBy', Session::getInstance()->getCurUser())
				->save();




			ob_start();
		?>

			<a href="<?php echo URL::admin('examens/view/' . $examen->get('ID')) ?>" target="_blank" class="examen_moyenne_td"><?php echo $examen->moyenne_generale() ?></a>
		<?php
			$html = ob_get_contents();
			ob_clean();

			$result['html'] = $html;


			$result['msg'] = 'L’évaluation a été programmée avec succès.';


			sendResult($result);

		case 'programmation_add_examen':

			$result = array();
			$examen = new Models\Evaluation();
			$examen
				->set('Unite', $_POST['unite'])
				->set('Matiere', $_POST['matiere'])
				->set('TypeExam', $_POST['type'])
				->set('Enseignant',  $_POST['enseignant'])
				->set('Classe', $_POST['classe'])
				->set('Niveau', $_POST['niveau'])
				->set('Semestre', $_POST['semestre'])
				->set('Date', date('Y-m-d H:i:s', strtotime($_POST['date'])))
				->set('By', Session::getInstance()->getCurUser())
				->save();

			ob_start();
		?>

			<a href="<?php echo URL::admin('examens/view/' . $examen->get('ID')) ?>" target="_blank" class="examen_moyenne_td"><?php echo $examen->moyenne_generale() ?></a>
		<?php
			$html = ob_get_contents();
			ob_clean();

			$result['html'] = $html;

			$result['msg'] = 'L’évaluation a été programmée avec succès.';

			sendResult($result);

		case 'absences-absence':
			$inscription = new Models\Inscription($_POST['inscription']);
			$classe = new Models\Classe($_POST['classe']);
			$date = $_POST['date'];
			$periode = $_POST['periode'];
			$absent = filter_var($_POST['absent'], FILTER_VALIDATE_BOOLEAN);

			$cours = Models\Cours::getList(array('where' => array('Classe' => $classe->get('ID'), 'Date' => $date)));

			$coursIds = array();
			foreach ($cours as $item) {

				if ($item->get('Seance') && $item->get('Seance')->get('Periode') != $periode)
					continue;

				$absences = Models\Absence::getList(array('where' => array(
					'Inscription' => $inscription->get('ID'),
					$item->get('ID') . ' IN (Cours)'
					// 'Date' => $date,
				)));

				foreach ($absences as $absence) {
					$absence->delete();
				}

				$coursIds[] = $item->get('ID');

				/*
				if ($absences) {
					if ($absent) {
						foreach ($absences as $absence) {
							$absence
								->set('Retards', 0)
								->save()
								;
						}
					}
					else {
						foreach ($absences as $absence) {
							$absence->delete();
						}
					}
				}
				*/
			}

			if ($absent) {

				$absence = new Models\Absence();
				$absence
					->set('Inscription', $inscription)
					->set('Cours', implode(',', $coursIds))
					->set('Date', $date)
					->set('SaisiPar', Session::getInstance()->getCurUser())
					->set('SaisiLe', date('Y-m-d H:i:s'))
					->save();

				// $absence->notifier();
			}

			sendResult(true);

		case 'absences-retard':
			$inscription = new Models\Inscription($_POST['inscription']);
			$classe = new Models\Classe($_POST['classe']);
			$date = $_POST['date'];
			$periode = $_POST['periode'];
			$minutes = $_POST['minutes'];

			$cours = Models\Cours::getList(array('where' => array('Classe' => $classe->get('ID'), 'Date' => $date)));

			$coursIds = array();
			foreach ($cours as $item) {
				if ($item->get('Seance')->get('Periode') != $periode)
					continue;

				$absences = Models\Absence::getList(array('where' => array(
					'Inscription' => $inscription->get('ID'),
					$item->get('ID') . ' IN (Cours)'
					// 'Date' => $date,
				)));

				foreach ($absences as $absence) {
					$absence->delete();
				}

				$coursIds[] = $item->get('ID');
			}


			if ($minutes > 0) {
				$absence = new Models\Absence();
				$absence
					->set('Inscription', $inscription)
					->set('Cours', isset($coursIds[0]) ? $coursIds[0] : null)
					->set('Date', $date)
					->set('Retards', $minutes)
					->set('SaisiPar', Session::getInstance()->getCurUser())
					->set('SaisiLe', date('Y-m-d H:i:s'))
					->save();

				// $absence->notifier();
			}

			sendResult(true);

		case 'absences-discipline-add':

			$inscription = new Models\Inscription($_POST['inscription']);
			$cours = new Models\ETD\SeanceTracking($_POST['cours']);
			$type = new Models\DisciplineActionType($_POST['type']);
			$date = $_POST['date'];

			$action = new Models\DisciplineAction();
			$seance =  $cours->Seance;
			$action
				->set('Cours', $cours)
				->set('DisciplineActionType', $type)
				->set('Inscription', $inscription)
				->set('Commentaire', $_POST['commentaire'])
				->set('Professeur', $seance->get('Enseignant') ? $seance->get('Enseignant')->get('User') : null)
				->set('Matiere', $seance->get('Unite')->get('ID'))
				->set('DateAction', $date)
				->set('Valeur', $type->get('Valeur') * ($type->get('Flag') ? 1 : -1))
				->set('Label', $type->get('Label') . ' - ' . $inscription->get('Eleve')->get('User')->getNomComplet())
				->set('UserBy', Session::getInstance()->getCurUser())
				->set('Date', date('Y-m-d H:i:s'))
				->set('ValidateBy', Session::getInstance()->getCurUser())
				->set('ValidateAt', date('Y-m-d'))
				->save();
			//if (isAllowed('notification_sms_discpline')) {
			$action->notifier();
			//}

			$actionsData = Models\DisciplineAction::getEleveActionData($inscription, $date);

			$result = array(
				'idInscription' => $inscription->get('ID'),
				'actionsData' => $actionsData,
			);

			sendResult($result);

		case 'support-cours':

			$result = array();
			$cours = new Models\Cours($_POST['cours']);

			$support = null;
			if (isset($_FILES['file']) && $_FILES['file']['error'] != UPLOAD_ERR_NO_FILE) {

				$fileError = Upload::checkUpload('file');
				errorPage($fileError);

				$support = Upload::store('file', Config::get('path-docs-cours'));
			}

			if ($support) {
				$files = $cours->get('Files') ? explode(',', $cours->get('Files')) : array();
				$files[] = $support;
				$cours->set('Files', implode(',', $files))->save();
			}

			$result['redirect'] = URL::admin('cours/view/' . $cours->get('ID'));


			sendResult($result);

		case 'copie-examen':

			$result = array();
			$note = new Models\Note($_POST['note']);

			if (isset($_FILES['file']) && $_FILES['file']['error'] != UPLOAD_ERR_NO_FILE) {

				$fileError = Upload::checkUpload('file');
				errorPage($fileError);

				if ($note->get('Copie'))
					Upload::delete(_basepath . Config::get('path-docs-examens') . $note->get('Copie'));

				$note->set('Copie', Upload::store('file', Config::get('path-docs-examens')));
				$note->save();
			}
			ob_start();
		?>
			<a href="<?php echo $note->getCopieLink() ?>" download="<?php echo $note->getCopieName() ?>" class="btn btn-boti btn-sm" style="padding: 10px 20px;">
				<i class="fa fa-download"></i> Télécharger la copie
				</button>
			</a>
			<a class="btn btn-boti btn-danger btn-sm file-delete-copie-examen" style="padding: 10px 20px;" data-note="<?php echo $note->get('ID') ?>">
				<i class="fa fa-times"></i> Supprimer
				</button>
			</a>
		<?php
			$html = ob_get_contents();
			ob_clean();

			$result['html'] = $html;
			$result['selector_add'] = '.copie_exam_' . $note->get('ID');
			$result['selector_delete'] = '.upload_input_file_' . $note->get('ID');


			sendResult($result);

		case 'delete-copie-examen':

			$result = array();
			$note = new Models\Note($_POST['note']);

			if ($note->get('Copie'))
				Upload::delete(_basepath . Config::get('path-docs-examens') . $note->get('Copie'));

			$note->set('Copie', null)->save();

			ob_start();
		?>
			<div class="upload_input_file_<?php echo $note->get('ID') ?>">
				<div class="mdl-shadow--6dp" style="font-size: 11px; padding: 6px 10px;    display: inline-block;">
					<div class="file-upload mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
						<span>Joindre la copie de l'examen</span>
						<input type="file" class="upload image file-upload-copie-examen" data-note="<?php echo $note->get('ID') ?>" />
					</div>
				</div>
				<div class="" style="display: inline-block;">
					<span id="file_name" style="font-size: 11px;"></span>
				</div>
			</div>
			<?php
			$html = ob_get_contents();
			ob_clean();

			$result['html'] = $html;
			$result['selector_add'] = '.copie_exam_' . $note->get('ID');


			sendResult($result);

		case 'absences-discipline-delete':
			$action = new Models\DisciplineAction($_POST['action']);

			$action->delete();

			$actionsData = Models\DisciplineAction::getEleveActionData($action->get('Inscription'), $action->get('DateAction'));

			sendResult(array(
				'idAction' => $action->get('ID'),
				'idInscription' => $action->get('Inscription')->get('ID'),
				'actions' => $actionsData
			));

		case 'inscription-pact':

			$promotion = Models\Promotion::promotion_actuelle();

			$teLParent = str_replace("-", "", $_POST['phone']);
			$teLParent = str_replace(".", "", $teLParent);
			$emailParent = $_POST['email'];

			$userPapa = new Models\User();
			$emailExists = $userPapa->emailExists($emailParent);
			$telExists = $userPapa->telExists($teLParent);

			if ($emailExists) {
				$userPapa = new Models\User($emailExists);
				$papa = $userPapa->getParent();
				if (!$papa) {
					$papa = new Models\Parentt();
					$papa
						->set('User', $userPapa)
						->save();
				}
			} elseif ($telExists) {
				$userPapa = new Models\User($telExists);
				$papa = $userPapa->getParent();
				if (!$papa) {
					$papa = new Models\Parentt();
					$papa
						->set('User', $userPapa)
						->save();
				}
			} else {

				$papa = new Models\Parentt();
				$userPapa = new Models\User();
				$userPapa
					->set('Key', \Tools::getRandChars(30, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'))
					->set('Role', 2)
					->set('Homme', ($_POST['parrainage'] == 'homme') ? true : false)
					->set('Adresse', '')
					->set('Nom', strtoupper($_POST['nom']))
					->set('Prenom', ucfirst(strtolower($_POST['prenom'])))
					->set('Tel', preg_replace('/\s+/', '', $teLParent))
					->set('Email', $emailParent)
					->set('Enabled', true)
					->save();
				$papa
					->set('User', $userPapa)
					->save();
			}

			foreach ($_POST['enfants'] as $item) {

				$utilisateur = new Models\User();
				$utilisateur
					->set('Key', \Tools::getRandChars(30, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'))
					->set('Role', 3)
					->set('Homme', (isset($item['homme']) && $item[0] == 'homme') ? true : false)
					->set('Nom', strtoupper($item['nom']))
					->set('Prenom', ucfirst(strtolower($item['prenom'])))
					->set('DateNaissance', null)
					->set('Adresse', '')
					->set('Enabled', true)
					->save();

				$eleve = new Models\Eleve();
				$eleve
					->set('User', $utilisateur)
					->set('PrenomAr', null)
					->set('CNE', null)
					->set('NomAr', null)
					->save();


				try {
					$niveau = new Models\Niveau($item['niveau']);
				} catch (Exception $e) {
					$niveau = null;
				}

				$inscription = new Models\Inscription();
				$inscription
					->set('Eleve', $eleve)
					->set('Promotion', $promotion)
					->set('Niveau', $niveau)
					->save();

				$parrainage = new Models\Parrainage();
				$parrainage
					->set('Eleve', $eleve)
					->set('Type', ($_POST['parrainage'] == 'homme') ? 1 : 2)
					->set('Parent', $papa)
					->save();
			}

			sendResult(array());

		default:
			sendError('Opération demandé invalide', 14);
	}
} else {
	if (!isset($_GET['op']))
		sendError('Aucune opération demandée', 11);
	switch ($_GET['op']) {
		case 'select-pointage-eleves':
			$result = array();
			$html = '';
			$relances = '';

			try {
				$eleve = new Models\Eleve($_GET['eleve']);
			} catch (Exception $e) {
				$eleve = null;
			}

			$inscription = $eleve->getInscription();

			if ($eleve && $inscription->get('Classe')) {

				$dateNow = date('H:i');

				$periode = '';
				if ($dateNow < Models\Seance::matinHeureFin()) {

					$periode = 'matin';
				} else {

					$periode = 'apres-midi';
				}

				$presences = Models\Presence::getList(array('where' => array(
					'Inscription' => $inscription->get('ID'),
					'Periode' => $periode,
					'Date LIKE \'' . date('Y-m-d') . '%\'',
				)));

				ob_start();
			?>
				<div class="text-center">
					<div class="user-picture" style="display: inline; text-align: left;">
						<div>
							<a href="<?php echo URL::admin('eleves/' . $eleve->get('ID')) ?>" target="_blank">
								<img class="img-circle" src="<?php echo $eleve->get('User')->getImage() ?>" class="img-responsive" alt="">
							</a>
						</div>
						<div>
							<p>
								<?php echo $eleve->get('User')->getNomComplet() ?>
								<br />
								<span class="tag tag-sm tag-info"><?php echo $inscription->get('Classe')->get('Label') ?></span>
							</p>

						</div>
					</div>
					<br />
					<br />
					<?php if (!$presences) { ?>
						<button class="btn btn-boti uppercase" data-pointage-eleve="<?php echo $eleve->get('ID') ?>">Marquer la présence</button>
					<?php } else { ?>
						<span style="text-align: center; display: block;">Présence marquée : <?php echo Tools::dateFormat($presences[0]->get('Date'), '%H:%M') ?></span>
					<?php } ?>
				</div>
				<br />
				<br />
			<?php
				$html = ob_get_contents();
				ob_clean();
			}

			$result['html'] = $html;

			sendResult($result);

		case 'pointage-eleve':
			$result = array();
			$html = '';
			$relances = '';

			try {
				$eleve = new Models\Eleve($_GET['eleve']);
			} catch (Exception $e) {
				$eleve = null;
			}

			if ($eleve) {

				$inscription = $eleve->getInscription();

				$where = array();
				$where[] = 'Date LIKE \'' . date('Y-m-d') . '\'';
				if ($inscription->get('Classe'))
					$where['Classe'] = $inscription->get('Classe')->get('ID');

				$dateNow = date('H:i');

				$periode = '';
				if ($dateNow < Models\Seance::matinHeureFin()) {

					$where[] = 'J1Periode LIKE \'%matin%\'';
					$periode = 'matin';
				} else {

					$periode = 'apres-midi';
					$where[] = 'J1Periode LIKE \'%apres-midi%\'';
				}


				$cours = array();
				if ($inscription->get('Classe')) {

					$cours = Models\Cours::getList(
						array('where' => $where),
						Models\Cours::sqlQuery(true) . <<<END
		JOIN (SELECT `ID` AS `J1ID`, `Periode` AS `J1Periode` FROM `sco_seances`) AS `j1` ON `sco_cours`.`Seance` = `j1`.`J1ID`
END
					);
				}

				$coursIds = array();
				foreach ($cours as $item) {
					$coursIds[] = $item->get('ID');
				}

				if ($coursIds) {

					$presences = Models\Presence::getCount(array('where' => array(
						'Inscription' => $inscription->get('ID'),
						'Periode' => $periode,
						'Date LIKE \'' . date('Y-m-d') . '%\'',
					)));

					if (!$presences) {

						$presence = new Models\Presence();
						$presence
							->set('Inscription', $inscription)
							->set('Cours', implode(',', $coursIds))
							->set('Periode', $periode)
							->set('UserBy', Session::getInstance()->getCurUser())
							->set('Date', date('Y-m-d H:i:s'))
							->save();
					}
				}
			}

			$result['msg'] = 'Présence marquée..';
			sendResult($result);

		case 'pointage':
			$result = array();
			$bloque = false;
			$nonInscrit = false;
			$withoutClasse = false;
			$cours = null;
			$inscriptions = null;

			$eleveId = substr($_GET['idEleve'], 0, -5);

			try {
				$eleve = new Models\Eleve(strtolower($eleveId));
			} catch (Exception $e) {
				$nonInscrit = true;
				$eleve = null;
			}

			$inscription = null;
			if ($eleve)
				$inscription = $eleve->getInscription();

			if (!$inscription)
				$nonInscrit = true;
			else {

				if (!$inscription->get('Eleve')->get('User')->get('Enabled'))
					$bloque = true;

				$where = array();
				$where[] = 'Date LIKE \'' . date('Y-m-d') . '\'';
				if ($inscription->get('Classe'))
					$where['Classe'] = $inscription->get('Classe')->get('ID');

				$dateNow = date('H:i');

				$periode = '';
				if ($dateNow < Models\Seance::matinHeureFin()) {

					$where[] = 'J1Periode LIKE \'%matin%\'';
					$periode = 'matin';
				} else {

					$periode = 'apres-midi';
					$where[] = 'J1Periode LIKE \'%apres-midi%\'';
				}

				$cours = array();
				if ($inscription->get('Classe')) {

					$cours = Models\Cours::getList(
						array('where' => $where),
						Models\Cours::sqlQuery(true) . <<<END
	JOIN (SELECT `ID` AS `J1ID`, `Periode` AS `J1Periode` FROM `sco_seances`) AS `j1` ON `sco_cours`.`Seance` = `j1`.`J1ID`
END
					);
				} else
					$withoutClasse = true;

				$coursIds = array();
				foreach ($cours as $item) {
					$coursIds[] = $item->get('ID');
				}

				if ($coursIds) {

					$presences = Models\Presence::getCount(array('where' => array(
						'Inscription' => $inscription->get('ID'),
						'Periode' => $periode,
						'Date LIKE \'' . date('Y-m-d') . '%\'',
					)));

					if (!$presences) {

						$presence = new Models\Presence();
						$presence
							->set('Inscription', $inscription)
							->set('Cours', implode(',', $coursIds))
							->set('Periode', $periode)
							->set('UserBy', Session::getInstance()->getCurUser())
							->set('Date', date('Y-m-d H:i:s'))
							->save();
					}
				}
			}

			$result['msg'] = 'Merci ' . $inscription->get('Eleve')->get('User')->getNomComplet();

			sendResult($result);

		case 'pointage-collaborateur':
			$result = array();

			$userId = hash_decode(strtolower(substr($_GET['idUser'], 0, -1)), 7);

			try {
				$user = new Models\User($userId);
			} catch (Exception $e) {
				$user = null;
			}

			$pointage = new Models\StaffPointage();
			$pointage
				->set('Date', date('Y-m-d H:i:s'))
				->set('User', $user)
				->save();

			$result['msg'] = 'Merci ' . $user->getNomComplet();

			sendResult($result);

		case 'generate-alias':

			$result = array();

			$label = $_GET['label'];

			$result['alias'] =  Tools::getAlias($label);

			sendResult($result);

		case 'checkup':

			$result = array();



			$result['retards'] =  array(
				'total' => 499,
				'classe' => 'success',
				'variation' => '<i class="fa fa-caret-up"></i> <span>' . \Tools::numberFormat(909, 2) . '%</span>',
			);

			$result['absences'] =  array(
				'total' => 800,
				'classe' => 'success',
				'variation' => '<i class="fa fa-caret-up"></i> <span>' . \Tools::numberFormat(800, 2) . '%</span>',
			);

			$result['warningHtml'] = "HTML";
			sendResult($result);

		case 'dashboard-pedagogique':

			$result = array();

			$periode = $_GET['periode'];

			$periodeExp =  explode(' - ', $periode);
			$where = array();
			$where[] = 'Date BETWEEN \'' . $periodeExp[0] . '\' AND \'' . $periodeExp[1] . '\'';
			$where[] = '(Retards IS NULL OR Retards = 0)';

			$earlier = new DateTime($periodeExp[0]);
			$later = new DateTime($periodeExp[1]);
			$diffDay = $later->diff($earlier)->format("%a");
			$diffDay++;


			$absences = Models\Absence::getCount(array('where' => $where, 'order' => array('Date' => false)));
			$retards = Models\Absence::minRetardsParPeriode($periodeExp);


			// PP = PERIODE PRECEDANTE
			$dateStartPP = clone $earlier;
			$dateEndPP = clone $earlier;
			$dateEndPP->sub(new DateInterval('P1D'));
			$dateStartPP->sub(new DateInterval('P' . $diffDay . 'D'));

			if (!isset($_GET['samePeriode'])) {
				$where = array();
				$where[] = 'Date BETWEEN \'' . Tools::dateFormat($dateStartPP, '%Y-%m-%d') . '\' AND \'' . Tools::dateFormat($dateEndPP, '%Y-%m-%d') . '\'';
				$where[] = '(Retards IS NULL OR Retards = 0)';

				$absencesPP = Models\Absence::getCount(array('where' => $where, 'order' => array('Date' => false)));
				$retardsPP = Models\Absence::minRetardsParPeriode(array(Tools::dateFormat($dateStartPP, '%Y-%m-%d'), Tools::dateFormat($dateEndPP, '%Y-%m-%d')));


				$variationRetards = 100;
				if ($retardsPP > 0) {
					$variationRetards = (($retardsPP - $retards) * 100) / $retardsPP;
				}
				if ($variationRetards < 0)
					$variationRetards = $variationRetards * -1;

				$variationAbsences = 100;
				if ($absencesPP > 0) {
					$variationAbsences = (($absencesPP - $absences) * 100) / $absencesPP;
				}
				if ($variationAbsences < 0)
					$variationAbsences = $variationAbsences * -1;

				$result['retards'] =  array(
					'total' => $retards,
					'classe' => ($retards > $retardsPP) ? 'warning' : 'success',
					'variation' => ($retards > $retardsPP) ? '<i class="fa fa-caret-up"></i> <span>' . \Tools::numberFormat($variationRetards, 2) . '%</span>' : '<i class="fa fa-caret-down"></i> <span>' . \Tools::numberFormat($variationRetards, 2) . '%</span>',
				);

				$result['absences'] =  array(
					'total' => $absences,
					'classe' => ($absences > $absencesPP) ? 'warning' : 'success',
					'variation' => ($absences > $absencesPP) ? '<i class="fa fa-caret-up"></i> <span>' . \Tools::numberFormat($variationAbsences, 2) . '%</span>' : '<i class="fa fa-caret-down"></i> <span>' . \Tools::numberFormat($variationAbsences, 2) . '%</span>',
				);
			}

			if (isset($_GET['niveau'])) {
				try {
					$niveau = new Models\Niveau($_GET['niveau']);
				} catch (Exception $e) {
					$niveau = null;
				}

				$absencesQuery = Models\Absence::sqlQueryCount(true) . <<<END
	JOIN (SELECT `ID` AS `J1ID`, `Classe` AS `J1Classe` FROM `sco_seance_tracking`) AS `j1` ON `sco_absences`.`Cours` = `j1`.`J1ID`
END;
				$devoirQuery = Models\PostClasse::sqlQueryCount(true) . <<<END
		JOIN (SELECT `ID` AS `J1ID`, `PostCategorie` AS `J1PostCategorie`, `PostFormat` AS `J1PostFormat`, `DatePublication` AS `J1DatePublication`, `DateExpiration` AS `J1DateExpiration`, `Home` AS `J1Home`, `Visible` AS `J1Visible`, `Date` AS `J1Date` FROM `com_posts`) AS `j1` ON `com_postclasses`.`Post` = `j1`.`J1ID`
END;
				$connexionQuery = Models\Connexion::sqlQueryCount(true) . <<<END
		JOIN (SELECT `ID` AS `J1ID`,  `User` AS `J1User` FROM `parents`) AS `j1` ON `connexions`.`User` = `j1`.`J1User`
		JOIN (SELECT  `Parent` AS `J2Parent`,  `Eleve` AS `J2Eleve` FROM `parrainages`) AS `j2` ON `j1`.`J1ID` = `j2`.`J2Parent`
		JOIN (SELECT `ID` AS `J3ID`,  `Classe` AS `J3Classe`,  `Eleve` AS `J3Eleve` FROM `ins_inscriptions`) AS `j3` ON `j2`.`J2Eleve` = `j3`.`J3Eleve`
END;
				$classesInfos = array();
				$promotion = Models\Promotion::promotion_actuelle();
				$classes = Models\Classe::getList(array('where' => array('Niveau' => $niveau->get('ID'), 'Promotion' => $promotion->get('ID'))));
				foreach ($classes as $classe) {
					$classeInfos = array();
					$classeInfos['classe'] = $classe;

					// Absences - Variation
					$where = array();
					$where[] = 'Date BETWEEN \'' . $periodeExp[0] . '\' AND \'' . $periodeExp[1] . '\'';
					$where[] = '(Retards IS NULL OR Retards = 0)';
					$where['J1Classe'] = $classe->get('ID');
					$absences = Models\Absence::getCount(array('where' => $where, 'order' => array('Date' => false)), $absencesQuery);


					$where = array();
					$where[] = 'Date BETWEEN \'' . Tools::dateFormat($dateStartPP, '%Y-%m-%d') . '\' AND \'' . Tools::dateFormat($dateEndPP, '%Y-%m-%d') . '\'';
					$where[] = '(Retards IS NULL OR Retards = 0)';
					$where['J1Classe'] = $classe->get('ID');
					$absencesPP = Models\Absence::getCount(array('where' => $where, 'order' => array('Date' => false)), $absencesQuery);

					$variationAbsences = 100;
					if ($absencesPP > 0) {
						$variationAbsences = (($absencesPP - $absences) * 100) / $absencesPP;
					}
					if ($variationAbsences < 0)
						$variationAbsences = $variationAbsences * -1;

					$classeInfos['absences'] =  array(
						'total' => $absences ? $absences : 0,
						'total_pp' => $absencesPP ? $absencesPP : 0,
						'variation' => $variationAbsences,
						'positive' => ($absences > $absencesPP) ? false : true,
					);


					// Devoirs - Variation
					$where = array();
					$where['J1Visible'] = true;
					$where[] = 'J1Date BETWEEN \'' . $periodeExp[0] . '\' AND \'' . $periodeExp[1] . '\'';
					$where['Classe'] = $classe->get('ID');
					$postsClasse = Models\PostClasse::getCount(array('where' => $where, 'order' => array('J1DatePublication' => false)), $devoirQuery);

					$where = array();
					$where['J1Visible'] = true;
					$where[] = 'J1Date BETWEEN \'' . Tools::dateFormat($dateStartPP, '%Y-%m-%d') . '\' AND \'' . Tools::dateFormat($dateEndPP, '%Y-%m-%d') . '\'';
					$where['Classe'] = $classe->get('ID');
					$postsClassePP = Models\PostClasse::getCount(array('where' => $where, 'order' => array('J1DatePublication' => false)), $devoirQuery);

					$variationDevoirs = 100;
					if ($postsClassePP > 0) {
						$variationDevoirs = (($postsClassePP - $postsClasse) * 100) / $postsClassePP;
					}
					if ($variationDevoirs < 0)
						$variationDevoirs = $variationDevoirs * -1;

					$classeInfos['devoirs'] =  array(
						'total' => $postsClasse ? $postsClasse : 0,
						'total_pp' => $postsClassePP ? $postsClassePP : 0,
						'variation' => $variationDevoirs,
						'positive' => ($postsClasse > $postsClassePP) ? true : false,
					);


					// Connexions - Variation
					$where = array();
					$where[] = 'Date BETWEEN \'' . $periodeExp[0] . ' 00:00 \' AND \'' . $periodeExp[1] . ' 23:59\'';
					$where['J3Classe'] = $classe->get('ID');
					$connexions = Models\Connexion::getCount(array('where' => $where), $connexionQuery);
					$where = array();
					$where[] = 'Date BETWEEN \'' . Tools::dateFormat($dateStartPP, '%Y-%m-%d') . ' 00:00\' AND \'' . Tools::dateFormat($dateEndPP, '%Y-%m-%d') . ' 23:59\'';
					$where['J3Classe'] = $classe->get('ID');
					$connexionsPP = Models\Connexion::getCount(array('where' => $where), $connexionQuery);

					$variationConnexions = 100;
					if ($connexionsPP > 0) {
						$variationConnexions = (($connexionsPP - $connexions) * 100) / $connexionsPP;
					}
					if ($variationConnexions < 0)
						$variationConnexions = $variationConnexions * -1;

					$classeInfos['connexions'] =  array(
						'total' => $connexions ? $connexions : 0,
						'total_pp' => $connexionsPP ? $connexionsPP : 0,
						'variation' => $variationConnexions,
						'positive' => ($connexions > $connexionsPP) ? true : false,
					);

					$classeInfos['topAbsences'] = Models\Absence::maxAbsence($classe, $periodeExp);

					$classesInfos[] = $classeInfos;
				}

				ob_start();
				include _basepath . Config::get('admin') . '/pages/dashboard-pedagogique/analytics-niveau.php';
				$html = ob_get_contents();
				ob_clean();

				$result['niveauHtml'] = $html;
				$result['selectorNiveau'] = '.tab-niveau-' . $niveau->get('ID');
			}



			ob_start();
			$topAbsence = Models\Absence::maxAbsence(null, $periodeExp);
			$topRetard = Models\Absence::maxRetard(null, $periodeExp);
			include _basepath . Config::get('admin') . '/pages/dashboard-pedagogique/analytics-warning.php';
			$warningHtml = ob_get_contents();
			ob_clean();

			$result['warningHtml'] = $warningHtml;

			sendResult($result);

		case 'classe-eleves':
			$result = array();

			try {
				$classe = new Models\Classe($_GET['classe']);
			} catch (Exception $e) {
				$classe = null;
			}

			$inscriptions  = array();

			if ($classe)
				$inscriptions = Models\Inscription::getList(array('where' => array(
					'Classe' => $classe->get('ID'),
				)));

			$result = array();
			$result['inscriptions'] = array();
			foreach ($inscriptions as $inscription) {
				$result['inscriptions'][] = array(
					'id' => $inscription->get('Eleve')->get('ID'),
					'label' => $inscription->get('Eleve')->get('User')->getNomComplet(),
				);
			}

			$result['classe'] = array(
				'id' => $classe->get('ID'),
				'label' => 'Affecter un responsable à la classe ' . $classe->get('Label') . ' Resp :' . ($classe->get('Responsable') ? $classe->get('Responsable')->get('User')->getNomComplet() : '-'),
			);

			sendResult($result);

		case 'eleve-key-search-input':


			$nomAlias = strtolower($_GET['nom']);
			$eleves = array();
			if ($nomAlias) {
				$eleves = Models\Eleve::getList(
					array('where' => array(
						'(LOWER(Massar) LIKE \'%' . $nomAlias . '%\' OR LOWER(Matricule) LIKE \'%' . $nomAlias . '%\' OR LOWER(J3Nom) LIKE \'%' . $nomAlias . '%\' OR LOWER(`J3Prenom`) LIKE \'%' . $nomAlias . '%\' OR CONCAT(LOWER(`J3Prenom`),\' \',LOWER(`J3Nom`)) LIKE \'%' . $nomAlias . '%\' OR CONCAT(LOWER(`J3Nom`),\' \',LOWER(`J3Prenom`)) LIKE \'%' . $nomAlias . '%\')'
					)),
					Models\Eleve::sqlQuery(true) . <<<END
	JOIN (SELECT `ID` AS `J1ID`, LOWER(`Nom`) AS J3Prenom, LOWER(`Prenom`) AS J3Nom FROM `users`) AS `j1` ON `gen_eleves`.`User` = `j1`.`J1ID`
END
				);
			}

			$html = null;
			if ($eleves) {
				ob_start();
				include _basepath . Config::get('admin') . '/pages/eleves/eleves-search-result.php';
				$html = ob_get_contents();
				ob_clean();

				$result = array(
					'html' => $html,
				);
			} else
				$result = array(
					'html' => null,
				);

			sendResult($result);

		case 'eleve-modal-infos':

			try {
				$eleve = new Models\Eleve($_GET['eleve']);
			} catch (Exception $e) {
				$eleve = null;
			}

			$inscription = $eleve->getInscription();
			if (!$inscription)
				$inscription = $eleve->getInscriptionProchaine();


			$parrainages = Models\Parrainage::getList(array('where' => array('Eleve' => $eleve->get('ID'))));

			$html = null;
			if ($eleve) {
				ob_start();
				include _basepath . Config::get('admin') . '/pages/eleves/eleves-eleve-modal.php';
				$html = ob_get_contents();
				ob_clean();

				$result = array(
					'html' => $html,
				);
			} else
				$result = array(
					'html' => null,
				);

			sendResult($result);

		case 'responsable-classe-infos':

			try {
				$eleve = new Models\Eleve($_GET['eleve']);
			} catch (Exception $e) {
				$eleve = null;
			}

			if ($eleve) {
				ob_start();
			?>
				<img src="<?php echo $eleve->get('User')->getImage() ?>" style="width:60px;height:60px;" class="img-responsive img-circle" alt="">
				<p>
					<?php echo $eleve->get('User')->getNomComplet() ?><br />
					<small>Né(e) le <?php echo Tools::dateFormat($eleve->get('User')->get('DateNaissance')) ?></small>
				</p>
			<?php
				$html = ob_get_contents();
				ob_clean();

				$result = array(
					'html' => $html,
				);
			} else
				$result = array(
					'html' => null,
				);

			sendResult($result);

		case 'timeline-details':

			$result = array();

			try {
				$post = new Models\Post($_GET['post']);
			} catch (Exception $e) {
				sendResult($result);
			}

			if ($post) {
				ob_start();
			?>
				<div class="row">
					<div class="col-sm-12">
						<h3 class="text-uppercase"><?php echo $post->get('Label') ?></h3>
						<?php echo $post->getContent() ?>
					</div>
				</div>
			<?php
				$html = ob_get_contents();
				ob_clean();

				$result = array(
					'html' => $html,
				);
			} else
				$result = array(
					'html' => null,
				);

			sendResult($result);

		case 'get-promotion-classes':
			$result = array();

			try {
				$promotion = new Models\Promotion($_GET['promotion']);
			} catch (Exception $e) {
				$promotion = null;
			}

			$classes  = array();

			if ($promotion)
				$classes = Models\Classe::getList(array('where' => array(
					'Promotion' => $promotion->get('ID'),
				)));

			$result = array();
			$result['classes'] = array();
			foreach ($classes as $classe) {
				$result['classes'][] = array(
					'id' => $classe->get('ID'),
					'label' => $classe->get('Label'),
				);
			}

			sendResult($result);

		case 'recu-promotion':
			$result = array();

			try {
				$promotion = new Models\Promotion($_GET['promotion']);
			} catch (Exception $e) {
				$promotion = null;
			}

			$result = array();
			$result['recu'] = $promotion->recuEncaissement();

			sendResult($result);


		case 'get-eleve-promotions':

			$result = array();

			try {
				$eleve = new Models\Eleve($_GET['eleve']);
			} catch (Exception $e) {
				$eleve = null;
			}

			$inscriptions  = array();
			//$grille = Models\FIN\RubriquePrice::grille();


			if ($eleve)
				$inscriptions = Models\Inscription::getList(array('where' => array(
					'Eleve' => $eleve->get('ID'),
				)));

			$result = array();
			$result['promotions'] = array();
			foreach ($inscriptions as $inscription) {
				$result['promotions'][] = array(
					'id' => $inscription->get('Promotion')->get('ID'),
					'label' => $inscription->get('Promotion')->get('Label'),
				);
			}
			$months_list = array(9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre', 1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril', 5 => 'Mai', 6 => 'Juin');
			$inscription = $eleve->getInscription();
			if (!$inscription) {
				$inscription = 	$eleve->getInscriptionProchaine();
			}
			$promotion =  $inscription->get('Promotion');
			$inscription_id =  $inscription->get('ID');
			$niveau =  $inscription->get('Niveau');

			$services  = $inscription->services();

			$service_annules = array_filter($services, function ($item) {
				return !$item->get('Mensuel');
			});

			$service_mensuels = array_filter($services, function ($item) {
				return $item->get('Mensuel');
			});
			ob_start();

			?>
			<div class="card">
				<img src="<?php echo $eleve->get('User')->getSmallImage() ?>" class="img-circle img-responsive" style="width: 100px; margin: auto; display: block;" />
				<h3 style="font-weight: 700; text-align: center; margin-top: 10px; font-size: 16px;"><?php echo $eleve->get('User')->getNomComplet() ?></h3>
				<div style="display: flex;justify-content: center;">
					<div class="tag tag-primary pull-right tag-montant"> <?php echo $promotion->get('Label'); ?></div>
				</div>
				<br />
				<h3 style="font-weight: 700; margin:5px; font-size: 16px;">Frais annuels :</h3>
				<?php foreach ($service_annules  as $s) { ?>
					<div class="list-group-item" style="border:none;">
						<div class="media">
							<div class="media-left">
								<i class="fa fa-<?php echo $s->get('Icone') ?> font-medium-2"></i>
							</div>
							<div class="media-body">
								<h6 class="media-heading mb-0">
									<?php echo $s->get('Label') ?>
									<b><?php
										// default montant
										// $service_amount =  $s->get('MontantDefaut');
										// $rubriquePrice = null;
										// if (isset($grille[$niveau->get('ID')]) && isset($grille[$niveau->get('ID')][$s->get('ID')])) {
										// 	$rubriquePrice  = $grille[$niveau->get('ID')][$s->get('ID')];
										// }

										// //  get the montant from grille
										// if ($rubriquePrice) {
										// 	$service_amount = $rubriquePrice->get('Frais');
										// }
										// echo $service_amount; 


										?> </b>
								</h6>
							</div>

						</div>
					</div>

				<?php } ?>
				<h3 style="font-weight: 700;margin:5px; font-size: 16px;">Frais mensuels :</h3>
				<?php foreach ($service_mensuels  as $s) {
					$monthService = $inscription->monthsOfService($s);;
				?>
					<div class="list-group-item" style="border:none;">
						<div class="media">
							<div class="media-left">
								<i class="fa fa-<?php echo $s->get('Icone') ?> font-medium-2"></i>
							</div>
							<div class="media-body">
								<h6 class="media-heading mb-0">
									<?php echo $s->get('Label') ?>
									<b>
										<?php
										// // default montant
										// $service_amount =  $s->get('MontantDefaut');
										// $rubriquePrice = null;
										// if (isset($grille[$niveau->get('ID')]) && isset($grille[$niveau->get('ID')][$s->get('ID')])) {
										// 	$rubriquePrice  = $grille[$niveau->get('ID')][$s->get('ID')];
										// }
										// //  get the montant from grille
										// if ($rubriquePrice) {
										// 	$service_amount = $rubriquePrice->get('Frais');
										// }
										// echo $service_amount;
										?>
									</b>
								</h6>
								<?php if ($s->get('Optionnel')) { ?>
									<?php foreach ($months_list as $key_month => $month) { ?>
										<span style="border-radius: 5px;text-align: center;color: #fff;margin:2px;padding: 1px;font-size: 11px;border: 1px solid <?php echo !$s->get('Optionnel') || in_array($key_month, $monthService) ? 'green' : 'red'; ?>;color:#fff;background:<?php echo !$s->get('Optionnel') || in_array($key_month, $monthService) ? 'green' : 'red'; ?>;"> <?php echo $key_month ?> </span>
									<?php } ?>
								<?php } ?>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>



		<?php
			$html = ob_get_contents();
			ob_clean();

			$result['html'] = $html;

			sendResult($result);

		case '--get-eleve-promotions':
			$result = array();

			try {
				$eleve = new Models\Eleve($_GET['eleve']);
			} catch (Exception $e) {
				$eleve = null;
			}

			$inscriptions  = array();

			if ($eleve)
				$inscriptions = Models\Inscription::getList(array('where' => array(
					'Eleve' => $eleve->get('ID'),
				)));

			$result = array();
			$result['promotions'] = array();
			foreach ($inscriptions as $inscription) {
				$result['promotions'][] = array(
					'id' => $inscription->get('Promotion')->get('ID'),
					'label' => $inscription->get('Promotion')->get('Label'),
				);
			}

			$inscription = $eleve->getInscription();

			$rubriques = Models\FIN\EncaissementRubriqueInscription::getList(array('where' => array('Inscription' => $inscription->get('ID'))));
			if (!$rubriques)
				$rubriquesGlob = Models\FIN\EncaissementRubrique::getList();

			ob_start();
		?>
			<img src="<?php echo $eleve->get('User')->getSmallImage() ?>" class="img-circle img-responsive" style="width: 100px; margin: auto; display: block;" />
			<h3 style="font-weight: 700; text-align: center; margin-top: 10px; font-size: 16px;"><?php echo $eleve->get('User')->getNomComplet() ?></h3>
			<br />
			<br />
			<?php if ($rubriques) { ?>
				<?php foreach ($rubriques as $item) { ?>
					<div class="list-group-item">
						<div class="media">
							<div class="media-left">
								<i class="fa fa-<?php echo $item->get('EncaissementRubrique')->get('Icone') ?> font-medium-2"></i>
							</div>
							<div class="media-body">
								<h6 class="media-heading mb-0">
									<?php echo $item->get('EncaissementRubrique')->get('Label') ?>
									<?php if ($item->get('EncaissementRubrique')->get('MontantDefaut') != $item->get('Montant')) { ?>
										<code><s><?php echo $item->get('EncaissementRubrique')->get('MontantDefaut') ?></s></code>
									<?php } ?>
									<b><?php echo $item->get('Montant') ?> DHS</b>
								</h6>
								<span class="font-small-2 pull-left"><?php echo $item->get('Remarques') ?></span>
							</div>
						</div>
					</div>
				<?php } ?>
			<?php } else { ?>
				<?php foreach ($rubriquesGlob as $item) { ?>
					<div class="list-group-item">
						<div class="media">
							<div class="media-left">
								<i class="fa fa-<?php echo $item->get('Icone') ?> font-medium-2"></i>
							</div>
							<div class="media-body">
								<h6 class="media-heading mb-0">
									<?php echo $item->get('Label') ?>
									<b><?php echo $item->get('MontantDefaut') ?> DHS</b>
								</h6>
							</div>
						</div>
					</div>
				<?php } ?>
			<?php } ?>
		<?php
			$html = ob_get_contents();
			ob_clean();

			$result['html'] = $html;

			sendResult($result);

		case 'edt-infos':
			$result = array();

			try {
				$seance = new Models\ETD\Seance($_GET['seance']);
			} catch (Exception $e) {
			}

			$edt = $seance->get('Edt');
			$classe = $edt->get('Classe');

			ob_start();
		?>
			<div class="cours-item-infos">
				<h3>
					<?php echo html($classe->get('Label') . ' - ' . $classe->get('Promotion')->get('Label')) ?>
				</h3>
				<h4> <?php echo html($seance->getLabel(true)) ?></h4>
				<span> <?php echo html($seance->get('Remarque')) ?></span>

				<div class="user-picture">
					<div>
						<div>
							<img class="img-circle" src="<?php echo $seance->get('Enseignant') ? $seance->get('Enseignant')->get('User')->getSmallImage() : $seance->get('Unite')->getIcone(true) ?>" alt="">
						</div>
					</div>
					<div>
						<p>
							<?php echo $seance->get('Enseignant') ? $seance->get('Enseignant')->get('User')->getNomComplet() : '' ?>
						</p>
					</div>
				</div>

				<ul class="infos">
					<li>
						<?php if ($seance->get('Online')) { ?>
							<span class=""><img src="<?php echo URL::base() ?>assets/mobile/cours_support/boti.png" alt="A distance"> A distance</span>
						<?php } else { ?>
							<span class=""><img src="<?php echo URL::base() ?>assets/icons/salle.svg" alt=""> <?php echo html($seance->get('Salle') ? $seance->get('Salle')->get('Label') : '-') ?></span>
						<?php } ?>
					</li>
					<li>
						<span class=""><img src="<?php echo URL::base() ?>assets/icons/clock_cours.svg" alt=""> <?php echo html($seance->getLabelHeure()) ?></span>
					</li>
				</ul>
				<?php if ($seance->get('Remarque')) {  ?>
					<span class="objectif"><span>Objectif de la séance : </span> </span>
					<?php echo html($seance->get('Remarque')) ?>
				<?php } ?>
				<a href="<?php echo URL::admin('cours/view/' . $seance->get('ID')) ?>" target="_blank" class="btn cours-link">Plus de détails</a>
			</div>
		<?php
			$html = ob_get_contents();
			ob_clean();

			$result['html'] = $html;

			sendResult($result);



		case 'cours-infos':
			$result = array();

			try {
				$cours = new Models\Cours($_GET['cours']);
			} catch (Exception $e) {
			}

			$classe = $cours->get('Classe');

			ob_start();
		?>
			<div class="cours-item-infos">
				<?php if ($cours->get('Annule')) { ?>
					<span class="statut"><img src="<?php echo URL::base() ?>assets/icons/calendar.svg" alt=""> Annulé</span>
				<?php } elseif ($cours->get('Valide')) { ?>
					<span class="statut"><img src="<?php echo URL::base() ?>assets/icons/calendar.svg" alt=""> Fait</span>
				<?php } elseif (!$cours->get('Valide') && $cours->get('Date') < date('Y-m-d')) { ?>
					<span class="statut"><img src="<?php echo URL::base() ?>assets/icons/calendar.svg" alt=""> Dépassé</span>
				<?php } elseif (!$cours->get('Valide')) { ?>
					<span class="statut"><img src="<?php echo URL::base() ?>assets/icons/calendar.svg" alt=""> Planifié</span>
				<?php } ?>
				<h3>
					<?php echo html($classe->get('Label') . ' - ' . $classe->get('Promotion')->get('Label')) ?>
				</h3>
				<h4> <?php echo html($cours->get('Matiere') ? $cours->get('Matiere')->get('Label') : '-') ?></h4>
				<span> <?php echo html($cours->get('Remarque')) ?></span>
				<div class="user-picture">
					<div>
						<div>
							<img class="img-circle" src="<?php echo $cours->get('Enseignant') ? $cours->get('Enseignant')->get('User')->getSmallImage() : '' ?>" alt="">
						</div>
					</div>
					<div>
						<p><?php echo $cours->get('Enseignant') ? $cours->get('Enseignant')->get('User')->getNomComplet() : '-' ?></p>
					</div>
				</div>
				<ul class="infos">
					<li>
						<?php if ($cours->get('MeetID')) { ?>
							<span class=""><img src="<?php echo URL::base() ?>assets/mobile/cours_support/boti.png" alt="A distance"> A distance</span>
						<?php } else { ?>
							<span class=""><img src="<?php echo URL::base() ?>assets/icons/salle.svg" alt=""> <?php echo html($cours->get('Salle') ? $cours->get('Salle')->get('Label') : '-') ?></span>
						<?php } ?>
					</li>
					<li>
						<span class=""><img src="<?php echo URL::base() ?>assets/icons/calendar.svg" alt=""> <?php echo html(Tools::dateFormat($cours->get('Date'), '%d/%m/%Y')) ?></span>
					</li>
					<li>
						<span class=""><img src="<?php echo URL::base() ?>assets/icons/clock_cours.svg" alt=""> <?php echo html($cours->getLabelHeure()) ?></span>
					</li>
				</ul>
				<?php if ($cours->get('Remarque')) {  ?>
					<span class="objectif"><span>Objectif de la séance : </span> </span>
					<?php echo html($cours->get('Remarque')) ?>
				<?php } ?>
				<a href="<?php echo URL::admin('cours/view/' . $cours->get('ID')) ?>" target="_blank" class="btn cours-link">Plus de détails</a>
			</div>
<?php
			$html = ob_get_contents();
			ob_clean();

			$result['html'] = $html;

			sendResult($result);

		case 'form-check-cours':
			$result = array();

			$whereGlob = array();
			$whereGlob['Date'] = $_GET['date'];
			$whereGlob['Seance'] = $_GET['seance'];

			if (isset($_GET['id']) && $_GET['id'])
				$whereGlob[] = 'ID != ' . $_GET['id'];

			$where = $whereGlob;
			$where['Salle'] = $_GET['salle'];
			$checkSalle = 0;
			if ($_GET['salle'])
				$checkSalle = Models\Cours::getCount(array('where' => $where));
			$result['checkSalle'] = ($checkSalle > 0) ? true : false;


			$where = $whereGlob;
			$where['Enseignant'] = $_GET['enseignant'];
			$checkEnseignant = 0;
			if ($_GET['enseignant'])
				$checkEnseignant = Models\Cours::getCount(array('where' => $where));
			$result['checkEnseignant'] = ($checkEnseignant > 0) ? true : false;

			$where = $whereGlob;
			$where['Classe'] = $_GET['classe'];
			$checkClasse = 0;
			if ($_GET['classe'])
				$checkClasse = Models\Cours::getCount(array('where' => $where));
			$result['checkClasse'] = ($checkClasse > 0) ? true : false;

			$checkHoliday = Models\Holiday::getCount(array('where' => array('\'' . $_GET['date'] . '\' BETWEEN DateDebut AND DateFin')));
			$result['checkHoliday'] = ($checkHoliday > 0) ? true : false;

			sendResult($result);

		case 'inscriptions-needs':
			$result = array();

			$cycles = Models\Cycle::getList(array('order' => array('ID' => true)));
			foreach ($cycles as $cycle) {
				$cycleResult = array();
				$cycleResult['id'] = $cycle->get('ID');
				$cycleResult['label'] = $cycle->get('Label');
				$niveaux = Models\Niveau::getList(array('where' => array('Cycle' => $cycle->get('ID')), 'order' => array('ID' => true)));
				foreach ($niveaux as $niveau) {
					$cycleResult['niveaux'][] = array(
						'id' => $niveau->get('ID'),
						'label' => $niveau->get('Label'),
					);
				}

				$result['cycles'][] = $cycleResult;
			}

			sendResult($result);

		default:
			sendError('Opération demandé invalide', 12);
	}
}

sendError('Aucune opération demandé', 10);
