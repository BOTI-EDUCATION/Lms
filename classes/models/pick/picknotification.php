<?php

namespace Models\Pick;

use \Models\Model;

class PickNotification extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'pick_notifications';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);

	protected static $fields = array(

		'Type' => array(
			'type' => 'varchar',
		),
		'Infos' => array(
			'type' => 'text',
		),
		'Message' => array(
			'type' => 'text',
		),
		'DateTime' => array(
			'type' => 'datetime',
		),
		'Views' => array(
			'type' => 'text',
		),
		'Managed' => array(
			'type' => 'text',
		),
		'Eleves' => array(
			'type' => 'text',
		),
		'Parents' => array(
			'type' => 'text',
		),
	);



	public static function getTableHeadHtml()
	{


		return getView('picks/noti_head', array(), 'null_layout');
	}


	public function getTableTrHtml()
	{

		return  getView('picks/noti_item', array('noti' => $this), 'null_layout');
	}
}
