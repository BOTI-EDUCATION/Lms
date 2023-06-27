<?php

header('Access-Control-Allow-Origin: *');

use Models\Enseignant;
use Models\LMS\Lecons;
use Models\LMS\Ressource;
use Models\LMS\RessourceContent;
use Models\LMS\Tracking;
use Models\LMS\TrackingHistory;
use Models\Role;
use Models\User;

/**
 * Controller Class 
 */

class ContentController
{



	function dashboard()
	{
		$leconsCount = count(Models\LMS\Lecons::getList());
		$ensCount =  count(Models\Enseignant::getList());
		$resCount =  count(Models\LMS\Ressource::getList());
		$countLeconByUnite =  DB::reader("SELECT sco_unites.Label , COUNT(lms_lecons.ID) 'lec' FROM lms_lecons INNER JOIN sco_unites on lms_lecons.Unite = sco_unites.ID GROUP BY sco_unites.Label ");
		$countLeconByNiveau =  DB::reader("SELECT gen_niveaux.Label , COUNT(lms_lecons.ID) 'lec' FROM lms_lecons INNER JOIN gen_niveaux on lms_lecons.Niveau = gen_niveaux.ID GROUP BY gen_niveaux.Label");
		$countLeconByUniteLabels = [];
		$countLeconByUniteValues = [];
		$countLeconByNivLabels = [];
		$countLeconByNivValues = [];
		foreach ($countLeconByNiveau as $cl) {
			$countLeconByNivLabels[] = $cl['Label'];
			$countLeconByNivValues[] = $cl['lec'];
		}
		foreach ($countLeconByUnite as $cl) {
			$countLeconByUniteLabels[] = $cl['Label'];
			$countLeconByUniteValues[] = $cl['lec'];
		}

		return sendResponse([
			'leconsCount' => $leconsCount,
			'ensCount' => $ensCount,
			'resCount' => $resCount,
			'uL' => $countLeconByUniteLabels,
			'uV' => $countLeconByUniteValues,
			'cL' => $countLeconByNivLabels,
			'cV' => $countLeconByNivValues,
			'charts' => [],
			'colis_count' => [],
			'ca_chiffres' => [],
			'document_count' => [],
			'actualites' => [],
		]);
	}

