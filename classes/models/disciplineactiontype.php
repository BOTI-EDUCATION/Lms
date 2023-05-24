<?php
namespace Models;

class DisciplineActionType extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'disciplineactiontypes';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Nature' => array(
			'fk' => 'DisciplineNature',
		),
		'Label' => array(
			'type' => 'varchar',
		),
		'Icon' => array(
			'type' => 'varchar',
		),
		'Flag' => array(
			'type' => 'boolean',
		),
		'Valeur' => array(
			'type' => 'int',
		),
	);
	

	protected static $list = null;
	public static function getListCached() {
		if (static::$list === null) {
			static::$list = static::getList();
		}
		return static::$list;
	}
	
	public function getClass() {
		return ($this->get('Flag')?'smile-o good':'frown-o bad');
	}
}
