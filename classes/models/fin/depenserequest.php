<?php

namespace Models\FIN;

use \Models\Model;

class DepenseRequest extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'fnc_depense_requests';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Rubrique' => array(
			'fk' => 'FIN\\DepenseRubrique',
		),
		'Depense' => array(
			'fk' => 'FIN\\Depense',
		),
		'RubriqueAlias' => array(
			'type' => 'varchar',
		),
		'RubriqueLabel' => array(
			'type' => 'varchar',
		),
		'Label' => array(
			'type' => 'varchar',
		),
		'Details' => array(
			'type' => 'text',
		),
		'Files' => array(
			'type' => 'text',
		),
		'Date' => array(
			'type' => 'date',
		),
		'DateSaisie' => array(
			'type' => 'date',
		),
		'UserBy' => array(
			'fk' => 'User',
		),
		'Validated' => array(
			'type' => 'text',
		),
		'Rejected' => array(
			'type' => 'text',
		),
	);
}
