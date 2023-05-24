<?php

namespace Models;

class PostSms extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'post_sms';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'ToText' => array(
			'type' => 'text',
		),
		'Numbers' => array(
			'type' => 'text',
		),
		'SmsSent' => array(
			'type' => 'text',
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
