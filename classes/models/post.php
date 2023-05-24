<?php

namespace Models;

class Post extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'com_posts';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'PostCategorie' => array(
			'type' => 'int',
			'fk' => 'PostCategorie',
		),
		'PostCategorieAlias' => array(
			'type' => 'varchar',
		),
		'PostFormat' => array(
			'type' => 'int',
			'fk' => 'PostFormat',
		),
		'User' => array(
			'type' => 'int',
			'fk' => 'User',
		),
		'Label' => array(
			'type' => 'varchar',
			'required' => true,
		),
		'Alias' => array(
			'type' => 'varchar',
			'required' => true,
		),
		'Intro' => array(
			'type' => 'text',
		),
		'Content' => array(
			'type' => 'text',
		),
		'Keywords' => array(
			'type' => 'text',
		),
		'File' => array(
			'type' => 'file',
		),
		'Image' => array(
			'type' => 'varchar',
		),
		'DatePublication' => array(
			'type' => 'datetime',
		),
		'DateExpiration' => array(
			'type' => 'datetime',
		),
		'EventLieu' => array(
			'type' => 'varchar',
		),
		'EventDateStart' => array(
			'type' => 'datetime',
		),
		'EventDateEnd' => array(
			'type' => 'datetime',
		),
		'Public' => array(
			'type' => 'tinyint',
		),
		'Parents' => array(
			'type' => 'tinyint',
		),
		'Teachers' => array(
			'type' => 'tinyint',
		),
		'ShareWithCollaborateurs' => array(
			'type' => 'tinyint',
		),
		'Visible' => array(
			'type' => 'tinyint',
		),
		'Home' => array(
			'type' => 'tinyint',
		),
		'Views' => array(
			'type' => 'int',
		),
		'Date' => array(
			'type' => 'datetime',
		),
		'UpdateHistory' => array(
			'type' => 'text',
		),
		'Images' => array(
			'type' => 'text',
		),
		'Files' => array(
			'type' => 'text',
		),
		'Enseignant' => array(
			'fk' => 'Enseignant',
		),
		'Matiere' => array(
			'fk' => 'Unite',
		),
		'DateRemise' => array(
			'type' => 'date',
		),
		'Commentaire' => array(
			'type' => 'boolean',
		),
		'ShowCommentaireToParents' => array(
			'type' => 'boolean',
		),
		'Questionnaire' => array(
			'type' => 'text',
		),
		'FacebookPostId' => array(
			'type' => 'varchar',
		),
		'RessourceID' => array(
			'fk' => 'PostRessource',
		),
		'Dynamic' => array(
			'type' => 'boolean'
		)
	);


	public function beforeSave()
	{
		$history = $this->getArray('UpdateHistory') ?: array();
		$user = \Session::getInstance()->getCurUser();

		if ($user) {

			$_history = array(
				'user' => \Session::getInstance()->getCurUser()->ID,
				'action' => $this->saved ? 'update' : 'add',
				'visible' => $this->Visible,
				'date' => date('Y-m-d H:i:s'),
			);

			$history[] =  $_history;
		}

		$this->setJson('UpdateHistory', $history);
	}



	public static function getByAlias($alias)
	{
		$id = \DB::scallar('SELECT `ID` FROM ' . static::wrapField(static::$table) . ' WHERE `Alias`=?', array($alias));
		if (!$id)
			return null;
		return new self($id);
	}

	public function getLink($lang = null)
	{
		if ($this->get('PostCategorie')) {
			$postAlias = \Tools::getAlias($this->get('Label', $lang));
			if ($postAlias)
				$postAlias .= '-';
			return \URL::link(\Tools::getAlias($this->get('PostCategorie')->get('Alias', $lang)) . '/' . \Tools::getAlias($this->get('Alias')), $lang);
		}
		return \URL::link(\Tools::getAlias($this->get('Alias')), $lang);
	}

	public function getFileName()
	{
		$name = \Tools::getAlias($this->get('Label'));
		$ext = pathinfo($this->get('File'), PATHINFO_EXTENSION);
		return \Tools::getAlias($name) . '.' . $ext;
	}

	public function getFilesLinks($file =  null)
	{
		$files  =  $this->getArray('File', true);
		$base_path_file = \Config::get('path-docs-posts');

		$files_links = [];
		foreach ($files as $file) {
			$files_links[] = 	\GoogleStorage::getUrl($base_path_file . $file);
		}
		return $files_links;
	}

	public function getFileLink($file =  null)
	{
		if ($file) {
			return \GoogleStorage::getUrl(\Config::get('path-docs-posts') . $file);
		}

		return \GoogleStorage::getUrl(\Config::get('path-docs-posts') . $this->get('File'));
	}


	public function getFile()
	{
		return \GoogleStorage::getUrl(\Config::get('path-docs-posts') . $this->get('File'));
	}


	public function getLinkOld($lang = null)
	{
		$postAlias = \Tools::getAlias($this->get('Label', $lang));
		if ($postAlias)
			$postAlias .= '-';
		if ($this->get('PostCategorie'))
			return \URL::base($this->get('PostCategorie')->get('Alias') . '/' . $postAlias . $this->getPK(true), $lang);
		return \URL::base($postAlias . $this->getPK(true), $lang);
	}

	public function getImage()
	{
		$url = $this->get('Image') ? \GoogleStorage::getUrl(\Config::get('path-images-posts') . $this->get('Image')) : null;

		if (!$url) {
			return  '';
		}

		return $url;
	}

	public function getViews()
	{

		return $this->get('Views') ? $this->get('Views') : 0;
	}

	public function views()
	{
		return  PostView::getList(array('where' => array('Post' => $this->get('ID'))));
	}

	public function viewedBy($user)
	{
		$views = PostView::getList(array('where' => array('Post' => $this->get('ID', null, false), 'User' => $user->ID)));
		if (count($views) == 0) {
			return null;
		}
		return $views[0];
	}

	public function getDocument()
	{
		if ($this->get('Content'))
			return \URL::base() . \Config::get('path-docs-posts') . $this->get('Content');
	}

	public function getCommentaires()
	{

		$items = PostCommentaire::getList(array('where' => array('Post' => $this->get('ID', null, false), 'Parent IS NOT NULL')));

		return $items;
	}

	public function getDevoirs($file = null, $done = null)
	{
		$where =  array('Post' => $this->get('ID', null, false));
		if ($file) {
			$where[] = 'Files IS NOT NULL AND Files NOT LIKE \'\'';
		}
		if ($done) {
			$where[] = 'Fait  = 1 ';
		}
		$items = PostDevoir::getList(array('where' => $where));
		return $items;
	}

	public function getQuestionnairesReponses()
	{
		$responses = $this->getArray('Questionnaire', false, true);
		if (!isset(array_values($responses)[0]['label'])) {
			return json_encode($this->get('Questionnaire'));
		}
		return  ['Oui', 'Non'];
	}

	public function getQuestionnaires()
	{

		$items = PostQuestionnaire::getList(array('where' => array('Post' => $this->get('ID', null, false))));

		return $items;
	}

	public function getQuestionnairesPct()
	{

		$qsts = PostQuestionnaire::getCount(array('where' => array('Post' => $this->get('ID', null, false))));
		return  array('count' => $qsts, 'views' => $this->getViews(), 'pct' => $qsts * 100 / ($this->getViews() ?: 1));
	}

	public function getCountCommentaires()
	{
		return PostCommentaire::getCount(array('where' => array('Post' => $this->get('ID', null, false), 'Parent IS NOT NULL')));
	}


	public function getCountDevoirFait()
	{
		return PostDevoir::getCount(array('where' => array('Post' => $this->get('ID', null, false), 'Fait' => true)));
	}


	public function getEleveDevoir($inscription)
	{

		$items = PostDevoir::getList(array('where' => array('Inscription' => $inscription->get('ID'), 'Post' => $this->get('ID', null, false))));

		return $items ? $items[0] : null;
	}

	public function getEleveSondage($inscription, $parent)
	{

		$items = PostQuestionnaire::getList(array('where' => array('Inscription' => $inscription->get('ID'), 'Inscription' => $inscription->get('ID'), 'Post' => $this->get('ID', null, false))));

		return $items ? $items[0] : null;
	}


	public function pctQuestionnaires()
	{

		$responses = $this->getQuestionnairesReponses() ?: array();

		$questionnaires = $this->getQuestionnaires() ?: array();
		$prt =  array();
		// foreach ($questionnaires as $item) {
		// 	foreach ($responses as $index => $value) {
		// 		if ($item->get('Reponse') == $value)
		// 			$prt[$index] = 	isset($prt[$index])  ? $prt[$index] + 1 : 1;
		// 		else {
		// 			$prt[$index] = 	isset($prt[$index])  ? $prt[$index] + 0 : 0;
		// 		}
		// 	}
		// }

		return $prt;
	}

	public function _pctQuestionnaires()
	{

		$ouiNon = array(
			'oui' => array(
				'label' => 'Oui',
				'pct' => 0,
			),
			'non' => array(
				'label' => 'Non',
				'pct' => 0,
			),
		);

		$questionnaires = $this->getQuestionnaires();
		$countQuestionnaires = count($questionnaires);
		$countOui = 0;
		foreach ($questionnaires as $item) {

			if ($item->get('Reponse'))
				$countOui++;
		}

		if (!$countQuestionnaires || $countQuestionnaires == 0)
			return $ouiNon;

		$prcOui = \Tools::numberFormat(($countOui * 100) / $countQuestionnaires, 0);
		$prcNon =  \Tools::numberFormat(100 - $prcOui, 0);

		$ouiNon['oui']['pct'] = $prcOui;
		$ouiNon['non']['pct'] = $prcNon;

		return $ouiNon;
	}

	public function getClasses()
	{
		$postClasses = array();
		$items = PostClasse::getList(array('where' => array('Post' => $this->get('ID', null, false))));
		if (!$items)
			return array();
		foreach ($items as $item)
			if ($item->get('Classe'))
				$postClasses[$item->get('Classe')->get('ID')] = $item;
		return $postClasses;
	}

	public function getClassesLabel()
	{
		$postClassesLabel = '';
		$items = PostClasse::getList(array('where' => array('Post' => $this->get('ID', null, false))));
		if (!$items)
			return $postClassesLabel;
		foreach ($items as $item)
			$postClassesLabel .= $item->get('Classe')->get('Label');

		return $postClassesLabel;
	}

	public function deleteClasses()
	{
		$postClasses = $this->getClasses();
		foreach ($postClasses as $item)
			$item->delete();
	}

	public function deleteViews()
	{
		$views = PostView::getList(array('where' => array('Post' => $this->get('ID', null, false))));
		if (!$views)
			return array();

		foreach ($views as $item)
			$item->delete();
	}


	public function deleteServices()
	{
		$items = PostService::getList(array('where' => array('Post' => $this->get('ID', null, false))));
		foreach ($items as $item) {
			$item->delete();
		}
	}

	public function deleteDevoirs()
	{
		$items = PostDevoir::getList(array('where' => array('Post' => $this->get('ID', null, false))));
		foreach ($items as $item) {
			$item->delete();
		}
	}


	public function getServices()
	{
		$postServices = array();
		$items = PostService::getList(array('where' => array('Post' => $this->get('ID', null, false))));
		if (!$items)
			return array();
		foreach ($items as $item) {
			try {
				$postServices[$item->get('Service')->get('ID')] = $item;
			} catch (\Exception $e) {
				continue;
			}
		}
		return $postServices;
	}


	public function getCycles()
	{
		$postCycles = array();
		$items = PostCycle::getList(array('where' => array('Post' => $this->get('ID', null, false))));
		if (!$items)
			return array();
		foreach ($items as $item)
			$postCycles[] = $item;
		return $postCycles;
	}

	public function deleteCycles()
	{
		$items = PostCycle::getList(array('where' => array('Post' => $this->get('ID', null, false))));
		foreach ($items as $item) {
			$item->delete();
		}
	}

	public function getNiveaux()
	{
		$postNiveaux = array();
		$items = PostNiveau::getList(array('where' => array('Post' => $this->get('ID', null, false))));
		if (!$items)
			return array();
		foreach ($items as $item)
			$postNiveaux[] = $item;
		return $postNiveaux;
	}

	public function deleteNiveaux()
	{
		$postNiveaux = $this->getNiveaux();
		foreach ($postNiveaux as $item)
			$item->delete();
	}

	public function getEleves()
	{
		$postEleves = array();
		$items = PostEleve::getList(array('where' => array('Post' => $this->get('ID', null, false))));
		if (!$items)
			return array();
		foreach ($items as $item)
			if ($item->get('Eleve'))
				$postEleves[$item->get('Eleve')->get('ID')] = $item;
		return $postEleves;
	}

	public function deleteEleves()
	{
		$postEleves = $this->getEleves();
		foreach ($postEleves as $item)
			$item->delete();
	}

	public function getAccess()
	{
		$htmlAccess = '';
		if ($this->get('Public')) {
			$htmlAccess .= '<span class="tag tag-sm tag-primary " style="display: inline-block;">Site public</span>';
		}

		if ($this->get('Parents')) {

			$htmlAccess .= '<span class="tag tag-sm tag-info " style="display: inline-block;">Tous les parents </span>';
		}

		if ($this->get('Teachers')) {
			$htmlAccess .= '<span class="tag tag-sm tag-info " style="display: inline-block;">Aux enseignants</span>';
		}

		$niveaux = $this->getNiveaux();
		$classes = $this->getClasses();
		$cycles = $this->getCycles();
		$services = $this->getServices();
		$tagsEleves = $this->getElevesTags();


		if ($niveaux) {
			if (count($niveaux) > 3) {
				$htmlAccess .= '<span class="tag tag-sm tag-info ">' . count($niveaux) . ' Classes</span>';
			} else {
				foreach ($niveaux as $cn) {
					$htmlAccess .= '<span class="tag tag-sm tag-info ">' . $cn->get('Niveau')->get('Label') . ($cn->get('Site') ? ' - ' . $cn->get('Site')->get('Label') : '') . '</span> ';
				}
			}
		}

		if ($services) {
			if (count($services) > 3) {
				$htmlAccess .= '<span class="tag tag-sm tag-info ">' . count($services) . ' Services</span>';
			} else {
				foreach ($services as $cn) {
					$htmlAccess .= '<span class="tag tag-sm tag-info ">' . $cn->get('Service')->get('Label') . '</span> ';
				}
			}
		}

		if ($cycles) {

			if (count($cycles) > 3) {
				$htmlAccess .= '<span class="tag tag-sm tag-info ">' . count($cycles) . ' Cycles</span>';
			} else {
				foreach ($cycles as $cn) {
					$htmlAccess .= '<span class="tag tag-sm tag-info ">' . $cn->get('Cycle')->get('Label') . ($cn->get('Site') ? ' - ' . $cn->get('Site')->get('Label') : '') . '</span> ';
				}
			}
		} elseif ($classes) {
			if (count($classes) > 3) {
				$htmlAccess .= '<span class="tag tag-sm tag-info ">' . count($classes) . ' Classes</span>';
			} else {
				foreach ($classes as $cl) {
					$htmlAccess .= '<span class="tag tag-sm tag-info ">' . $cl->get('Classe')->get('Label') . '</span> ';
				}
			}
		} elseif ($tagsEleves) {
			if ($tagsEleves) {
				if (count($tagsEleves) > 3) {
					$htmlAccess .= '<span class="tag tag-sm tag-info ">' . count($tagsEleves) . ' Elèves</span>';
				} else {
					foreach ($tagsEleves as $el) {
						$htmlAccess .= '<span class="tag tag-sm tag-info ">' . $el->get('Eleve')->get('User')->getNomComplet() . '</span> ';
					}
				}
			}
		} else {
			$eleves = $this->getEleves();
			if ($eleves) {
				if (count($eleves) > 3) {
					$htmlAccess .= '<span class="tag tag-sm tag-info ">' . count($eleves) . ' Elèves</span>';
				} else {
					foreach ($eleves as $el) {
						$htmlAccess .= '<span class="tag tag-sm tag-info ">' . $el->get('Eleve')->get('User')->getNomComplet() . '</span> ';
					}
				}
			}
		}

		if (!$htmlAccess) {
			$htmlAccess .= '<span class="tag tag-sm tag-danger ">Non definie</span>';
		}

		return $htmlAccess;
	}

	public function getElevesTags()
	{
		$inscriptionsIds = [];
		foreach ($this->getTags() as $item) {
			$inscriptionsIds = array_merge($inscriptionsIds, $item->getInscriptionsIds());
		}
		return 	$inscriptionsIds ? Inscription::getList(array('where' => array('ID IN (' . implode(',', $inscriptionsIds) . ')'))) : array();
	}


	public function _getAccess()
	{
		$htmlAccess = '';
		if ($this->get('Public')) {
			$htmlAccess .= '<span class="tag tag-sm tag-primary " style="display: inline-block;">Site public</span>';
		} elseif ($this->get('Parents')) {
			$htmlAccess .= '<span class="tag tag-sm tag-info " style="display: inline-block;">Aux parents</span>';
		} elseif ($this->get('Teachers')) {
			$htmlAccess .= '<span class="tag tag-sm tag-info " style="display: inline-block;">Aux enseignants</span>';
		} else {
			$niveaux = $this->getNiveaux();
			$classes = $this->getClasses();
			if ($niveaux) {
				if (count($niveaux) > 3) {
					$htmlAccess .= '<span class="tag tag-sm tag-info ">' . count($niveaux) . ' Classes</span>';
				} else {
					foreach ($niveaux as $cn) {
						$htmlAccess .= '<span class="tag tag-sm tag-info ">' . $cn->get('Niveau')->get('Label') . '</span> ';
					}
				}
			} elseif ($classes) {
				if (count($classes) > 3) {
					$htmlAccess .= '<span class="tag tag-sm tag-info ">' . count($classes) . ' Classes</span>';
				} else {
					foreach ($classes as $cl) {
						$htmlAccess .= '<span class="tag tag-sm tag-info ">' . $cl->get('Classe')->get('Label') . '</span> ';
					}
				}
			} else {
				$eleves = $this->getEleves();
				if ($eleves) {
					if (count($eleves) > 3) {
						$htmlAccess .= '<span class="tag tag-sm tag-info ">' . count($eleves) . ' Elèves</span>';
					} else {
						foreach ($eleves as $el) {
							$htmlAccess .= '<span class="tag tag-sm tag-info ">' . $el->get('Eleve')->get('User')->getNomComplet() . '</span> ';
						}
					}
				} else {
					$htmlAccess .= '<span class="tag tag-sm tag-danger ">Non definie</span>';
				}
			}
		}
		return $htmlAccess;
	}

	public function getContent()
	{
		$htmlAccess = '';
		if (true) {
			$htmlAccess .= $this->get('Content');
		} elseif ($this->get('PostFormat')->get('Alias') == 'image') {
			$htmlAccess .= '<img src="' . $this->get('Image') . '">';
		} elseif ($this->get('PostFormat')->get('Alias') == 'document') {
			$htmlAccess .= '<div class="card-body collapse in">						
								<div class="row card-block ">
									<div class="col-md-3">
										<a target="_blank" href=' . \URL::absolute($this->getDocument()) . ' class="btn btn-float btn-float-lg btn-outline-cyan"><i class="fa fa-cloud-download"></i><span>Télécharger</span></a>
									</div>
								</div>
							 </div>';
		} elseif ($this->get('PostFormat')->get('Alias') == 'event') {
			$htmlAccess .= '
                            <div class="media">
								<blockquote class="card-blockquote">
									' . $this->get('Content') . '
								  </blockquote>
                                <div class="media-left media-middle bg-primary position-relative callout-arrow-left px-2">
                                    <i class="fa fa-calendar fa-lg white font-medium-5"></i>
                                </div>
                                <div class="media-body p-1">
                                    <strong>De : ' . \Tools::DateFormat($this->Get('EventDateStart')) . '</strong><br />
                                    <strong>Au : ' . \Tools::DateFormat($this->Get('EventDateEnd')) . '</strong>                                    
                                </div>
                            </div>';
		} elseif ($this->get('PostFormat')->get('Alias') == 'video') {
			$htmlAccess .= '<div class="embed-responsive embed-responsive-item embed-responsive-4by3">
								<iframe class="img-thumbnail" src="https://www.youtube.com/embed/' . $this->get('Content') . '?rel=0&amp;controls=0&amp;showinfo=0" allowfullscreen=""></iframe>
							</div>';
		}
		return $htmlAccess;
	}



	public static function countContenuPartages($dateDebut, $dateFin)
	{

		$query = <<<END
		SELECT DATE_FORMAT(Date,'%Y-%m') AS Date, count(*) AS Count
		FROM com_posts
		where 
			`Date` BETWEEN ? AND ? 
			AND
			`PostCategorie` NOT IN (3,4)
		group by DATE_FORMAT(Date,'%Y-%m')
		ORDER by DATE_FORMAT(Date,'%Y-%m') ASC
END;

		$params = array($dateDebut, $dateFin);

		$result = \DB::reader($query, $params);

		$response = array();

		foreach ($result as $data) {

			$response[$data['Date']] = array(
				'Count' => $data['Count'],
			);
		}

		return ($response);
	}

	public static function countDevoirsartages($dateDebut, $dateFin)
	{

		$query = <<<END
		SELECT DATE_FORMAT(Date,'%Y-%m') AS Date, count(*) AS Count
		FROM com_posts
		where 
			`Date` BETWEEN ? AND ? 
			AND
			`PostCategorie` NOT IN (3,4)
		group by DATE_FORMAT(Date,'%Y-%m')
		ORDER by DATE_FORMAT(Date,'%Y-%m') ASC
END;

		$params = array($dateDebut, $dateFin);

		$result = \DB::reader($query, $params);

		$response = array();

		foreach ($result as $data) {

			$response[$data['Date']] = array(
				'Count' => $data['Count'],
			);
		}

		return ($response);
	}


	public static function myContents($user)
	{
		$items = array();

		$items = parent::getList(array('where' => array('User' => $user->get('ID'))));

		if (!$items)
			return array();


		return $items;
	}


	public static function _getList($args = null, $query = null)
	{
		$user = \Session::getInstance()->getCurUser();

		if (!is_array($args))
			$args = array();
		if (!$query)
			$query = Post::sqlQuery();

		if (roleIs('admin')) {
			return parent::getList($args, $query);
		}

		if (roleIs('educatrice')) {
			$args['where'][] = "User = " . $user->get('ID');
			return parent::getList($args, $query);
		}
		// var_dump($user->get('Classes'));
		// exit;
		if ($user && $user->get('Classes')) {
			$classes =  $user->get('Classes');
			$query .= <<<END
			JOIN (SELECT `ID` AS `JM1ID`, `Post` AS `JM1Post`, `Classe` AS `JM1Classe` FROM `com_postclasses`  WHERE Classe IN ("$classes")) AS `jm_1` ON `com_posts`.`ID` = `jm_1`.`JM1Post` 

			JOIN (SELECT `ID` AS `JM2ID`, `Post` AS `JM2Post`, `Eleve` AS `JM2Eleve` FROM `com_posteleves`  WHERE Eleve IN (SELECT Eleve FROM ins_inscriptions WHERE Classe IN ("$classes") )) AS `jm_2` ON `com_posts`.`ID` = `jm_2`.`JM2Post` 
END;
			// var_dump($query);
			// exit;
		}


		return parent::getList($args, $query);
	}

	//OLD CODE
	/*
	public static function getList($args = null, $query = null)
	{
		$user = \Session::getInstance()->getCurUser();

		if (!is_array($args))
			$args = array();
		if (!$query)
			$query = Post::sqlQuery();

		if (roleIs('admin')) {
			return parent::getList($args, $query);
		}

		if (roleIs('educatrice')) {
			$args['where'][] = "User = " . $user->get('ID');
			return parent::getList($args, $query);
		}
		// var_dump($user->get('Classes'));
		// exit;
		if ($user && $user->get('Classes')) {
			$classes =  $user->get('Classes');
			$args['where'][] = '(ID IN (SELECT Post FROM `com_postclasses`  WHERE Classe IN ("' . $classes . '")) OR ID IN (SELECT POST FROM `com_posteleves` WHERE Eleve IN (SELECT Eleve FROM ins_inscriptions WHERE Classe IN ("' . $classes . '") )))';
			// var_dump($query);
			// exit;
		}


		return parent::getList($args, $query);
	}*/


	public static function getListOld($args = null, $query = null)
	{

		$user = \Session::getInstance()->getCurUser();

		if ($user && $user->is('admin')) {
			return parent::getList($args, $query);
		}

		if ($user && $user->is('educatrice')) {
			return Post::myContents($user);
		}

		if (!is_array($args))
			$args = array();

		if (!$query)
			$query = Post::sqlQuery();

		if ($user && $user->get('Classes')) {
			$query .= <<<END
		LEFT OUTER JOIN (SELECT `ID` AS `JM1ID`, `Post` AS `JM1Post`, `Classe` AS `JM1Classe` FROM `com_postclasses`) AS `jm_1` ON `com_posts`.`ID` = `jm_1`.`JM1Post`
		LEFT OUTER JOIN (SELECT `ID` AS `JM2ID`, `Post` AS `JM2Post`, `Eleve` AS `JM2Eleve` FROM `com_posteleves`) AS `jm_2` ON `com_posts`.`ID` = `jm_2`.`JM2Post`
		LEFT OUTER JOIN (SELECT `ID` AS `JM3ID`, `Classe` AS `JM3Classe`, `Eleve` AS `JM3Eleve` FROM `ins_inscriptions`) AS `jm_3` ON `jm_2`.`JM2Eleve` = `jm_3`.`JM3Eleve`  
			
END;
			$classes =  $user->get('Classes');
			$args['where'][] = "( User = " . $user->get('ID') . " OR JM1Classe IN (" . $classes . ") OR JM3Classe IN (" . $classes . "))";
			//	$args['query'] = "GROUP BY ID";
		}

		return parent::getList($args, $query);
	}

	public static function getList($args = null, $query = null)
	{

		$user = \Session::getInstance()->getCurUser();

		if ($user && $user->is('admin')) {
			return parent::getList($args, $query);
		}

		if ($user && $user->is('educatrice')) {
			return Post::myContents($user);
		}

		if (!is_array($args))
			$args = array();

		if (!$query)
			$query = Post::sqlQuery();

		if ($user && $user->get('Classes')) {
			$classes =  $user->get('Classes');
			$_query  = "(User = " . $user->get('ID') . " ";

			
			$_query .=  "OR ID IN (SELECT `Post` FROM `com_postclasses` where Classe IN ($classes))) ";
			// $_query .=  "OR ID IN (SELECT `Post`  FROM `com_posteleves` where  Eleve IN (SELECT `Eleve` FROM `ins_inscriptions` where Classe IN ($classes))) ";
			// $_query .=  "OR ID IN (SELECT `Post`  FROM `com_postdevoirs` where  Eleve IN (SELECT `Eleve` FROM `ins_inscriptions` where Classe IN ($classes))))";
			// }
			$args['where'][] =  $_query;
		}

		return parent::getList($args, $query);
	}



	public static function __getList($args = null, $query = null)
	{

		$user = \Session::getInstance()->getCurUser();

		if ($user && $user->is('admin')) {
			return parent::getList($args, $query);
		}

		if ($user && $user->is('educatrice')) {
			return Post::myContents($user);
		}

		if (!is_array($args))
			$args = array();

		if (!$query)
			$query = Post::sqlQuery();

		if ($args) {
		} else {

			if ($user && $user->get('Classes')) {
				$classes =  $user->get('Classes');
				$user_id = $user->get('ID');
				$query .= <<<END
				LEFT OUTER JOIN (SELECT `ID` AS `JM1ID`, `Post` AS `JM1Post`, `Classe` AS `JM1Classe` FROM `com_postclasses`) AS `jm_1` ON `com_posts`.`ID` = `jm_1`.`JM1Post`
				LEFT OUTER JOIN (SELECT `ID` AS `JM2ID`, `Post` AS `JM2Post`, `Eleve` AS `JM2Eleve` FROM `com_posteleves`) AS `jm_2` ON `com_posts`.`ID` = `jm_2`.`JM2Post`
				LEFT OUTER JOIN (SELECT `ID` AS `JM3ID`, `Classe` AS `JM3Classe`, `Eleve` AS `JM3Eleve` FROM `ins_inscriptions`) AS `jm_3` ON `jm_2`.`JM2Eleve` = `jm_3`.`JM3Eleve`
				where ( User =  $user_id  OR JM1Classe IN ("$classes") OR JM3Classe IN ("$classes")) 
END;
			}
		}


		// var_dump($query);
		// // var_dump($args);
		// exit;
		return parent::getList($args, $query);
	}


	public function getTags($inscription = null)
	{
		$where = array();
		$where['Post'] = $this->get('ID');

		if ($inscription && !$this->Parents)
			$where[] = '( ( Inscriptions LIKE \'%"' . $inscription->get('ID') . '"%\')' . ($this->getClasses() || count($this->getEleves()) == 1 ? ' OR ( `Inscriptions` IS NULL )' : '') . ' )';
		// $where[] = '( ( Inscriptions LIKE \'%"' . $inscription->get('ID') . '"%\')'.($this->getClasses()?' OR ( `Inscriptions` IS NULL )':'').' )';

		$items = PostTag::getList(array('where' => $where));
		if (!$items)
			return array();

		return $items;
	}

	public function deleteTags()
	{
		$tags = PostTag::getList(array('where' => array('Post' => $this->get('ID', null, false))));
		if (!$tags)
			return array();
		foreach ($tags as $item)			$item->delete();
	}
}
