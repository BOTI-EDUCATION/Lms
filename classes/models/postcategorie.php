<?php
namespace Models;

class PostCategorie extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'com_postcategories';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Label' => array(
			'type' => 'varchar',
			'required' => true,
		),
		'Alias' => array(
			'type' => 'varchar',
			'required' => true,
		),
		'Intro' => array(
			'type' => 'varchar',
		),
		'Description' => array(
			'type' => 'text',
		),
		'Image' => array(
			'type' => 'varchar',
		),
		'Icon' => array(
			'type' => 'varchar',
		),
		'Ordre' => array(
			'type' => 'int',
		),
		'Visible' => array(
			'type' => 'boolean',
		),
	);


	public function getLink() {		return \URL::link('blog/').\Tools::getAlias($this->get('Alias')).'/';	}

	public function getImage() {
		if (!$this->get('Image'))
			return null;
		return \Request::getBase() . \Config::get('path-images-postcategories') . $this->get('Image');
	}
	public static function getByAlias($alias) {		$idPost = \DB::scallar('SELECT `ID` FROM '.static::wrapField(static::$table). ' WHERE `Alias`=?', array($alias));		if (!$idPost)			return null;
		return new self($idPost);	}
}