	function init()
	{
		$user = Session::getInstance()->getCurUser();
		return sendResponse([
			'user' => [
				'id' => $user->ID,
				'full_name' => $user->getNomComplet(),
				'image' => $user->getImage(),
				'role' => $user->get('Role')->Alias,
			],
			'school' => [
				'logo' => (Config::get('logo') ? URL::absolute(URL::base('assets/images/schools/' . Config::get('logo'))) : URL::absolute(URL::base('assets/images/logo.92874874.png'))),
				'school_name' => Config::get('nom_ecole'),

			]
		]);
	}
	public function validation($data)
	{
		// || !json_decode($data['content'])[0]->content
		// echo '<pre>';
		// print_r($data);
		// echo '</pre>';
		// exit;

		$result = null;
		if (isset($data['posts']['type_id']) && $data['posts']['type_id']) {
			if ($data['posts']['type_id'] == 1) {
				if (!isset($data['posts']['content']) || $data['posts']['content'] == '""') {
					$result['errors'][] = 'titre is required';
				}
			}
			if ($data['posts']['type_id'] == 2) {
				if (!isset($data['posts']['content']) || $data['posts']['content'] == '""') {
					$result['errors'][] = 'html is required';
				}
			}
			if ($data['posts']['type_id'] == 3) {
				if (!isset($data['posts']['content']) || $data['posts']['content'] == '""') {
					$result['errors'][] = 'pdf name is required';
				}
				if (!isset($data['files']) || !$data['files']) {
					$result['errors'][] = 'pdf file is required';
				}
				if (isset($data['files']) || $data['files']['file']) {
					if ($data['files']['file']['error'] > 0) {
						$result['errors'][] = 'enter valide file (pdf contains error)';
					}
				}
			}
			if ($data['posts']['type_id'] == 4) {
				if (!isset($data['posts']['content']) || $data['posts']['content'] == '""') {
					$result['errors'][] = 'youtube title is required';
				}
				if (!isset($data['posts']['link']) || !$data['posts']['link']) {
					$result['errors'][] = 'youtube link is required';
				}
			}
			if ($data['posts']['type_id'] == 6) {
				if (!isset($data['posts']['content']) || $data['posts']['content'] == '""') {
					$result['errors'][] = 'text is required';
				}
			}
			if ($data['posts']['type_id'] == 7) {
				if (!isset($data['posts']['content']) || $data['posts']['content'] == '""') {
					$result['errors'][] = 'slide content is required';
				}
			}
			if ($data['posts']['type_id'] == 8) {
				if (!isset($data['posts']['content']) || $data['posts']['content'] == '""') {
					$result['errors'][] = 'question is required';
				}
			}
			if ($data['posts']['type_id'] == 12) {
				if (!isset($data['posts']['content']) || $data['posts']['content'] == '""') {
					$result['errors'][] = 'name is required';
				}
			}
		}
		return $result;
	}
	public function lecons($action = null)
	{

		if ($action == 'ressource') {

			if (Request::isPost()) {
				$result = null;
				if (!isset($_POST['label']) || !$_POST['label']) {
					$result['errors'][] = 'label is required';
				}
				if (!isset($_POST['type_id']) || !$_POST['type_id']) {
					$result['errors'][] = 'type is required';
				}
				if ($result) {
					sendResponse($result, 422);
				}

				$ressource  = new  Models\LMS\Ressource();
				if (is_null(Request::get('id')) || is_numeric(Request::get('id'))) {
					$ressource  = new  Models\LMS\Ressource(Request::get('id'));
				}


				$ressource->Label =  Request::get('label');
				$ressource->Type =  Request::get('type_id');
				$ressource->Introduction =  Request::get('introduction');
				$ressource->Lecon =  Request::get('lecon_id');
				$ressource->Date = date('Y-m-d H:i:s');

				if (!$ressource->ID)
					$ressource->Ordre = Models\LMS\Ressource::where(array('Lecon' => $ressource->Lecon->ID))->count() + 1;

				$ressource->save();


				sendResponse('ok');
			}
		}
		if ($action == 'content') {
			if (Request::isPost()) {
				$result = $this->validation(['posts' => $_POST, 'files' => $_FILES]);
				if ($result) {
					sendResponse($result, 422);
				}
				$question = null;
				if ($_POST['type_id'] == 8 || $_POST['type_id'] == 9) {
					$question = $_POST['content'];
					$answer = $_POST['answer'];
				}
				if (Request::get('id') && Request::get('id') != 'null') {
					$contenu = new Models\LMS\RessourceContent(Request::get('id'));
					$ressource = new Models\LMS\Ressource($contenu->Ressource->ID);
				} else {
					$contenu = new Models\LMS\RessourceContent();
					$ressource = new Models\LMS\Ressource(Request::get('ressource_id'));
				}
				if ($question) {
					$contenu->set('Content', $question);
					$contenu->set('Answer', $answer);
				} elseif ($_POST['type_id'] == 11) {
					$contenu->set('Content', $_POST['content']);
					$contenu->set('Answer', $_POST['answer']);
				} elseif ($_POST['type_id'] == 12) {
					$contenu->set('Content', $_POST['content']);
					$contenu->set('Answer', $_POST['answer']);
				} elseif ($_POST['type_id'] == 6) {
					$contenu->set('Content', $_POST['content']);
					$contenu->set('Answer', $_POST['answer']);
				} else {
					$contenu->Content =  Request::get('content');
					$contenu->Answer =  Request::get('answer');
				}
				$contenu->Link =  Request::get('link');
				$contenu->Type =  Request::get('type_id');
				$contenu->Ressource =  $ressource ? $ressource->ID : null;
				$contenu->Duree =  Request::get('duree');
				if (!$contenu->ID)
					$contenu->Ordre =  1;
				if (isset($_FILES['file']) && $_FILES['file']['error'] != UPLOAD_ERR_NO_FILE) {
					$upload_path  = _basepath . \Config::get('path-lms-files') . '/lecons_files/';
					if ($contenu->File)
						Upload::delete($upload_path . $contenu->File);
					$contenu->File = Upload::store('file', $upload_path);
				}
				if (isset($_FILES['audio']) && $_FILES['audio']['error'] != UPLOAD_ERR_NO_FILE) {
					$upload_path  = _basepath . \Config::get('path-lms-files') . '/lecons_files/';
					if ($contenu->Audio)
						Upload::delete($upload_path . $contenu->File);
					$contenu->Audio = Upload::store('audio', $upload_path);
				}
				$contenu->Date = date('Y-m-d H:i:s');

				$contenu->save();

				if ($contenu->File) {
					$fileName = explode('.', $contenu->File);
					if ($fileName[1] == 'pdf') {
						loadLib('pdftoimage');
						$upload_path  =  _basepath . \Config::get('path-lms-files') . '/lecons_files/';
						$file_path  =  _basepath . \Config::get('path-lms-files') . '/lecons_files/' . $contenu->File;
						$file_pdf_path  =  _basepath . \Config::get('path-lms-files') . '/lecons_files/' . $fileName[0] . '/';
						mkdir($file_pdf_path, 0777, true);
						$pdf = new \PdfToImage\Pdf($file_path);
						for ($i = 1; $i <= $pdf->getNumberOfPages(); $i++) {
							$pdf->setPage($i)->saveImage($file_pdf_path  . '/' . $i . '.jpg');
						}
					}
					// else{
					// 	$contenu->set('file',  Upload::storeImage('file', '/assets/spa/lms'));
					// }
				}

				$ressource->Duree =  null;
				$ressource->save();

				$lecon = $ressource->Lecon;
				$lecon->Duree = null;
				$lecon->save();
				sendResponse('ok');
			}
		}

		if ($action == 'save_order') {

			$ordre = 1;
			$data = json_decode(file_get_contents('php://input'), true);
			$data = $data['data'];
			foreach ($data as $item) {
				$lecon = new Models\LMS\Lecons($item['id']);
				$lecon->Ordre = $ordre;
				$lecon->save();
				$ordre++;
			}

			return sendResponse([
				'title' => 'Ordre modifié !',
				'message' => 'L\'ordre des rubriques a été modifié avec succés.',
				'icon' => 'success',
			]);
		}

		if ($action == 'contents_order') {
			$ordre = 1;
			$data = json_decode(file_get_contents('php://input'), true);
			$data = $data['data'];
			foreach ($data as $item) {
				$lecon = new Models\LMS\RessourceContent($item['id']);
				$lecon->Ordre = $ordre;
				$lecon->save();
				$ordre++;
			}
			return sendResponse([
				'title' => 'Ordre modifié !',
				'message' => 'L\'ordre des rubriques a été modifié avec succés.',
				'icon' => 'success',
			]);
		}

		if ($action == 'ressources_order') {
			$ordre = 1;
			$data = json_decode(file_get_contents('php://input'), true);
			$data = $data['data'];
			foreach ($data as $item) {
				$lecon = new Models\LMS\Ressource($item['id']);
				$lecon->Ordre = $ordre;
				$lecon->save();
				$ordre++;
			}

			return sendResponse([
				'title' => 'Ordre modifié !',
				'message' => 'L\'ordre des rubriques a été modifié avec succés.',
				'icon' => 'success',
			]);
		}

		if ($action == 'delete_content') {
			$content = new Models\LMS\RessourceContent(Request::getArgs(2));
			$content->delete();

			return;
		}


		// action = lecon_id
		if ($lecon = $action) {
			$niveaux = Models\Niveau::all();
			$unites = Models\Unite::all();
			$rubriques = Models\LMS\LeconRubrique::all();
			$matieres = Models\Matiere::all();
			$ressource_types = Models\LMS\RessourceType::where(['Enabled' => 1])->get();
			$etape_types  = Models\LMS\EtapeType::all();


			$lecon =  new Models\LMS\Lecons($lecon);

			$ressources = Models\LMS\Ressource::where(array('Lecon' => $lecon->ID))->order(array('Ordre' => true))->get();


			return  sendResponse([
				'niveaux' => array_map(fn ($item) => ['value' => $item->ID, 'text' => $item->Label], $niveaux),
				'unites' => array_map(fn ($item) => ['value' => $item->ID, 'text' => $item->Label], $unites),
				'matieres' =>  array_map(fn ($item) => ['value' => $item->ID, 'text' => $item->Label], $matieres),
				'rubriques' =>  array_map(fn ($item) => ['value' => $item->ID, 'text' => $item->Label], $rubriques),
				'etape_types' => array_map(fn ($item) => [
					'id' => $item->ID,
					'value' => $item->ID,
					'text' => $item->Label,
					'icon' => $item->Icon,
					'color' => $item->Color,
					'image_url' => $item->getImage(),
				], $etape_types),
				'ressource_types' => array_map(fn ($item) => [
					'id' => $item->ID,
					'value' => $item->ID,
					'text' => $item->Label,
					'icon' => $item->Icon,
					'image_url' => $item->getImage(),
				], $ressource_types),

				'lecon' => [
					'id' => $lecon->ID,
					'label' => $lecon->Label,
					'introduction' => $lecon->Introduction,
					'niveau_id' => $lecon->Niveau->ID,
					'unite_id' => $lecon->Unite->ID,
					'matiere_id' => $lecon->Matiere ? $lecon->Matiere->ID : null,
					'rubrique_id' => $lecon->Rubrique->ID,
					'syllabus' => $lecon->Syllabus,
					'rub_count' => count(Models\LMS\Lecons::where(['Rubrique' => $lecon->Rubrique->ID, 'Unite' => $lecon->Unite->ID, 'Niveau' => $lecon->Niveau->ID, 'Enabled' => 1])->get()),
					'instructions' => $lecon->Instructions,
					'objectifs' => $lecon->Objectifs,
					'prerequis' => $lecon->Prerequis,
					'duree' => $lecon->Duree ? $lecon->Duree : 0,
					'percent' => $lecon->getPercent(),
					'last_content_learn' => $lecon->getLastContent(),
					'last_ressource_learn' => $lecon->getLastRessource(),
					'icone' => $lecon->getIcone(),
					'count_ressources' => Models\LMS\Ressource::where(array('Lecon' => $lecon->ID))->count(),
					'code' => $lecon->Code,
					'enabled' => $lecon->Enabled,
				],
				'ressources' => array_map(fn ($item) => [
					'id' => $item->ID,
					'label' => $item->Label,
					'type_id' => $item->Type ? $item->Type->ID : null,
					'type_label' =>  $item->Type ? $item->Type->Label : null,
					'etape_color' =>  $item->Type ? $item->Type->Color : null,
					'introduction' => $item->Introduction,
					'lecon_id' => $item->Lecon->ID,
					'ordre' => $item->Ordre,
					'duree' => $item->Duree,
					'icon' => URL::base() . 'assets/lms/icons/online-learning.png',
					'contents' => array_map(
						fn ($_item) => [
							'id' => $_item->ID,
							'content' =>  $_item->getJsonProperty('Content', ''),
							'link' => $_item->Link,
							'image' => $_item->File,
							'duree' => $_item->Duree,
							'answer' =>  $_item->get('Answer'),
							'type_id' => $_item->Type ? $_item->Type->ID : null,
							'type_label' => $_item->Type ? $_item->Type->Label : null,
							'audio' => $_item->Audio,
						],
						Models\LMS\RessourceContent::where(array('Ressource' => $item->ID))->order(array('Ordre' => true))->get()
					),
				], $ressources),
			]);
		}
		// save lecon 
		if (Request::isPost()) {
			$lecon  = new  Models\LMS\Lecons();
			if (is_null(Request::get('id')) || is_numeric(Request::get('id'))) {
				$lecon  = new  Models\LMS\Lecons(Request::get('id'));
			}


			if (Request::get('id')) {
				$lecon->Code = \Tools::getRandChars(10, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789');
			}
			$lecon->Label =  Request::get('label');
			$lecon->Alias = alais_string(Request::get('label'));
			$lecon->Introduction =  Request::get('introduction');
			$lecon->Niveau =  Request::get('niveau_id');
			$lecon->Unite =  Request::get('unite_id');
			$lecon->Matiere =  Request::get('matiere_id');
			$lecon->Syllabus =  Request::get('syllabus');
			$lecon->Instructions =  Request::get('instructions');
			$lecon->Objectifs =  Request::get('objectifs');
			$lecon->Prerequis =  Request::get('prerequis');
			if (!Request::get('id')) {
				$lecon->Enabled =  1;
				$lecon->Date =  date('Y-m-d H:i:s');
			}

			if (!$lecon->ID)
				$lecon->Ordre =  Models\LMS\Ressource::where(array('Lecon' => $lecon->ID))->count() + 1;

			if (Request::get('new_rubrique')) {
				$rubrique = new Models\LMS\LeconRubrique();
				$rubrique->Label =  Request::get('rubrique_label');
				$rubrique->Date =  date('Y-m-d H:i:s');
				$rubrique->save();
			} else if (Request::get('rubrique_id')) {
				$rubrique = new Models\LMS\LeconRubrique(Request::get('rubrique_id'));
			}
			$lecon->Rubrique =  $rubrique->ID;
			if (isset($_FILES['icone']) && $_FILES['icone']['error'] != UPLOAD_ERR_NO_FILE) {
				$upload_path  = _basepath . \Config::get('path-lms-files') . '/lecons_files/';
				if ($lecon->Icone)
					Upload::delete($upload_path . $lecon->Icone);
				$lecon->Icone = Upload::store('icone', $upload_path);
			}
			$lecon->save();
			sendResponse('ok');
		}


		$niveau_id = null;
		$unite_id = null;
		$matiere_id = null;
		$niveaux = Models\Niveau::all();
		$unites = Models\Unite::all();
		$rubriques = Models\LMS\LeconRubrique::all();
		$matieres = Models\Matiere::all();

		if (Request::get('niveau_id')) {
			$niveau_id = Request::get('niveau_id');
		} else {
			$niveau_id = $niveaux[0]->ID;
		}


		if (Request::get('unite_id')) {
			$unite_id = Request::get('unite_id');
		} else {
			$unite_id = $unites[0]->ID;
		}
		$collection_rubriques_tree = [];
		$rubriques_tree =  Models\LMS\LeconRubrique::where('`ID` IN(SELECT `Rubrique` FROM `lms_lecons` WHERE `Unite` =' . $unite_id . ' AND `Niveau`=' . $niveau_id .   ')')->get();
		if (Request::get('matiere_id')) {
			$matiere_id = Request::get('matiere_id');
			$rubriques_tree =  Models\LMS\LeconRubrique::where('`ID` IN(SELECT `Rubrique` FROM `lms_lecons` WHERE `Unite` =' . $unite_id . ' AND `Niveau`=' . $niveau_id . ' AND `Matiere`=' . $matiere_id .  ')')->get();
		}

		foreach ($rubriques_tree as $key => $item) {
			$_lecons =  Models\LMS\Lecons::where(['Rubrique' => $item->ID])->where(array('Unite' => $unite_id))->where(array('Niveau' => $niveau_id))->order(array('Ordre' => true))->get();
			$lecons  = [];
			foreach ($_lecons as $key => $_item) {
				$lecon_ressources = Models\LMS\Ressource::where(array('Lecon' => $_item->ID))->get();
				$duree = 0;
				foreach ($lecon_ressources as $lecon_ressource) {
					$lecon_ressource_contents = Models\LMS\RessourceContent::where(array('Ressource' => $lecon_ressource->ID))->get();
					foreach ($lecon_ressource_contents as $lecon_ressource_content) {

						$duree = $duree + $lecon_ressource_content->get('Duree');
					}
				}
				$lecons[] = [
					'id' => $_item->ID,
					'label' => $_item->Label,
					'introduction' => $_item->Introduction,
					'niveau_id' => $_item->Niveau->ID,
					'unite_id' => $_item->Unite->ID,
					'matiere_id' => $_item->Matiere ? $_item->Matiere->ID : null,
					'rubrique_id' => $_item->Rubrique->ID,
					'syllabus' => $_item->Syllabus,
					'instructions' => $_item->Instructions,
					'objectifs' => $_item->Objectifs,
					'prerequis' => $_item->Prerequis,
					'percent' => $_item->getPercent(),
					'last_content_learn' => $_item->getLastContent(),
					'last_ressource_learn' => $_item->getLastRessource(),
					'icone' => $_item->getIcone(),
					'duree' => $duree,
					'rub_count' => count(Models\LMS\Lecons::where(['Rubrique' => $_item->Rubrique->ID, 'Unite' => $_item->Unite->ID, 'Niveau' => $_item->Niveau->ID, 'Enabled' => 1])->get()),
					'count_ressources' => Models\LMS\Ressource::where(array('Lecon' => $_item->ID))->count(),
					'code' => $_item->Code,
					'enabled' => $_item->Enabled,
				];
			}

			$collection_rubriques_tree[] = [
				'id' => $item->ID,
				'label' => $item->Label,
				'lecons' => $lecons,
			];
		}


		return sendResponse([
			'lecons' => [],
			'niveaux' => array_map(fn ($item) => ['value' => $item->ID, 'text' => $item->Label], $niveaux),
			'unites' => array_map(fn ($item) => ['value' => $item->ID, 'text' => $item->Label], $unites),
			'matieres' =>  array_map(fn ($item) => ['value' => $item->ID, 'text' => $item->Label], $matieres),
			'rubriques' =>  array_map(fn ($item) => ['value' => $item->ID, 'text' => $item->Label], $rubriques),
			'rubriques_tree' => $collection_rubriques_tree,
			'niveau_id' => $niveau_id,
			'unite_id' => $unite_id,
			'matiere_id' => $matiere_id,
		]);
	}

	public function single_etape_type()
	{
		$lessons = new Models\LMS\EtapeType(Request::get('id'));
		return sendResponse(
			[
				'id' => $lessons->ID,
				'value' => $lessons->ID,
				'text' => $lessons->Label,
				'icon' => $lessons->Icon,
				'image_url' => $lessons->getImage()
			],
		);
	}
	public function etape_types()
	{



		if (Request::isPost()) {
			$statut  = new  Models\LMS\EtapeType();
			if (is_null(Request::get('id')) || is_numeric(Request::get('id'))) {
				$statut  = new  Models\LMS\EtapeType(Request::get('id'));
			}

			$statut->Label =  (Request::get('text') != 'null') ? Request::get('text') : null;
			$statut->Color =  (Request::get('color') != 'null') ? Request::get('color') : '#ebedf3';
			$statut->Alias = alais_string($statut->Label);

			if (isset($_FILES['icon']) && $_FILES['icon']['error'] != UPLOAD_ERR_NO_FILE) {

				$upload_path  = _basepath . \Config::get('path-lms-files') . '/etape_types/';

				$fileError = Upload::checkUploadImage('icon');
				errorPage($fileError);

				Upload::delete($upload_path . $statut->Icon);
				$statut->Icon = Upload::storeImage('icon', $upload_path);
			}



			$statut->save();

			sendResponse('ok');
		}


		$rubriques = Models\LMS\EtapeType::all();
		return sendResponse([
			'rubriques' => array_map(fn ($item) => [
				'id' => $item->ID,
				'value' => $item->ID,
				'text' => $item->Label,
				'color' => $item->Color ?: '#ebedf3',
				'icon' => $item->Icon,
				'image_url' => $item->getImage(),
			], $rubriques),
		]);
	}

	public function ressource_types()
	{

		if (Request::isPost()) {
			$statut  = new  Models\LMS\RessourceType();
			if (is_null(Request::get('id')) || is_numeric(Request::get('id'))) {
				$statut  = new  Models\LMS\RessourceType(Request::get('id'));
			}

			$statut->Label =  (Request::get('text') != 'null') ? Request::get('text') : null;
			$statut->Alias = alais_string($statut->Label);
			if (!Request::get('id')) {
				$statut->Date = date('Y-m-d H:i:s');
			}


			if (isset($_FILES['icon']) && $_FILES['icon']['error'] != UPLOAD_ERR_NO_FILE) {

				$upload_path  = _basepath . \Config::get('path-lms-files') . '/ressource_types/';

				$fileError = Upload::checkUploadImage('icon');
				errorPage($fileError);

				Upload::delete($upload_path . $statut->Icon);
				$statut->Icon = Upload::storeImage('icon', $upload_path);
			}


			$statut->save();

			sendResponse('ok');
		}


		$rubriques = Models\LMS\RessourceType::where(['Enabled' => true])->get();
		return sendResponse([
			'rubriques' => array_map(fn ($item) => [
				'id' => $item->ID,
				'value' => $item->ID,
				'text' => $item->Label,
				'icon' => $item->Icon,
				'image_url' =>  $item->getImage(),
			], $rubriques),
		]);
	}
	public function getCourseFiltered($id)
	{
		$niveau_id = null;
		$unite_id = null;
		$niveaux =  Models\Niveau::all();
		$unites = Models\Unite::all();
		$rubriques = Models\LMS\LeconRubrique::all();
		$matieres = Models\Matiere::all();

		if (Request::get('niveau_id')) {
			$niveau_id = Request::get('niveau_id');
		} else {
			$niveau_id = $niveaux[0]->ID;
		}


		if (Request::get('unite_id')) {
			$unite_id = Request::get('unite_id');
		} else {
			$unite_id = $unites[0]->ID;
		}

		$collection_rubriques_tree = [];
		$rubriques_tree =  Models\LMS\LeconRubrique::where('`ID` IN(SELECT `Rubrique` FROM `lms_lecons` WHERE `Unite` =' . $unite_id . ' AND `Niveau`=' . $niveau_id . ')')->get();

		foreach ($rubriques_tree as $key => $item) {
			$_lecons =  Models\LMS\Lecons::where(array('Rubrique', $item->ID))->where(array('Unite' => $unite_id))->where(array('Niveau' => $niveau_id))->order(array('Ordre' => true))->get();
			$lecons  = [];
			foreach ($_lecons as $key => $_item) {
				$lecons[] = [
					'id' => $_item->ID,
					'label' => $_item->Label,
					'introduction' => $_item->Introduction,
					'niveau_id' => $_item->Niveau->ID,
					'unite_id' => $_item->Unite->ID,
					'matiere_id' => $_item->Matiere ? $_item->Matiere->ID : null,
					'rubrique_id' => $_item->Rubrique->ID,
					'syllabus' => $_item->Syllabus,
					'instructions' => $_item->Instructions,
					'objectifs' => $_item->Objectifs,
					'prerequis' => $_item->Prerequis,
					'duree' => $_item->Duree ? $_item->Duree : 0,
					'count_ressources' => Models\LMS\Ressource::where(array('Lecon' => $_item->ID))->count(),
					'code' => $_item->Code,
					'enabled' => $_item->Enabled,
				];
			}

			$collection_rubriques_tree[] = [
				'id' => $item->ID,
				'label' => $item->Label,
				'lecons' => $lecons,
			];
		}



		return sendResponse([
			'lecons' => [],
			'niveaux' => array_map(fn ($item) => ['value' => $item->ID, 'text' => $item->Label], $niveaux),
			'unites' => array_map(fn ($item) => ['value' => $item->ID, 'text' => $item->Label], $unites),
			'matieres' =>  array_map(fn ($item) => ['value' => $item->ID, 'text' => $item->Label], $matieres),
			'rubriques' =>  array_map(fn ($item) => ['value' => $item->ID, 'text' => $item->Label], $rubriques),
			'rubriques_tree' => $collection_rubriques_tree,
			'niveau_id' => $niveau_id,
			'unite_id' => $unite_id,
		]);
	}
	public function borne_home()
	{

		$niveau_id = null;
		$unite_id = null;
		$niveaux =  Models\Niveau::all();
		$unites = Models\Unite::all();
		$rubriques = Models\LMS\LeconRubrique::all();
		$matieres = Models\Matiere::all();

		if (Request::get('niveau_id')) {
			$niveau_id = Request::get('niveau_id');
		} else {
			$niveau_id = $niveaux[0]->ID;
		}


		if (Request::get('unite_id')) {
			$unite_id = Request::get('unite_id');
		} else {
			$unite_id = $unites[0]->ID;
		}

		$collection_rubriques_tree = [];
		$rubriques_tree =  Models\LMS\LeconRubrique::where('`ID` IN(SELECT `Rubrique` FROM `lms_lecons` WHERE `Unite` =' . $unite_id . ' AND `Niveau`=' . $niveau_id . ')')->get();

		foreach ($rubriques_tree as $key => $item) {
			$_lecons =  Models\LMS\Lecons::where(['Rubrique' => $item->ID, 'Unite' => $unite_id, 'Niveau' => $niveau_id])->order(array('Ordre' => true))->get();
			$lecons  = [];
			foreach ($_lecons as $key => $_item) {
				$lecons[] = [
					'id' => $_item->ID,
					'label' => $_item->Label,
					'introduction' => $_item->Introduction,
					'niveau_id' => $_item->Niveau->ID,
					'unite_id' => $_item->Unite->ID,
					'matiere_id' => $_item->Matiere ? $_item->Matiere->ID : null,
					'rubrique_id' => $_item->Rubrique->ID,
					'syllabus' => $_item->Syllabus,
					'instructions' => $_item->Instructions,
					'objectifs' => $_item->Objectifs,
					'prerequis' => $_item->Prerequis,
					'percent' => $_item->getPercent(),
					'last_content_learn' => $_item->getLastContent(),
					'last_ressource_learn' => $_item->getLastRessource(),
					'icone' => $_item->getIcone(),
					'duree' => $_item->Duree ? $_item->Duree : 0,
					'count_ressources' => Models\LMS\Ressource::where(array('Lecon' => $_item->ID))->count(),
					'code' => $_item->Code,
					'enabled' => $_item->Enabled,
				];
			}

			$collection_rubriques_tree[] = [
				'id' => $item->ID,
				'label' => $item->Label,
				'lecons' => $lecons,
			];
		}



		return sendResponse([
			'lecons' => [],
			'niveaux' => array_map(fn ($item) => ['value' => $item->ID, 'text' => $item->Label], $niveaux),
			'unites' => array_map(fn ($item) => ['value' => $item->ID, 'text' => $item->Label], $unites),
			'matieres' =>  array_map(fn ($item) => ['value' => $item->ID, 'text' => $item->Label], $matieres),
			'rubriques' =>  array_map(fn ($item) => ['value' => $item->ID, 'text' => $item->Label], $rubriques),
			'rubriques_tree' => $collection_rubriques_tree,
			'niveau_id' => $niveau_id,
			'unite_id' => $unite_id,
		]);
	}


	public function borne_rcode($code)
	{
		$lecon = Models\LMS\Lecons::where(array('Code' => $code))->first();

		if ($lecon) {
			return $this->borne_lecon($lecon->ID);
		}
	}


	public function  borne_lecon($lecon)
	{
		$lec = $lecon;
		$niveaux = Models\Niveau::all();
		$unites = Models\Unite::all();
		$rubriques = Models\LMS\LeconRubrique::all();
		$matieres = Models\Matiere::all();
		$ressource_types = Models\LMS\RessourceType::where(['Enabled' => true])->get();
		$etape_types  = Models\LMS\EtapeType::all();

		$lecon =  new Models\LMS\Lecons($lecon);

		$ressources = Models\LMS\Ressource::where(array('Lecon' => $lecon->ID))->order(array('Ordre' => true))->get();
		$collection_rubriques_tree = [];
		$rubriques_tree =  Models\LMS\LeconRubrique::where('`ID` IN(SELECT `Rubrique` FROM `lms_lecons` WHERE `lms_lecons`.`Enabled` = 1 AND `Unite` =' . $lecon->get('Unite')->get('ID') . ' AND `Niveau`=' . $lecon->get('Niveau')->get('ID')  . ')')->get();

		foreach ($rubriques_tree as $key => $item) {
			$_lecons =  Models\LMS\Lecons::where(array('Rubrique' => $item->ID, 'Enabled = 1', 'Unite' => $lecon->get('Unite')->get('ID')))->where(array('Niveau' => $lecon->get('Niveau')->get('ID')))->order(array('Ordre' => true))->get();
			$lecons  = [];
			foreach ($_lecons as $key => $_item) {
				$lecons[] = [
					'id' => $_item->ID,
					'label' => $_item->Label,
					'introduction' => $_item->Introduction,
					'niveau_id' => $_item->Niveau->ID,
					'unite_id' => $_item->Unite->ID,
					'matiere_id' => $_item->Matiere ? $_item->Matiere->ID : null,
					'rubrique_id' => $_item->Rubrique->ID,
					'syllabus' => $_item->Syllabus,
					'instructions' => $_item->Instructions,
					'icone' => $_item->getIcone(),
					'objectifs' => $_item->Objectifs,
					'percent' => $_item->getPercent(),
					'last_content_learn' => $_item->getLastContent(),
					'last_ressource_learn' => $_item->getLastRessource(),
					'prerequis' => $_item->Prerequis,
					'duree' => $_item->Duree ? $_item->Duree : 0,
					'count_ressources' => Models\LMS\Ressource::where(array('Lecon' => $_item->ID))->count(),
					'code' => $_item->Code,
					'enabled' => $_item->Enabled,
				];
			}

			$collection_rubriques_tree[] = [
				'id' => $item->ID,
				'label' => $item->Label,
				'lecons' => $lecons,
			];
		}

		return sendResponse([
			'rubriques_tree' => $collection_rubriques_tree,
			'niveaux' => array_map(fn ($item) => ['value' => $item->ID, 'text' => $item->Label], $niveaux),
			'unites' => array_map(fn ($item) => ['value' => $item->ID, 'text' => $item->Label], $unites),
			'matieres' =>  array_map(fn ($item) => ['value' => $item->ID, 'text' => $item->Label], $matieres),
			'rubriques' =>  array_map(fn ($item) => ['value' => $item->ID, 'text' => $item->Label], $rubriques),
			'etape_types' => array_map(fn ($item) => [
				'id' => $item->ID,
				'value' => $item->ID,
				'color' => $item->Color,
				'text' => $item->Label,
				'icon' => $item->Icon,
				'image_url' => $item->getImage(),
			], $etape_types),
			'ressource_types' => array_map(fn ($item) => [
				'id' => $item->ID,
				'value' => $item->ID,
				'text' => $item->Label,
				'icon' => $item->Icon,
				'image_url' => $item->getImage(),
			], $ressource_types),

			'lecon' => [
				'id' => $lecon->ID,
				'next_lecon' => $lecon->nextLecon($lec),
				'label' => $lecon->Label,
				'introduction' => $lecon->Introduction,
				'niveau_id' => $lecon->Niveau->ID,
				'unite_id' => $lecon->Unite->ID,
				'matiere_id' => $lecon->Matiere ? $lecon->Matiere->ID : null,
				'rubrique_id' => $lecon->Rubrique->ID,
				'rub_count' => count(Models\LMS\Lecons::where(['Rubrique' => $lecon->Rubrique->ID, 'Unite' => $lecon->Unite->ID, 'Niveau' => $lecon->Niveau->ID, 'Enabled' => 1])->get()),
				'syllabus' => $lecon->Syllabus,
				'instructions' => $lecon->Instructions,
				'objectifs' => $lecon->Objectifs,
				'prerequis' => $lecon->Prerequis,
				'percent' => $lecon->getPercent(),
				'last_content_learn' => $lecon->getLastContent(),
				'last_ressource_learn' => $lecon->getLastRessource(),
				'duree' => $lecon->Duree ? $lecon->Duree : 0,
				'count_ressources' => Models\LMS\Ressource::where(array('Lecon' => $lecon->ID))->count(),
				'code' => $lecon->Code,
				'icone' => $lecon->getIcone(),
				'icone_u' => $lecon->Icone,
				'enabled' => $lecon->Enabled,
			],
			'ressources' => array_map(fn ($item) => [
				'id' => $item->ID,
				'label' => $item->Label,
				'type_id' => $item->Type ? $item->Type->ID : null,
				'type_label' =>  $item->Type ? $item->Type->Label : null,
				'etape_color' =>  $item->Type ? $item->Type->Color : null,
				'introduction' => $item->Introduction,
				'lecon_id' => $item->Lecon->ID,
				'ordre' => $item->Ordre,
				'duree' => $item->Duree,
				'icon' => $item->Type ? NULL : null,
				'contents' => array_map(
					fn ($_item) => [
						'id' => $_item->ID,
						'content'  => $_item->getJsonProperty('Content', ''),
						'link' => $_item->Link,
						'image' => $_item->File,
						'duree' => $_item->Duree,
						'answer' => $_item->get('Answer'),
						'type_id' => $_item->Type ? $_item->Type->ID : null,
						'type_label' => $_item->Type ? $_item->Type->Label : null,
						'audio' => $_item->Audio,
					],
					Models\LMS\RessourceContent::where(array('Ressource' => $item->ID))->order(array('Ordre' => true))->get()
				),
			], $ressources),
		]);
	}
	// get unites length 
	public function getUnitesLength()
	{
		$unitesCount =  Models\LMS\LeconRubrique::query()->count();

		return sendResponse([
			'unitesCount' => $unitesCount,

		]);
	}
	public function getLabelsDonut()
	{
		$labels = [
			'name 1',
			'name 2',
			'name 3',
			'name 4',
		];
		return sendResponse([
			'labels' => $labels
		]);
	}
	public function insCount()
	{
		$insc = Models\Inscription::getCount();
		return sendResponse([
			'insc' => $insc
		]);
	}
	// save glossary image 
	public function saveGlossaryImage()
	{
		if (isset($_FILES['File']) && $_FILES['File']['error'] != UPLOAD_ERR_NO_FILE) {
			$upload_path  = _basepath . \Config::get('path-lms-files') . '/lecons_files/';
			$name =  Upload::store('File', $upload_path);
		}
		$data['path'] =  $name;
		sendResponse($data);
	}
	// save text image 
	public function saveTextImage()
	{
		if (isset($_FILES['File']) && $_FILES['File']['error'] != UPLOAD_ERR_NO_FILE) {
			$upload_path  = _basepath . \Config::get('path-lms-files') . '/lecons_files/';
			$name =  Upload::store('File', $upload_path);
		}
		$data['path'] =  $name;
		sendResponse($data);
	}
	// get unites length 
	public function getLevels()
	{
		$levels =  Models\Niveau::all();
		$levels_ =  array();
		foreach ($levels as $level) {
			$levels_[] = array(
				'value' => $level->get('ID'),
				'text' => $level->get('Label'),
			);
		}
		return sendResponse([
			'levels' => $levels_,
			'levelsCount' => count($levels),

		]);
	}
	// get users length 
	public function enseignants()
	{
		$data =  Enseignant::getList();
		$enseignants = array();
		foreach ($data as $enseignant) {
			$enseignants[] = [
				'id' => $enseignant->User->ID,
				'name' => $enseignant->User->getNomComplet(),
				'image_url' => $enseignant->User->getFullLinkImage(),
				'email' => $enseignant->User->get('Email'),
				'tel' => $enseignant->User->get('Tel'),
				'classes' => rtrim(implode(' , ', array_map(fn ($item) =>  $item->Classe->Label, $enseignant->getClasses())), ','),
				'unites' => rtrim(implode(' , ', array_map(fn ($item) => $item->Unite->Label, $enseignant->getUnites())), ','),
			];
		}
		return sendResponse([
			'enseignants' =>  $enseignants,
		]);
	}
	// get ens length 
	public function getUsers()
	{
		$data =  User::limit(10)->order(array('Date' => false))->get();
		$users = array();
		foreach ($data as $user) {
			$role =  $user->get('Role');
			// print_r($role);
			$users[] = [
				'id' => $user->get('ID'),
				'firstName' => $user->get('Prenom'),
				'lastName' => $user->get('Nom'),
				'image' => $user->getFullLinkImage(),
				'active' => $user->get('Enabled'),
				'email' => $user->get('Email'),
				'gsm' => $user->get('Tel'),
				'role' =>   ''
				// 'role' => 'Admin'
			];
		}
		return sendResponse([
			'users' =>  $users,
			'usersCount' =>  count($users),

		]);
	}
	public function getRoles()
	{
		$roles_ = Models\Role::getList();
		$roles = [];
		foreach ($roles_ as $role) {
			$roles[] = array(
				'value' => $role->get('ID'),
				'text' => $role->get('Label'),
			);
		}
		sendResponse([
			'roles' =>  $roles
		]);
	}
	public function  new_user()
	{
		$user = new Models\User();
		if ($user->emailExists($_POST['email'])) {
			$alert_msg = 'L\'email que vous avez entré est déjà enregistrer par un autre user.';
			return sendResponse($alert_msg);
		}
		if ($user->telExists($_POST['gsm'])) {
			$alert_msg = 'Le numéro de téléphone que vous avez entré est déjà enregistrer par un autre user.';
			return sendResponse($alert_msg);
		}
		if (roleIs('admin')) {
			$role = Role::where(array('id' => $_POST['role']))->first();
			$user->set('Role', $role->get('Alias'));
		}
		$user
			->set('Key', \Tools::getRandChars(30, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'))
			->set('Date', date('Y-m-d H:i:s'))
			// ->set('Role', (int)$_POST['role'])
			->set('Login', $_POST['email'])
			->set('Nom', $_POST['firstName'])
			->set('Prenom', $_POST['lastName'])
			->set('Tel', $_POST['gsm'])
			->set('Email', $_POST['email'])
			->set('Enabled', $_POST['active'] ? 1 : 0);
		if (isset($_FILES['image']) && $_FILES['image']['error'] != UPLOAD_ERR_NO_FILE) {
			$fileError = Upload::checkUploadImage('image');
			errorPage($fileError);

			if ($user->get('Image'))
				Upload::delete(_basepath . Config::get('path-images-users') . $user->get('Image'));
			$user->set('Image', Upload::storeImage('image', Config::get('path-images-users')));

			$file = _basepath . Config::get('path-images-users') . $user->get('Image');

			$resizedFile = Config::get('path-images-users') . 'small-' . $user->get('Image');

			Tools::smart_resize_image($file, null, 400, 400, false, $resizedFile, false, false, 100);
		}
		$user->save();
		sendResponse(['user' => $user]);
	}
	public function updatePassword()
	{
		$user = new Models\User($_POST['user_id']);

		$data = array();
		if ($user) {
			if ($_POST['password'] == $_POST['passwordComfirmation']) {
				$user->set('Password', \Tools::passwordCrypt($_POST['passwordComfirmation'], $user->get('Key')))->save();
				$data['isPass'] = 1;
			} else {
				$data['isPass'] = 0;
				$data['msg'] = "Password d'ont match";
			}
		}
		sendResponse($data);
	}
	public function update_user()
	{
		$user = new Models\User($_POST['id']);
		if (isset($_POST['role']) && $_POST['role']) {

			if (roleIs('admin')) {
				$role = Role::where(array('id' => $_POST['role']))->first();
				$user->set('Role', $role->get('Alias'));
			}
		}
		$results = array();
		if ($_POST['email'] != $user->get('Email')) {
			if ($user->emailExists($_POST['email'])) {
				$results['emailExists'] = true;
				return sendResponse($results);
			}
		}
		if ($_POST['gsm'] != $user->get('Tel')) {
			if ($user->telExists($_POST['gsm'])) {
				$results['telExists'] = true;
				return sendResponse($results);
			}
		}
		$user
			->set('Login', $_POST['email'])
			->set('Nom', $_POST['firstName'])
			->set('Prenom', $_POST['lastName'])
			->set('Tel', $_POST['gsm'])
			->set('Email', $_POST['email'])
			->set('Enabled', $_POST['active'] ? 1 : 0);
		if (isset($_FILES['image']) && $_FILES['image']['error'] != UPLOAD_ERR_NO_FILE) {
			$fileError = Upload::checkUploadImage('image');
			errorPage($fileError);

			if ($user->get('Image'))
				Upload::delete(_basepath . Config::get('path-images-users') . $user->get('Image'));
			$user->set('Image', Upload::storeImage('image', Config::get('path-images-users')));

			$file = _basepath . Config::get('path-images-users') . $user->get('Image');

			$resizedFile = Config::get('path-images-users') . 'small-' . $user->get('Image');

			Tools::smart_resize_image($file, null, 400, 400, false, $resizedFile, false, false, 100);
		}
		$user->save();
		$results['isSuccess'] = true;
		sendResponse($results);
	}
	public function deleteUser($id)
	{
		$user = new Models\User($id);
		$user->delete();
		$data['msg'] = 'User Deleted Successfully';
		sendResponse($data);
	}
	public function deleteLevel($id)
	{
		$level = Models\Niveau::where(array('ID' => $id))->first();
		$result = array();
		if ($level) {
			$level->delete();
			$result['isDeleted'] = true;
		} else {
			$result['isDeleted'] = false;
		}
		return sendResponse($result);
	}
	public function saveLevel()
	{
		$result = array();
		$level = new Models\Niveau();
		$level_exists = Models\Niveau::where(array('Label' => $_POST['text']))->get();
		if ($level_exists) {
			$result['isExists'] = true;
			return sendResponse($result);
		}
		$level
			->set('Label', $_POST['text'])
			->set('Enabled', 1);
		$level->save();
		$result['isSaved'] = true;
		$result['level'] = $_POST['text'];
		return sendResponse($result);
	}
	public function updateLevel()
	{
		$result = array();
		$level = new Models\Niveau($_POST['value']);
		if ($level) {
			if ($_POST['text'] != $level->get('Label')) {
				$level_exists = Models\Niveau::where(array('Label' => $_POST['text']))->get();
				if ($level_exists) {
					$result['isExists'] = true;
					return sendResponse($result);
				}
			}
			$level
				->set('Label', $_POST['text']);
			$level->save();
			$result['isSaved'] = true;
			$result['level'] = $_POST['text'];
		} else {
			$result['isSaved'] = false;
		}
		return sendResponse($result);
	}
	// get unites length 
	public function getAllUnites()
	{
		$unites =  Models\LMS\LeconRubrique::all();


		return sendResponse([
			'unites' => array_map(fn ($item) => ['value' => $item->ID, 'text' => $item->Label], $unites),
		]);
	}
	// save rub in db 
	public function saveRubrique()
	{
		if (Request::isPost()) {
			$rub  = new \Models\LMS\LeconRubrique();
			$rub->set('Label', $_POST['label']);
			$rub->set('Date', date('Y-m-d H:i:s'));
			$rub->save();
		}
	}
	//delete a rub from db
	public function deleteUnite()
	{
		$lecons =   Models\LMS\Lecons::where(['rubrique' => Request::getArgs(1)])->get();
		if ($lecons) {
			sendResponse('can\'t delete this item');
			return;
		}
		$rub = new Models\LMS\LeconRubrique(Request::getArgs(1));
		$rub->delete();
		sendResponse('deleted success' . Request::getArgs(1));
		return;
	}
	//delete a ressource from db
	public function deleteRessource()
	{
		$ressource = new  Models\LMS\Ressource(Request::getArgs(1));
		$ressource->delete();
		sendResponse('deleted success' . Request::getArgs(1));
		return;
	}
	//delete a lecon from db
	public function deleteLecon()
	{
		$lecon = new  Models\LMS\Lecons(Request::getArgs(1));
		$lecon->delete();
		sendResponse('deleted success' . Request::getArgs(1));
		return;
	}

	// update unite 
	public function updateUnite()
	{
		if (Request::isPost()) {
			$rub  = new  Models\LMS\LeconRubrique();
			if (is_null(Request::get('value')) || is_numeric(Request::get('value'))) {
				$rub  = new  Models\LMS\LeconRubrique(Request::get('value'));
			}

			$rub->set('Label', $_POST['text']);
			if (isset($_FILES['icon']) && $_FILES['icon']['error'] != UPLOAD_ERR_NO_FILE) {

				$upload_path  = _basepath . \Config::get('path-lms-files') . '/etape_types/';

				$fileError = Upload::checkUploadImage('icon');
				errorPage($fileError);

				Upload::delete($upload_path . $rub->Icon);
				$rub->Icon = Upload::storeImage('icon', $upload_path);
			}
			$rub->save();
			sendResponse('ok');
		}
	}
	public function exportFile()
	{
		$request_body = file_get_contents('php://input');
		$data = json_decode($request_body);
		$lecons = [];
		$lecons =   Models\LMS\Lecons::where([
			'unite' => $data->formData,
			'niveau' => $data->niv,
			// 'matiere' => $data->matiere,
		])->get();
		$lc = [];
		$output = '';
		$output .= '
		<table class="table" border="1">
		<tr>
		<th>Niveau</th>
		<th>Matiere</th>
		<th>Label</th>
		<th>Unite</th>
		<th>Rubrique</th>
		<th>Introduction</th>
		<th>Duree</th>
		</tr> 
		';

		foreach ($lecons as $lecon) {

			$lc[] = [
				'id' => $lecon->ID,
				'label' => $lecon->Label,
				'introduction' => $lecon->Introduction,
				'niveau_label' => $lecon->Niveau,
				'unite_id' => $lecon->Unite,
				'matiere_id' => $lecon->Matiere,
				'rubrique_id' => $lecon->Rubrique,
				'syllabus' => $lecon->Syllabus,
				'instructions' => $lecon->Instructions,
				'objectifs' => $lecon->Objectifs,
				'prerequis' => $lecon->Prerequis,
				'duree' => $lecon->getRessourcesDuree(),
				'code' => $lecon->Code,
				'enabled' => $lecon->Enabled,

			];
		}
		$unite = null;
		$niveau = null;
		foreach ($lc as $l) {

			$niveau = $l['niveau_label']->Label ?? 'none';
			$matiere = $l['matiere_id']->Label ?? 'none';
			$unite = $l['unite_id']->Label ?? 'none';
			$rubrique = $l['rubrique_id']->Label ?? 'none';

			$output .= '<tr>
			<th>' . html($niveau) . '</th>
			<th>' . html($matiere) . '</th>
            <th>' . html($l['label'])  . '</th>
            <th>' . html($unite)  . '</th>
            <th>' . html($rubrique)  . '</th>
            <th>' . html($l['introduction'])  . '</th>
            <th>' . html($l['duree'])  . ' min</th>
            </tr>';
		}
		$output .= ' </table>';

		return sendResponse(
			[
				'output' => $output,
				'unite' => $unite,
				'level' => $niveau,
			]
		);
	}
	// export composantes to excel 
	public function exportComposantesFile()
	{

		$rubriques = Models\LMS\LeconRubrique::all();

		$output = '';
		$output .= '
		<table class="table" border="1">
		<tr>
		<th>#</th>
		<th>Label</th>
		</tr> 
		';



		$i = 0;
		foreach ($rubriques as $rub) {
			$i++;
			$output .= '<tr>
			<th>' . $i . '</th>
			<th>' . html($rub->Label) ?? '---' . '</th>
            </tr>';
		}
		$output .= ' </table>';
		return sendResponse(
			[
				'output' => $output,
			]
		);
	}
	public function getMatiereFiltredByUnites()
	{

		$request_body = file_get_contents('php://input');
		$data = json_decode($request_body);
		$unite_id = $data->formData;
		$matieres = Models\Matiere::where(['Unite' => $unite_id])->get();

		sendResponse([
			'matieres' =>  array_map(fn ($item) => ['value' => $item->ID, 'text' => $item->Label], $matieres),

		]);
	}
	public function getLeconByNiveauMatiereUnite()
	{
		$rubriques = Models\LMS\LeconRubrique::all();
		$request_body = file_get_contents('php://input');
		$data = json_decode($request_body);
		$unite_id = $data->unite;
		$matiere_id = $data->matiere;
		$niveau_id = $data->niveau;

		$_lecons =  Models\LMS\Lecons::where(array(
			'Unite' => $unite_id,
			'Matiere' => $matiere_id,
			'Niveau' => $niveau_id,

		))->get();

		$collection_rubriques_tree = [];
		if ($niveau_id) {
			if ($matiere_id) {

				$rubriques_tree =  Models\LMS\LeconRubrique::where('ID IN(SELECT Rubrique FROM lms_lecons WHERE Unite =' . $unite_id . ' and Niveau = ' . $niveau_id . ' and Matiere = ' . $matiere_id . ')')->get();
			} else {

				$rubriques_tree =  Models\LMS\LeconRubrique::where('ID IN(SELECT Rubrique FROM lms_lecons WHERE Unite =' . $unite_id . ' and Niveau = ' . $niveau_id .  ')')->get();
			}
		} else {
			if ($matiere_id) {

				$rubriques_tree =  Models\LMS\LeconRubrique::where('ID IN(SELECT Rubrique FROM lms_lecons WHERE Unite =' . $unite_id .  ' and Matiere = ' . $matiere_id . ')')->get();
			} else {

				$rubriques_tree =  Models\LMS\LeconRubrique::where('ID IN(SELECT Rubrique FROM lms_lecons WHERE Unite =' . $unite_id . ')')->get();
			}
		}

		foreach ($rubriques_tree as $key => $item) {
			$_lecons =  Models\LMS\Lecons::where(['Rubrique' => $item->ID])->where(array('Unite' => $unite_id))->where(array('Niveau' => $niveau_id))->order(array('Ordre' => true))->get();
			$lecons  = [];
			foreach ($_lecons as $key => $_item) {
				$lecons[] = [
					'id' => $_item->ID,
					'label' => $_item->Label,
					'introduction' => $_item->Introduction,
					'niveau_id' => $_item->Niveau->ID,
					'unite_id' => $_item->Unite->ID,
					'matiere_id' => $_item->Matiere ? $_item->Matiere->ID : null,
					'rubrique_id' => $_item->Rubrique->ID,
					'syllabus' => $_item->Syllabus,
					'instructions' => $_item->Instructions,
					'objectifs' => $_item->Objectifs,
					'prerequis' => $_item->Prerequis,
					'duree' => $_item->Duree ? $_item->Duree : 0,
					'count_ressources' => Models\LMS\Ressource::where(array('Lecon' => $_item->ID))->count(),
					'code' => $_item->Code,
					'enabled' => $_item->Enabled,
				];
			}

			$collection_rubriques_tree[] = [
				'id' => $item->ID,
				'label' => $item->Label,
				'lecons' => $lecons,
			];
		}
		sendResponse([
			'lecons' =>  [],
			'rubriques' =>  array_map(fn ($item) => ['value' => $item->ID, 'text' => $item->Label], $rubriques),
			'rubriques_tree' => $collection_rubriques_tree,
		]);
	}
	public function profile()
	{
		$auth_user = Session::getInstance()->getCurUser();
		$user = [
			'firstname' => $auth_user->get('Nom'),
			'lastname' => $auth_user->get('Prenom'),
			'email' => $auth_user->get('Email'),
			'tel' => $auth_user->get('Tel'),
			'image' => $auth_user->getImage()
		];

		sendResponse([
			'user' => $user
		]);
	}

	// activate lecon  lecon 
	public function activateLecon()
	{
		$lecon =  new Models\LMS\Lecons(Request::getArgs(1));
		$lecon->Enabled = 1;
		$lecon->save();
		return;
	}
	// activate lecon   lecon 
	public function deactivateLecon()
	{
		$lecon =  new Models\LMS\Lecons(Request::getArgs(1));
		$lecon->Enabled = 0;
		$lecon->save();
		return;
	}
	// tracking 
	public function saveTeacherTracking()
	{
		$user = Session::user();
		if ($user->Role->ID == 4) {
			$trackingHistory = new Models\LMS\TrackingHistory();
			$trackingHistory->set('Lecon', $_POST['lecon']);
			$trackingHistory->set('Ressource', $_POST['ressource']);
			$trackingHistory->set('Step',  $_POST['step']);
			$trackingHistory->set('Date',  date('Y-m-d H:m:s'));
			$trackingHistory->set('Teacher', Session::user()->ID);
			$trackings = Models\LMS\Tracking::getList(['where' => ['Lecon' => $_POST['lecon'], 'Teacher' => Session::user()->ID, 'Percent != 100']]);
			if ($trackings) {
				$tracking = new Models\LMS\Tracking($trackings[0]->ID);
			} else {
				$tracking = new Models\LMS\Tracking();
			}
			$tracking->set('Lecon', $_POST['lecon']);
			$tracking->set('Ressource', $_POST['ressource']);
			$tracking->set('Step',  $_POST['step']);
			$tracking->set('Date',  date('Y-m-d H:m:s'));
			$tracking->set('Teacher', Session::user()->ID);
			$percent = 0;
			$lecon_ressources = Models\LMS\Ressource::getList(['where' => ['Lecon' => $_POST['lecon']]]);
			$lecon_ressources_contents = 0;
			foreach ($lecon_ressources as $lecon_ressource) {
				# code...
				$content = Models\LMS\RessourceContent::getList(['where' => ['Ressource' => $lecon_ressource->ID]]);
				$lecon_ressources_contents += count($content);
			}
			$tracking->save();
			$trackingHistory->set('Tracking', $tracking->ID);
			$step_exists = Models\LMS\TrackingHistory::getList(['where' => ['Lecon' => $_POST['lecon'], 'Step' =>  $_POST['step'], 'Tracking' => $tracking->ID, 'Teacher' => Session::user()->ID]]);
			if (!$step_exists) {
				$trackingHistory->save();
			}
			$tracking_history_ressource = Models\LMS\TrackingHistory::getList(['where' => ['Lecon' => $_POST['lecon'], 'Tracking' => $tracking->ID, 'Teacher' => Session::user()->ID]]);
			$percent = (count($tracking_history_ressource) * 100) / $lecon_ressources_contents;
			if (!$step_exists) {
				$tracking->set('Percent',  $percent);
			}
			$tracking->save();
		}
		sendResponse(['msg' => 'tracking save']);
	}
	public function tracking()
	{
		$trackings = array_map(fn ($item) => [
			'lecon' => $item->Lecon ? $item->Lecon->Label : '---',
			'ressource' => $item->Ressource ? $item->Ressource->Label : '---',
			'step' => $item->Step ? 'Step' : '---',
			'teacher' => $item->Teacher->getNomComplet(),
			'image_url' => $item->Teacher->getFullLinkImage(),
			'date' => $item->Date,
		], TrackingHistory::getList());
		sendResponse(['trackings' => $trackings]);
	}
}

/* Router options */
$action = Request::getArgs(0) ? Request::getArgs(0) : 'index';
$id = Request::getArgs(1);
$controller = new ContentController;


if (!method_exists('ContentController', $action))
	$controller->index();

$controller->{$action}($id);
