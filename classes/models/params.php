<?php

namespace Models;



class Params extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'params';

	protected static $pk = array(

		'ID' => array(

			'auto' => true,

		),

	);

	protected static $fields = array(

		'Type' => array(

			'type' => 'varchar',

		),

		'Alias' => array(

			'type' => 'varchar',

		),
		'TabParams' => array(
			'fk' => 'TabParams',
		),

		'Label' => array(

			'type' => 'varchar',

		),

		'Value' => array(

			'type' => 'text',

		),
		'Description' => array(

			'type' => 'text',

		),
		'EditHistory' => array(
			'type' => 'text',
		),

	);


	public function beforeSave()
	{
		$history = $this->getArray('EditHistory') ?: array();
		$history[] = array(
			'user' => \Session::getInstance()->getCurUser()->ID,
			'action' => $this->saved ? 'update' : 'add',
			'date' => date('Y-m-d H:i:s'),
		);
		$this->setJson('EditHistory', $history);
	}


	public static function getByAlias($alias)
	{
		$id = \DB::scallar('SELECT `ID` FROM ' . static::wrapField(static::$table) . ' WHERE `Alias`=?', array($alias));
		if (!$id)
			return null;
		return new self($id);
	}

	public static function getValue($alias)
	{
		$item = self::getByAlias($alias);
		if ($item)
			return json_decode($item->get('Value'), true);

		return null;
	}

	public static function isAllowed($alias)
	{
		$item = self::getByAlias($alias);
		if ($item)
			return json_decode($item->get('Value'), true);

		return null;
	}
	public function value()
	{
		return json_decode($this->get('Value'), true);
	}
}
