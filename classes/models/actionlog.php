<?php

namespace Models;

class ActionLog extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'actionlogs';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'User' => array(
			'fk' => 'User',
			'type' => 'int',
		),
		'Detail' => array(
			'type' => 'text',
		),
		'Object' => array(
			'type' => 'text',
		),
		'Date' => array(
			'type' => 'datetime',
		),
	);

	public static function catchLog($details, $object = null, $is_model_object = true)
	{
		$action = new ActionLog();
		$action->set('User', \Session::getInstance()->getCurUser());
		$action->set('Detail', $details);
		$action->set('Date', date('Y-m-d H:i:s'));

		if ($object && $is_model_object) {
			$action->set('Object', json_encode($object->asArray()));
		} else {
			$action->set('Object', json_encode($object));
		}
		return $action->save();
	}
}
