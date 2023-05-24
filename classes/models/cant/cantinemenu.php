<?php

namespace Models\CANT;

class CantineMenu extends \Models\Model
{

	protected static $sqlQueries = array();

	protected static $table = 'cant_menu';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'CantineTheme' => array(
			'fk' => 'CANT\\CantineTheme',
		),
		'Week' => array(
			'type' => 'varchar',
		),
		'From' => array(
			'type' => 'date',
		),
		'To' => array(
			'type' => 'date',
		),
		'Menu' => array(
			'type' => 'text',
		),
		'Edit_history' => array(
			'type' => 'text',
		),
		'Views' => array(
			'type' => 'int',
		),
	);

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
