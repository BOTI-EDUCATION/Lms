<?php

namespace Models;

class Promotion extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'gen_promotions';
	protected static $pk = array(
		'ID' => array(
			'auto' => false,
		),
	);
	protected static $fields = array(
		'Annee' => array(
			'type' => 'int',
		),
		'Label' => array(
			'type' => 'varchar',
		),
		'Ordre' => array(
			'type' => 'int',
		),
		'Actuelle' => array(
			'type' => 'boolean',
		),
		'DateDebut' => array(
			'type' => 'date',
		),
		'DateFin' => array(
			'type' => 'date',
		),
		'InscriptionActive' => array(
			'type' => 'boolean',
		),
		'FncMonths' => array(
			'type' => 'varchar',
		),
		'RecuEncaissement' => array(
			'type' => 'int',
		),
	);



	public function months()
	{
		$months = array();
		$months_list = array(0 => 'Frais Annuels', 9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre', 1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril', 5 => 'Mai', 6 => 'Juin', 7 => 'Juillet');
		$zero_months_list  =  array_filter($months_list, fn ($key) => $key == 0 || in_array((int)$key, $this->getArray('FncMonths', true)), ARRAY_FILTER_USE_KEY);
		$zero_months_keys = array_keys($zero_months_list);
		$months['zero_list'] = $zero_months_list;
		$months['zero_keys'] = $zero_months_keys;

		unset($zero_months_list[0]);
		unset($zero_months_keys[0]);

		$months['list'] = $zero_months_list;
		$months['keys'] = $zero_months_keys;
		return (object)$months;
	}


	public function recuEncaissement($save = false)
	{

		$recu = $this->get('RecuEncaissement') ? $this->get('RecuEncaissement') : 1;

		if ($save)
			$this->set('RecuEncaissement', $recu + 1)->save();

		return str_pad($recu, 3, '0', STR_PAD_LEFT) . '_' . $this->get('Label');
	}

	public function getLastMonths()
	{
		$start_promot 	= date_create($this->get('DateDebut'));
		$today 			= date_create();
		$diff  			= date_diff($start_promot, $today);
		$months 		=  $diff->format('%y') * 12 + $diff->format('%m');

		return $months;
	}

	public static function promotion_actuelle()
	{

		$items = static::getList(array('where' => array(
			'Actuelle' => true
		), 'order' => array(
			'Annee' => false
		)));

		if ($items)
			return $items[0];

		return null;
	}
	public static function promotion_overte_pour_inscriptions()
	{

		$items = static::getList(array('where' => array(
			'InscriptionActive' => true
		), 'order' => array(
			'Annee' => false
		)));

		if ($items)
			return $items[0];

		return null;
	}

	public function getInscriptions()
	{
		$inscriptions = array();
		$items = Inscription::getList(array('where' => array('Promotion' => $this->get('ID'))));
		if (!$items)
			return null;
		foreach ($items as $item)
			$inscriptions[$item->get('ID')] = $item;
		return $inscriptions;
	}

	public static function exists($year)
	{
		$items = Promotion::getList(array('where' => array('Annee' => $year)));
		if (count($items))
			return $items[0];
		return null;
	}

	public static function actuelle()
	{
		$promotion = null;

		if (isset($_SESSION['promotion_actuelle'])) {
			// Turn notice into Exception
			set_error_handler(function ($errno, $errstr, $errfile, $errline, array $errcontext) {
				// error was suppressed with the @-operator
				if (0 === error_reporting()) {
					return false;
				}

				throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
			});
			try {
				$promotion = unserialize($_SESSION['promotion_actuelle']);
				if (gettype($promotion) != 'Models\Promotion')
					unset($_SESSION['promotion_actuelle']);
			} catch (\ErrorException $e) {
				unset($_SESSION['promotion_actuelle']);
			}
			// restore default error handler
			restore_error_handler();
		}

		if (!isset($_SESSION['promotion_actuelle'])) {
			$promotion = static::promotion_actuelle();
			$_SESSION['promotion_actuelle'] = serialize($promotion);
		}

		return $promotion;
	}


	/* ALTER TABLE `gen_promotions` ADD `RecuEncaissement` INT NULL AFTER `DateFin`; */


	public static function unsetActuelle()
	{
		if (isset($_SESSION['promotion_actuelle']))
			unset($_SESSION['promotion_actuelle']);
	}

	public function nextPromo()
	{

		return self::where(array('Annee' => $this->Annee + 1))->first();
	}
	public function lastPromo()
	{
		return self::where(array('Annee' => $this->Annee - 1))->first();
	}
}
