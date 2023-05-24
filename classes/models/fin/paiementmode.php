<?php
namespace Models\FIN;
use \Models\Model;

class PaiementMode extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'paiementmodes';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Label' => array(
			'type' => 'varchar',
		),
		'Alias' => array(
			'type' => 'varchar',
		),
	);

	public static function getByAlias($alias) {
		$id = \DB::scallar('SELECT `ID` FROM '.static::wrapField(static::$table). ' WHERE `Alias`=?', array($alias));
		if (!$id)
			return null;
		return new self($id);
	}
}
