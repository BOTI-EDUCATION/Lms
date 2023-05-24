<?php

namespace Models;

class NotificationSms extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'notifications_sms';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'UserTo' => array(
			'fk' => 'User',
			'type' => 'int',
		),
		'UserToName' => array(
			'type' => 'varchar',
		),
		'GSM' => array(
			'type' => 'varchar',
		),
		'Inscription' => array(
			'fk' => 'Inscription',
			'type' => 'int',
		),

		'Action' => array(
			'type' => 'varchar',
		),
		'Message' => array(
			'type' => 'varchar',
		),
		'Date' => array(
			'type' => 'datetime',
		),
		'User' => array(
			'fk' => 'User',
			'type' => 'int',
		),
	);
}
