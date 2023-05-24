<?php
namespace Models;

class Role extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'roles';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Parent' => array(
			'fk' => 'Role',
		),
		'Label' => array(
			'type' => 'varchar',
		),
		'Alias' => array(
			'type' => 'varchar',
		),
	);

	//check this function at any action or view loading
	Public function getPermissions()
	{
		$permissions = array('Absences_View_Listing', 'Absences_View_Item', 'Absences_Action_Edit', 'Users_View_Item');
		//to replace with $this->getPermissions()
		return $permissions;
	}

	public static function getByAlias($alias) {
		$id = \DB::scallar('SELECT `ID` FROM '.static::wrapField(static::$table). ' WHERE `Alias`=?', array($alias));
		if (!$id)
			return null;
		return new self($id);
	}
}
