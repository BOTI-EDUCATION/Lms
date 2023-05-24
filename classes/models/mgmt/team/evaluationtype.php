<?php

namespace Models\MGMT\Team;

use Models\Model as Model;

class EvaluationType extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'mgmt_types_evaluations';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Alias' => array(
			'type' => 'varchar',
		),
		'Label' => array(
			'type' => 'varchar',
		),
		'Icon' => array(
			'type' => 'varchar',
		),
		'Enabled' => array(
			'type' => 'tinyint',
		),
		'EditHistory' => array(
			'type' => 'text',
		),
	);

	public function getIcon($full_link = false)
	{
		$icon = $this->get('Icon');
		if (!$icon || !file_exists(_basepath . \Config::get('path-mgmt-evaluation-type') . $this->get('Icon'))) {
			$icon = 'default1.png';
		}

		return ($full_link ? \URL::AbsoluteLink() : \URL::base('')) . \Config::get('path-mgmt-evaluation-type') . $icon;
	}
}
