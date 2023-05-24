<?php

namespace Models\COM;

use \Models\Model;
use Session;

class Message extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'com_messages';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(

		'User' => array(
			'fk' => 'User',
		),
		'UserTo' => array(
			'fk' => 'User',
		),
		'Channel' => array(
			'type' => 'varchar',
		),
		'Inscription' => array(
			'fk' => 'Inscription',
		),
		'MessageReference' => array(
			'fk' => 'COM\\Message',
		),
		'Theme' => array(
			'fk' => 'COM\\MessageTheme',
		),
		'Niveau' => array(
			'fk' => 'Niveau',
		),
		'NiveauAlias' => array(
			'type' => 'varchar',
		),
		'Classe' => array(
			'fk' => 'Classe',
		),
		'Cycle' => array(
			'fk' => 'Cycle',
		),

		'Sujet' => array(
			'type' => 'varchar',
		),
		'Message' => array(
			'type' => 'text',
		),
		'Audio' => array(
			'type' => 'varchar',
		),
		'Date' => array(
			'type' => 'datetime',
		),
		'Vu' => array(
			'type' => 'datetime',
		),
		'Fichiers' => array(
			'type' => 'varchar',
		),
		'Views' => array(
			'type' => 'text',
		),
		'ViewsID' => array(
			'type' => 'text',
		),
		'ViewsHistory' => array(
			'type' => 'text',
		),
		'DeletedAt' => array(
			'type' => 'datetime',
		),
	);


	public function _SeenBy($user)
	{
		$views = $this->getArray('ViewsHistory') ?: array();
		$viewsID = $this->getArray('ViewsID') ?: array();

		$views[] = [
			'user' => $user->ID,
			'date' => date('Y-m-d H:i:s'),
		];

		$viewsID[] = $user->ID;

		$this->setJson('Views', $views);

		$this->setJson('ViewsID', $viewsID);
		$this->save();
	}

	public function seenBy($user)
	{
		if ($this->User->getParent() || $this->User->getEnseignant()) {

			$views = $this->getArray('ViewsHistory', false, true) ?: [];
			$viewsID = $this->getArray('ViewsID', false, true) ?: [];

			if (!in_array($user->ID, $viewsID)) {
				$viewsID[] = $user->ID;
				$views[] = [
					'user' => $user->ID,
					'date' => date('Y-m-d H:i:s'),
				];
				$this->setJson('ViewsHistory', $views);
				$this->setJson('ViewsID', $viewsID);
			}

			$this->save();
		}
	}


	public function unreadBy($user)
	{
		if ($this->User->getParent() || $this->User->getEnseignant()) {
			$viewsID = $this->getArray('ViewsID', false, true) ?: [];
			if (in_array($user->ID, $viewsID)) {
				if (($key = array_search($user->ID, $viewsID)) !== false) {
					unset($viewsID[$key]);
				}
				$this->setJson('ViewsID', $viewsID);
			}
		}
		$this->save();
	}


	public static function messages($user = null, $count = false, $nonvue = false, $userApi = null)
	{


		if ($userApi)
			$userSession = $userApi;
		else
			$userSession = \Session::getInstance()->getCurUser();

		if (!$user)
			$args = 'UserTo =' . $userSession->get('ID');

		else
			$args = '(User =' . $userSession->get('ID') . ' AND UserTo =' . $user->get('ID')
				. ') OR (UserTo =' . $userSession->get('ID') . ' AND User =' . $user->get('ID') . ')';

		if ($nonvue)
			$args .= ' AND Vu IS NULL';

		if ($count)
			$echanges = Message::getCount(array('where' => array(
				$args
			)));
		else
			$echanges = Message::getList(array('where' => array(
				$args
			), 'order' => array(
				'Date' => false
			)));

		return $echanges;
	}


	public static function MessageReference($user, $toUser, $inscription = null)
	{

		$where = array(
			'User' => $user->get('ID'),
			'UserTo' => $toUser->get('ID'),
			'MessageReference IS NULL'
		);
		if ($inscription) {
			$where['Inscription'] = $inscription->get('ID');
		} else {
			$where[] = 'Inscription IS NULL';
		}
		$echanges = Message::getList(array('where' => $where));
		if (!count($echanges))
			return null;
		return $echanges[count($echanges) - 1]->get('ID');
	}


	public static function lastMessage($user = null)
	{

		if (!$user)
			$args = 'UserTo =' . \Session::getInstance()->getCurUser()->get('ID');

		else
			$args = '(User =' . \Session::getInstance()->getCurUser()->get('ID') . ' AND UserTo =' . $user->get('ID')
				. ') OR (UserTo =' . \Session::getInstance()->getCurUser()->get('ID') . ' AND User =' . $user->get('ID') . ')';


		$echanges = Message::getList(array('where' => array(
			$args
		), 'order' => array(
			'Date' => false
		)));


		if ($echanges)
			return $echanges[0];

		return null;
	}


	// Retourn les fichiers (liens absolus) du message en cours
	public function getFichiers($api = false)
	{
		$fichiers = array();
		if (!$this->get('Fichiers'))
			return $fichiers;

		$files = explode(',', $this->get('Fichiers'));

		if ($api) {
			$filesApi = array();
			foreach ($files as $item) {
				$filesApi[] = array(
					'link' => \GoogleStorage::getUrl(\Config::get('path-uploads') . $item),
					'name' => $item
				);
			}

			return $filesApi;
		}

		foreach ($files as $item) {
			if (!$item)
				continue;
			$link = \GoogleStorage::getUrl(\Config::get('path-uploads') . $item);
			$fichiers[] = $link;
		}
		return $fichiers;
	}


	// Retourn les fichiers (liens absolus) du message en cours
	public function _getFichiers($api = false)
	{
		$fichiers = array();
		if (!$this->get('Fichiers'))
			return $fichiers;

		$files = explode(',', $this->get('Fichiers'));

		if ($api) {
			$filesApi = array();
			foreach ($files as $item) {
				$filesApi[] = array(
					'link' => 'http://docs.google.com/viewer?url=' . encodeURIComponent(\URL::absolute(\URL::absolute(\URL::base() . \Config::get('path-uploads') . $item))),
					'name' => $item
				);
			}

			return $filesApi;
		}

		foreach ($files as $item) {
			if (!$item)
				continue;
			$link = \URL::base() . \Config::get('path-uploads') . $item;
			$fichiers[] = $link;
		}
		return $fichiers;
	}


	public function getReponse()
	{
		return Message::getList(array('where' => array('MessageReference' => $this->getPK(true))));
	}


	public static function getTree($message = null)
	{
		function messagesNestFunction($message = null)
		{

			$where = array();

			if ($message)
				$where['MessageReference'] = $message->get('ID');

			$messagesObjs = Message::getList(array('where' => $where, 'order' => array('Date' => true)));

			$messages = array();
			foreach ($messagesObjs as $item) {
				$message = array();
				$message['obj'] = $item;
				$message['children'] = messagesNestFunction($item);
				$messages[$item->get('ID')] = $message;
			}
			return $messages;
		};
		return messagesNestFunction($message);
	}


	public static function lastConversation($message)
	{

		$messages = Message::getList(array(
			'where' => array(
				'(User = ' . $message->User->ID . ' AND UserTo = ' . $message->UserTo->ID . ')' .
					' OR ' .
					'(UserTo = ' . $message->User->ID . ' AND User = ' . $message->UserTo->ID . ')',
				'MessageReference IS NULL'

			),
			'order' => array('Date' => true), 'limit' => 50
		));

		if (!count($messages))
			return null;

		return $messages[count($messages) - 1]->get('ID');
	}

	public static function listesMessages()
	{

		$listesmessages = array();

		$auth = Session::getInstance()->getCurUser();
		$query = <<<END
		SELECT DISTINCT User,UserTo,Date FROM `com_messages` ORDER BY Date Desc LIMIT 50 
END;
		$exists_conversion = array();
		$result = \DB::reader($query);

		foreach ($result as $recorder) {
			if (in_array($auth->ID, array($recorder['UserTo'], $recorder['User']))) {
				if (!in_array($recorder['User'] . '_' . $recorder['UserTo'], $exists_conversion) && !in_array($recorder['UserTo'] . '_' . $recorder['User'], $exists_conversion)) {
					$exists_conversion[] = $recorder['User'] . '_' . $recorder['UserTo'];
					$exists_conversion[] = $recorder['UserTo'] . '_' . $recorder['User'];
					$messages = Message::getList(array(
						'where' => array(
							'(User = ' . $recorder['User'] . ' AND UserTo = ' . $recorder['UserTo'] . ')' .
								' OR ' .
								'(UserTo = ' .  $recorder['User'] . ' AND User = ' . $recorder['UserTo'] . ')'
						),
						'order' => array('Date' => true), 'limit' => 50
					));


					$listesmessages[] = $messages[count($messages) - 1];
				}
			}
		}
		return $listesmessages;
	}

	// Retourn les fichiers (liens absolus) du message en cours
	public function getFiles($api = false)
	{
		$fichiers = array();
		if (!$this->get('Fichiers'))
			return $fichiers;

		$files = explode(',', $this->get('Fichiers'));

		if ($api) {
			$filesApi = array();
			foreach ($files as $item) {
				$filesApi[] = array(
					'link' => \GoogleStorage::getUrl(\Config::get('path-uploads') . $item),
					'name' => $item
				);
			}

			return $filesApi;
		}

		foreach ($files as $item) {
			if (!$item)
				continue;
			$link = \GoogleStorage::getUrl(\Config::get('path-uploads') . $item);
			$fichiers[] = $link;
		}
		return $fichiers;
	}


	public static function getList($args = null, $query = null)
	{

		$user = \Session::getInstance()->getCurUser();
		if ($user && $user->get('Classes')) {
			$classes =  $user->get('Classes');
			$args['where'][] = "(Inscription IS NULL OR (`Inscription` IN (SELECT `ID` FROM `ins_inscriptions` WHERE `Classe` IN(" . $classes . "))))";
		}

		return parent::getList($args, $query);
	}

	
	public function getAudio(){
		if(!$this->Audio)
		return null;

		return \GoogleStorage::getUrl(\Config::get('path-uploads').$this->Audio);
	}

	
}
