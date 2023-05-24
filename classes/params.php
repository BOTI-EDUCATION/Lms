<?php
class Params
{

	private $tabParams = null;
	private $params = null;
	private $listValues = null;
	static  $instance = null;
	// Privates
	private function __construct()
	{


		$paramsFilePath = realpath(dirname(__FILE__) . '/..') . DIRECTORY_SEPARATOR . 'config/params.php';
		if (!is_readable($paramsFilePath))
			throw new Exception('Could not load Params file');
		$this->tabParams = require $paramsFilePath;
		$this->params = array_reduce($this->tabParams, function ($carry, $tab) {
			return  array_merge($tab['params'], $carry);
		}, []);

		foreach (Models\Params::getList() as $key => $item) {
			$this->listValues[$item->Alias] = $item;
		}
	}

	// Singletone Implementation
	public static function getInstance()
	{
		if (!static::$instance) {
			static::$instance  = new self();
		}
		return  static::$instance;
	}


	public static function getDetail($param_alias)
	{
		$instance = self::getInstance();
		return $instance->params[$param_alias] ?? null;
	}

	public static function get($param_alias)
	{
		$instance = self::getInstance();
		return $instance->listValues[$param_alias] ?? null;
	}

	public static function getValue($param_alias, $json = true)
	{
		return self::has($param_alias) ? self::get($param_alias)->value($json) : null;
	}

	public static function set($param_alias, $value)
	{
		$instance = self::getInstance();

		return $instance->listValues[$param_alias] ? $instance->listValues[$param_alias]->set('Value', $value) : null;
	}

	public static function has($param_alias)
	{
		$instance = self::getInstance();
		return  isset($instance->listValues[$param_alias]);
	}

	public static function tabList()
	{
		$instance = self::getInstance();
		return $instance->tabParams;
	}

	public static function list()
	{
		$instance = self::getInstance();
		return $instance->params;
	}

	public static function listValues()
	{
		$instance = self::getInstance();
		return $instance->listValues;
	}
}
