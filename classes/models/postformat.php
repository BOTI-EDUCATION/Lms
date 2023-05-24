<?php
namespace Models;

class PostFormat extends Model {

	protected static $sqlQueries = array();

	protected static $table = 'com_postformats';
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
		'Icon' => array(
			'type' => 'varchar',
		),
		'Ordre' => array(
			'type' => 'int',
		),
		'Visible' => array(
			'type' => 'boolean',
		),
		'Categories' => array(
			'type' => 'varchar',
		),
	);
	public static function getByAlias($alias) {		$idPost = \DB::scallar('SELECT `ID` FROM '.static::wrapField(static::$table). ' WHERE `Alias`=?', array($alias));		if (!$idPost)			return null;
		return new self($idPost);	}
	
	public function getCategories() {
		$categories = array();
		$cats = explode(',',$this->get('Categories'));
		foreach($cats as $item) {
			if(!$item)
				continue;
			
			$category = new PostCategorie($item);
			if ($category)
				$categories[] = $category; 
		}
		return $categories;
	}
}
