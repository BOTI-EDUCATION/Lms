<?php

namespace Models;

class User extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'users';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Role' => array(
			'fk' => 'Role',
		),
		'AdditionRoles' => array(
			'type' => 'varchar',
		),
		'Login' => array(
			'type' => 'varchar',
		),
		'Password' => array(
			'type' => 'varchar',
		),
		'Key' => array(
			'type' => 'varchar',
			'required' => true,
		),
		'Forgot' => array(
			'type' => 'varchar',
		),
		'CodeAuth' => array(
			'type' => 'varchar',
		),
		'TokenID' => array(
			'type' => 'varchar',
		),
		'TokenDevice' => array(
			'type' => 'varchar',
		),
		'Nom' => array(
			'type' => 'varchar',
		),
		'Prenom' => array(
			'type' => 'varchar',
		),
		'Email' => array(
			'type' => 'varchar',
		),
		'Tel' => array(
			'type' => 'varchar',
		),
		'SecondTel' => array(
			'type' => 'varchar',
		),
		'Adresse' => array(
			'type' => 'varchar',
		),
		'Fonction' => array(
			'type' => 'varchar',
		),
		'CIN' => array(
			'type' => 'varchar',
		),
		'Image' => array(
			'type' => 'varchar',
		),
		'DateNaissance' => array(
			'type' => 'date',
		),
		'Homme' => array(
			'type' => 'boolean',
		),
		'Enabled' => array(
			'type' => 'tinyint',
		),
		'EnableSmsNotif' => array(
			'type' => 'tinyint',
		),
		'EnableFncSmsNotif' => array(
			'type' => 'tinyint',
		),
		'Classes' => array(
			'type' => 'varchar',
		),
		'Date' => array(
			'type' => 'datetime',
		),
		'DateEnter' => array(
			'type' => 'datetime',
		),
		'EditHistory' => array(
			'type' => 'text',
		),
		'Autorisations' => array(
			'type' => 'text',
		),
		'AutorisationsHistory' => array(
			'type' => 'text',
		),
		'DeletedAt' => array(
			'type' => 'text',
		),
	);


	public function beforeSave()
	{
		$history = $this->getArray('EditHistory') ?: array();
		$user = \Session::getInstance()->getCurUser();
		if ($user) {
			$history[] = array(
				'user' => $user->ID,
				'action' => $this->saved ? 'update' : 'add',
				'role' => $this->getMainRole()->ID,
				'role_label' => $this->getMainRole()->Label,
				'date' => date('Y-m-d H:i:s'),
			);
		}
		$this->setJson('EditHistory', $history);
	}

	public function getMainRole()
	{
		return parent::get('Role', null, null);
	}

	public function additionRoles()
	{

		return	$this->getArray('AdditionRoles', false, true) ? $this->getArray('AdditionRoles', false, true) : [];
	}

	// public function get($field, $lang = null, $hash = true)
	// {
	// 	if ($field == 'Role' && \Session::user() && \Session::user()->ID == $this->ID) {
	// 		if (isset($_SESSION[_school_alias . '_role'])) {
	// 			return new Role($_SESSION[_school_alias . '_role']);
	// 		}
	// 		return  parent::get($field, $lang, $hash);
	// 	}

	// 	return parent::get($field, $lang, $hash);
	// }


	public function is($alais)
	{
		if ($role = $this->get('Role')) {
			return $role->get('Alias') == $alais;
		}
		return false;
	}

	public static function firstAdmin()
	{
		$admins = self::getList(array('where' => array(' Role IN (SELECT ID FROM roles WHERE Alias = "admin")')));

		if (count($admins)) {
			return $admins[0];
		}
		return null;
	}

	public function _getFullLinkImage()
	{
		if (!$this->get('Image') || !file_exists(_basepath . \Config::get('path-images-users') . $this->get('Image'))) {
			if ($this->get('Homme'))
				$image = 'male_prof.svg';
			else
				$image = 'female_prof.svg';

			return \URL::absolute() . \URL::base() . '/assets/icons/' . $image;
		}

		return \URL::absolute() . \Config::get('path-images-users') . $this->get('Image');
	}

	public function _getImage()
	{
		$path_images_users = _basepath . \Config::get('path-images-users');
		$path_uri_images_users = \URL::base()  . \Config::get('path-images-users');

		if (!$this->get('Image') || !file_exists($path_images_users . $this->get('Image'))) {
			if ($this->get('Role') && $this->get('Role')->get('Alias') == 'eleve') {
				if ($this->get('Homme'))
					$image = 'no-user-man.svg';
				else
					$image = 'no-user-woman.svg';
			} else {

				if ($this->get('Homme'))
					$image = 'man.jpg';
				else
					$image = 'woman.jpg';
			}
			return \URL::base() . 'assets/icons/' . $image;
		}

		if (file_exists($path_images_users . 'small/' . $this->get('Image'))) {
			return $path_uri_images_users . 'small/' . $this->get('Image');
		}
		return $path_uri_images_users . $this->get('Image');
	}

	public function _getSmallImage()
	{
		$path_images_users = _basepath . \Config::get('path-images-users');
		$path_uri_images_users = \URL::base()  . \Config::get('path-images-users');

		if (!$this->get('Image') || !file_exists($path_images_users . $this->get('Image'))) {
			if ($this->get('Role') && $this->get('Role')->get('Alias') == 'eleve') {
				if ($this->get('Homme'))
					$image = 'no-user-man.svg';
				else
					$image = 'no-user-woman.svg';
			} else {

				if ($this->get('Homme'))
					$image = 'man.jpg';
				else
					$image = 'woman.jpg';
			}
			return \URL::base() . 'assets/icons/' . $image;
		}

		if (file_exists($path_images_users . 'small/' . $this->get('Image'))) {
			return $path_uri_images_users . 'small/' . $this->get('Image');
		}

		return $path_uri_images_users . $this->get('Image');
	}

	public function _getSmallSizeImage()
	{
		$path_images_users  = _basepath . \Config::get('path-images-users');

		if (!$this->get('Image') || !file_exists($path_images_users . $this->get('Image'))) {
			if ($this->get('Role') && $this->get('Role')->get('Alias') == 'eleve') {

				if ($this->get('Homme'))
					$image = 'no-user-man.svg';
				else
					$image = 'no-user-woman.svg';
			} else {

				if ($this->get('Homme'))
					$image = 'man.jpg';
				else
					$image = 'woman.jpg';
			}

			return \URL::base() . 'assets/icons/' . $image;
		}

		if (!file_exists($path_images_users . 'small')) {
			mkdir($path_images_users . 'small');
		}

		if (!file_exists($path_images_users . 'small/' . $this->get('Image'))) {
			\Tools::smart_resize_image($path_images_users . $this->get('Image'), null, 500, 400, true, $path_images_users . 'small/' . $this->get('Image'), false, false, 75);
		}

		return \URL::base() . \Config::get('path-images-users') . 'small/' . $this->get('Image');
	}


	public function getFullLinkImage()
	{

		// $url  = $this->get('Image') ? \GoogleStorage::getUrl(\Config::get('path-images-users') . $this->get('Image')) : null;
		$url  = null;
		if (!$url) {
			if ($this->get('Homme'))
				$image = 'male_prof.svg';
			else
				$image = 'female_prof.svg';

			return \URL::absolute() . \URL::base() . '/assets/icons/' . $image;
		}

		return  $url;
	}



	public function getImage()
	{

		// $url  = $this->get('Image') ?  \GoogleStorage::getUrl(\Config::get('path-images-users') . $this->get('Image')) : null;
		$url  =  null;

		if (!$url) {
			if ($this->get('Role') && $this->get('Role')->get('Alias') == 'eleve') {
				if ($this->get('Homme'))
					$image = 'no-user-man.svg';
				else
					$image = 'no-user-woman.svg';
			} else {

				if ($this->get('Homme'))
					$image = 'man.jpg';
				else
					$image = 'woman.jpg';
			}
			return \URL::base() . 'assets/icons/' . $image;
		}


		return $url;
	}

	public function getSmallImage()
	{
		return $this->getImage();
	}

	public function getSmallSizeImage()
	{
		return $this->getImage();
	}

	//---------------------Divers
	public function getNomComplet()
	{
		return implode(' ', array($this->get('Nom'), $this->get('Prenom')));
	}

	//---------------------Authentification
	public static function auth($login, $password)
	{
		$query = 'SELECT `ID` FROM `users` WHERE ( TRIM(`Login`)=:login OR TRIM(`Email`)=:login OR TRIM(`Tel`=:login) OR ( ID IN ( SELECT User FROM gen_eleves where Massar = :login ))) AND `Password`=SHA1(CONCAT(:password,`Key`)) ORDER BY Nom DESC';
		//$query = 'SELECT `ID` FROM `users` WHERE ( TRIM(`Login`)=:login OR TRIM(`Email`)=:login OR TRIM(`Tel`=:login) ) AND `Password`=SHA1(CONCAT(:password,`Key`)) ORDER BY Nom DESC';

		$params = array('login' => $login, 'password' => $password);

		$userid = \DB::scallar($query, $params);

		if (!$userid)
			return NULL;
		return new self($userid);
	}

	public static function phone_auth($phone)
	{
		$query = "SELECT ID FROM users WHERE REPLACE(REPLACE(Tel, '-', ''), ' ', '') LIKE '%" . $phone . "'";

		$params = array();

		$userid = \DB::scallar($query, $params);

		if (!$userid)
			return NULL;
		return new self($userid);
	}

	public static function auth_code($code)
	{

		$query = 'SELECT `ID` FROM `users` WHERE (`CodeAuth`=:codeauth)';

		$params = array('codeauth' => $code);

		$userid = \DB::scallar($query, $params);

		if (!$userid)
			return NULL;
		return new self($userid);
	}

	public static function checkPassword($id, $password)
	{
		$query = 'SELECT `ID` FROM `users` WHERE `ID`=:id AND `Password`=SHA1(CONCAT(:password,`Key`))';

		$params = array('id' => $id, 'password' => $password);

		return \DB::scallar($query, $params);
	}

	//---------------------Check Unique Values
	public static function loginExists($login)
	{

		if (!$login)
			return false;

		$database = \DB::getInstance();

		$query = 'SELECT `ID` FROM `users` WHERE `Login`=?';
		$params = array($login);

		return \DB::scallar($query, $params);
	}


	public function horaireByJourSemaine()
	{
		$horaireByJourSemaine = array();
		$horaires = CollaborateurHoraire::getList(array('where' => array('Collaborateur' => $this->get('ID'))));
		foreach ($horaires as $item) {
			$horaireByJourSemaine[$item->get('JourSemaine')] = $item;
		}

		return $horaireByJourSemaine;
	}

	public static function tokenExist($token)
	{
		$query = 'SELECT `ID` FROM `users` WHERE SHA1(`Key`) = :token  ';

		$params = array('token' => $token);

		$userid = \DB::scallar($query, $params);

		if (!$userid)
			return NULL;
		return new self($userid);
	}

	public static function emailExists($email)
	{

		if (!$email)
			return false;
		$database = \DB::getInstance();

		$query = 'SELECT `ID` FROM `users` WHERE `Email`=?';
		$params = array($email);

		return \DB::scallar($query, $params);
	}

	public static function getByTel($tel)
	{

		$users = self::all(array("where" => array(
			'Tel' => $tel,
		)));
		return count($users) ? $users[0] : null;
	}


	public static function getByEmail($email)
	{

		$users = self::all(array("where" => array(
			'Email' => $email,
		)));
		return count($users) ? $users[0] : null;
	}

	public static function getBy($field, $value)
	{

		$users = self::all(array("where" => array(
			$field => $value,
		)));
		return count($users) ? $users[0] : null;
	}


	public static function telExists($tel)
	{


		if (!$tel)
			return false;

		$database = \DB::getInstance();

		$query = 'SELECT `ID` FROM `users` WHERE `Tel`=?';
		$params = array($tel);

		return \DB::scallar($query, $params);
	}
	public static function keyExists($key)
	{

		if (!$key)
			return false;

		$database = \DB::getInstance();

		$query = 'SELECT `ID` FROM `users` WHERE `Key`=?';
		$params = array($key);

		$userid = \DB::scallar($query, $params);

		if (!$userid)
			return NULL;

		return new self($userid);
	}

	//---------------------Récuperation Objets

	public static function getByKeyForgot($keyforgot)
	{
		if (!$keyforgot)
			return null;
		$users = User::getList(array('where' => array('Forgot' => $keyforgot)));
		if (!$users)
			return null;
		return $users[0];
	}

	public function getParent()
	{
		$parents = Parentt::all(array('where' => array('User' => $this->get('ID'))));
		if (!$parents)
			return null;
		return $parents[0];
	}

	public function getAideChauffeur()
	{
		$parents = Rh\Collaborateur::all(array('where' => array('User' => $this->get('ID'), 'AideChauffeur' => 1)));
		if (!$parents)
			return null;
		return $parents[0];
	}

	public function getPick()
	{
		$parents = Rh\Collaborateur::all(array('where' => array('User' => $this->get('ID'))));
		if (!$parents)
			return null;
		return $parents[0];
	}



	public function getEleve()
	{

		$eleves = Eleve::all(array('where' => array('User' => $this->get('ID'))));
		if (!$eleves)
			return null;
		return $eleves[0];
	}

	public function getEnseignant()
	{

		$enseignants = Enseignant::all(array('where' => array('User' => $this->get('ID'))));
		if (!$enseignants)
			return null;
		return $enseignants[0];
	}

	//check this function at any action or view loading
	public function HasPermission($view_or_action)
	{
		$permissions = $this->get('Role')->getPermissions();
		return in_array($view_or_action, $permissions);
	}

	public function simpleToken()
	{
		return $this->getArray('TokenID', true);;
	}

	public static function simpleTokens()
	{
		$tokens = array();
		$items = User::getList(array('where' => array('TokenID IS NOT NULL')));
		if (!$items)
			return array();
		foreach ($items as $item) {
			if ($item->get('TokenID'))
				$user_tokens = $item->getArray('TokenID', true);
			foreach ($user_tokens as $token) {
				$tokens[] = $token;
			}
		}
		return $tokens;
	}

	public function token()
	{
		$tokens = array(
			'ios' => array(),
			'android' => array(),
		);

		if ($this->get('TokenID'))
			$tokens[$this->get('TokenDevice')][] = $this->get('TokenID');

		return $tokens;
	}


	public static function tokens()
	{
		$tokens = array(
			'ios' => array(),
			'android' => array(),
		);

		$items = User::getList(array('where' => array('TokenID IS NOT NULL')));
		if (!$items)
			return null;
		foreach ($items as $item) {
			if ($item->get('TokenID'))
				$tokens[$item->get('TokenDevice')][] = $item->get('TokenID');
		}
		return $tokens;
	}


	public static function collaborateursPhones()
	{
		$phones = array();
		$items = User::getList(array('where' => array('Role NOT IN (3,2,4)')));
		if (!$items)
			return null;
		foreach ($items as $item) {
			if ($item->get('Tel'))
				$phones[] = $item->get('Tel');
		}

		return $phones;
	}

	public static function _tokens()
	{
		$tokens = array(
			'ios' => array(),
			'android' => array(),
		);

		$parrainages = Parrainage::getList(
			array('where' => array(
				'J1Promotion' =>  $_SESSION['promotion_actuelle'],
			)),
			Parrainage::sqlQuery() . <<<END
	JOIN (SELECT `ID` AS `J1ID`, `Eleve` AS `J1Eleve`, `Promotion` AS `J1Promotion`, `Classe` AS `J1Classe` FROM `ins_inscriptions`) AS `j1` ON `parrainages`.`Eleve` = `j1`.`J1Eleve`
END
		);

		if (!$parrainages)
			return null;
		foreach ($parrainages as $item) {

			if (!$item->get('Parent'))
				continue;
			if (!$item->get('Parent')->get('User'))
				continue;

			if (!$item->get('Parent')->get('User')->get('Tel'))
				continue;

			if ($item->get('Parent')->get('User')->get('TokenID'))
				$tokens[$item->get('Parent')->get('User')->get('TokenDevice')][] = $item->get('Parent')->get('User')->get('TokenID');
		}
		return $tokens;
	}

	public static function connexionsParents($dateDebut, $dateFin)
	{

		$query = <<<END
		SELECT DATE_FORMAT(Date,'%Y-%m') AS Date, count(*) AS Count
		FROM connexions JOIN (SELECT `ID` AS `J1ID`, `Role` AS `J1Role` FROM `users`) AS `j1` ON `connexions`.`User` = `j1`.`J1ID`
		where 
			`Date` BETWEEN ? AND ? 
			AND
			J1Role = 2
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

	public static function connexionsProfesseurs($dateDebut, $dateFin)
	{

		$query = <<<END
		SELECT DATE_FORMAT(Date,'%Y-%m') AS Date, count(*) AS Count
		FROM connexions JOIN (SELECT `ID` AS `J1ID`, `Role` AS `J1Role` FROM `users`) AS `j1` ON `connexions`.`User` = `j1`.`J1ID`
		where 
			`Date` BETWEEN ? AND ? 
			AND
			J1Role = 4
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

	public static function connexionsAdmins($dateDebut, $dateFin)
	{

		$query = <<<END
		SELECT DATE_FORMAT(Date,'%Y-%m') AS Date, count(*) AS Count
		FROM connexions JOIN (SELECT `ID` AS `J1ID`, `Role` AS `J1Role` FROM `users`) AS `j1` ON `connexions`.`User` = `j1`.`J1ID`
		where 
			`Date` BETWEEN ? AND ? 
			AND
			J1Role NOT IN (2,4)
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

	public function getlastConnexion()
	{

		$query = "SELECT Date FROM connexions WHERE User = ? ORDER by Date DESC LIMIT 1";
		$params = array($this->get('ID'));
		$lastConnexion = \DB::scallar($query, $params);

		if (!$lastConnexion)
			return NULL;

		return $lastConnexion;
	}


	public function getClasses()
	{
		if (!$this->get('Classes'))
			return array();

		return explode(',', $this->get('Classes'));
	}


	public function getTaches()
	{
		$id = $this->get('ID');
		$query = TI\Tache::sqlQuery(true) . <<< END
		JOIN (SELECT `ID` AS `J1ID`, `Tache` AS `J1Tache`, `User` AS `J1User` FROM `ti_tachecollaborateurs` WHERE User = $id ) AS `j1` ON `ti_taches`.`ID` = `j1`.`J1Tache` 
END;

		$ti_taches = TI\Tache::getList(null, $query);
		return $ti_taches;
	}




	public function getCollaborateur()
	{
		$collabs =  RH\Collaborateur::all(['where' => ['User' => $this->get('ID')]]);
		return isset($collabs[0]) ? $collabs[0] : null;
	}


	public static function getCollaborateurs()
	{

		return User::getList(array('where' => array('Role  IN (SELECT ID FROM roles WHERE Alias NOT IN (\'eleve\', \'parent\', \'professeur\'))')));
	}


	public static function getCollaborateursTokens()
	{

		$tokens = array();
		$items = static::getCollaborateurs();
		if (!$items)
			return array();
		foreach ($items as $item) {
			if ($item->get('TokenID'))
				$user_tokens = $item->getArray('TokenID', true);
			foreach ($user_tokens as $token) {
				$tokens[] = $token;
			}
		}
		return $tokens;
	}


	public function getLastConnection($source = null)
	{
		$args = array('where' => array(), 'order' => array());
		$args['where']['User'] = $this->ID;
		if ($source) $args['where']['Source'] = $source;
		$args['order']['Date'] = false;

		$connections = Connexion::getList($args);

		return count($connections) ? $connections[0] : null;
	}

	public static function autorisations($alias =  null)
	{

		$items  =   [
			'receive_inscriptions_email_notifications' => 'Recevoir des notifications d\'inscription par e-mail',
			'receive_reinscriptions_email_notifications' => 'Recevoir des notifications par e-mail de réinscription',
			'apply_discount_price' => 'Affecter services & Appliquer des remises de tarifs'
			// 'edit_encaissement' => 'Modifier les encaissements'
		];

		if ($alias) {
			return  isset($items[$alias]) ? $items[$alias] : null;
		}

		return $items;
	}


	public static function getList($args = null, $query = null)
	{
		if (!is_array($args))
			$args = array();

		$args['where'][] = '`DeletedAt` IS  NULL';

		return parent::getList($args, $query);
	}

	public function hasAutorisation($alias =  null)
	{
		return $this->get('Autorisations') && in_array($alias, $this->getArray('Autorisations'));
	}






	// public static function autorisations($alias =  null, $role = null)
	// {

	// 	$items  =   [
	// 		'receive_inscriptions_email_notifications' => [
	// 			'alias' => 'receive_inscriptions_email_notifications',
	// 			'label' => 'recevoir des notifications d\'inscription par e-mail',
	// 			'roles' => ['admin']
	// 		],
	// 		'apply_discount_price' => [
	// 			'alias' => 'apply_discount_price',
	// 			'label' => 'Appliquer des remises de tarifs',
	// 			'roles' => ['respo_peda']
	// 		],
	// 		'receive_reinscriptions_email_notifications' => [
	// 			'alias' => 'receive_reinscriptions_email_notifications',
	// 			'label' => 'recevoir des notifications d\'inscription par e-mail',
	// 			'roles' => ['respo_peda']
	// 		],
	// 	];


	// 	if ($alias) {
	// 		return  isset($items[$alias]) ? $items[$alias] : null;
	// 	}

	// 	if ($role) {
	// 		return array_filter($items, fn ($val) => in_array($role, $val['roles']));
	// 	}

	// 	return $items;
	// }


}
