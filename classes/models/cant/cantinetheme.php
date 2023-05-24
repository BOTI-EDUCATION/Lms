<?php

namespace Models\CANT;

class CantineTheme extends \Models\Model
{

	protected static $sqlQueries = array();

	protected static $table = 'cant_themes';
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
		'Presentation' => array(
			'type' => 'varchar',
		),
		'Menu' => array(
			'type' => 'text',
		),
		'Image' => array(
			'type' => 'varchar',
		),
		'Enabled' => array(
			'type' => 'text',
		),
		'Edit_history' => array(
			'type' => 'text',
		),
		'Date' => array(
			'type' => 'date',
		),
		'UserBy' => array(
			'fk' => 'User',
		)
	);


	public function getImages($absolute = false){
		if(!$this->get('Image'))
		return null;

		$images = [];

		foreach (json_decode($this->get('Image'),true) as $key => $image) {
			$images[] =\GoogleStorage::getUrl(\Config::get('path-images-cantine') .$image);
		}

		return  $images ;
	}

	public function getCountPartages(){
		$menus = \Models\CANT\CantineMenu::getCount(
			array(
				'where' => array(
					'CantineTheme' => $this->get('ID')
				)
			)
		);

		return $menus;
	}

	public function getMenu(){
		$daysOfWeek = [1=>'Lundi',2=>'Mardi',3=>'Mercredi',4=>'Jeudi',5=>'Vendredi',6=>'Samedi',7=>'Dimanche'];

		$menu = [];

		if($this->get('Menu'))
		foreach (json_decode($this->get('Menu'),true) as $day => $dayMenu) {
			$menu[$day] = [
				'day' => $daysOfWeek[$day],
				'menu' => $dayMenu
			];
		}

		return $menu;
	}


}
