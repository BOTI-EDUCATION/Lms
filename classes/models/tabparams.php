<?php

namespace Models;

use phpDocumentor\Reflection\DocBlock\Tags\Param;

class TabParams extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'tab_params';

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
	);


	public function listParams()
	{
		return Params::getList(array('where' => array('TabParams' => $this->get('ID'))));
	}
}
