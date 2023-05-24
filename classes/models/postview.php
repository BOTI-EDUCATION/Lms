<?php

namespace Models;

class PostView extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'com_postviews';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'User' => array(
			'fk' => 'User',
		),
		'Post' => array(
			'fk' => 'Post',
		),
		'Date' => array(
			'type' => 'datetime',
		),
		'IP' => array(
			'type' => 'varchar',
		),
		'Source' => array(
			'type' => 'varchar',
		),
		'Country' => array(
			'type' => 'varchar',
		),
		'City' => array(
			'type' => 'varchar',
		),
	);

	public static function addEntry($user, $post)
	{
		if (!$user)
			return NULL;
		$log = null;
		$logs = PostView::getList(array('where' => array('User' => $user->ID, 'Post' => $post->ID)));
		if (count($logs)) {
			$log = $logs[0];
		} else {
			$post->set('Views', $post->get('Views') + 1)->save();
			$log = new PostView();
			$log->set('User', $user)
				->set('Post', $post)
				->set('IP', $_SERVER['REMOTE_ADDR'])
				->set('Date',  date('Y-m-d H:i:s'))
				->save();
		}

		return $log;
	}
}
