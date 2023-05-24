<?php

namespace Models;

class ModelManger
{
	protected $query_where = array();
	protected $query_order = array();
	protected $query_groupBy = array();
	protected $limit =  null;
	protected $start =  null;
	protected $model =  null;
	protected $join_i =  1;
	protected $sql_query =  null;

	function  __construct($model)
	{
		$this->model = $model;
		$this->sql_query = $this->model::sqlQuery(true);
	}

	function  where($where = null)
	{
		if (!is_null($where)) {
			if (is_array($where)) {
				foreach ($where as $key => $value) {
					$this->query_where[$key] = is_object($value) ? $value->getPk(true) : $value;
				}
			} else {
				$this->query_where[] = $where;
			}
		}

		return $this;
	}


	function  whereInJson($field, $values)
	{
		$queries = [];

		if ($values) {
			foreach ($values as  $item) {
				$queries[] = ' (' . $field . '  LIKE \'%"' . $item . '"%\') ';
			}
			$this->query_where[] = "(" . implode(' OR ', $queries) . ")";
		}

		return $this;
	}


	function  whereNotInJson($field, $values)
	{
		$queries = [];

		if ($values) {
			foreach ($values as  $item) {
				$queries[] = ' (' . $field . ' NOT LIKE \'%"' . $item . '"%\') ';
			}
			$this->query_where[] = "(" . implode(' AND ', $queries) . ")";
		}

		return $this;
	}




	function  whereIn($field, $values)
	{

		if (!empty($values)) {
			$this->query_where[] = "(" .  $field . " IN(" . (is_array($values) ? implode(',', $values) : $values) . ")" . ")";;
		} else {
			$this->query_where[] = "(" . $field . " IN(0)" . ")";
		}


		return $this;
	}



	function  between($field, $v1, $v2)
	{
		$this->query_where[] =  '(`' . $field . '` BETWEEN \'' . $v1 . '\' AND \'' . $v2 . '\')';
		return $this;
	}


	function whereNull($field)
	{
		$this->query_where[] =  '(`' . $field . '` IS NULL )';
		return $this;
	}


	function whereNotNull($field)
	{
		$this->query_where[] =  '(`' . $field . '` IS NOT NULL )';
		return $this;
	}

	function  whereNotIn($field, $values)
	{

		if (!empty($values)) {
			$this->query_where[] = $field . " NOT IN(" . (is_array($values) ? implode(',', $values) : $values) . ")";
		} else {
			$this->query_where[] = $field . " NOT IN(0)";
		}
		return $this;
	}



	function  order($order = array())
	{
		foreach ($order as $key => $value) {
			$this->query_order[$key] = $value;
		}
		return $this;
	}

	function join($table, $model_j_pkey, $table_fkey, $fields = array(), $jtable = null)
	{
		$join_i = $this->join_i++;
		$model_table =  $jtable ?: $this->model::getTable();
		$fields_query = " $table_fkey AS j$join_i$table_fkey,";
		foreach ($fields as $key => $v) {
			$fields_query .= " $v AS j$join_i$v ,";
		}

		$fields_query = substr($fields_query, 0, -1);
		$this->sql_query .= " JOIN (SELECT $fields_query FROM `" . $table . "`) AS `j$join_i` ON `$model_table`.`$model_j_pkey` = `j$join_i`.`j$join_i$table_fkey`";

		return $this;
	}


	function _join($table_key, $model_j_pkey, $fields = array())
	{

		$table = strpos($table_key, '.') ? explode('.', $table_key)[0] : $table_key;
		$table_fkey = '';
		$model_j_pkey = strpos($table_key, '.') ? explode('.', $table_key)[1] : $table_key;;
		$jtable  = '';

		$join_i = $this->join_i++;
		$model_table =  $jtable ?: $this->model::getTable();
		$fields_query = " $table_fkey AS j$join_i$table_fkey,";
		foreach ($fields as $key => $v) {
			$fields_query .= " $v AS j$join_i$v ,";
		}

		$fields_query = substr($fields_query, 0, -1);
		$this->sql_query .= " JOIN (SELECT $fields_query FROM `" . $table . "`) AS `j$join_i` ON `$model_table`.`$model_j_pkey` = `j$join_i`.`j$join_i$table_fkey`";

		return $this;
	}



	function jJoin($JIDtable, $table, $JIDtableKey, $tableKey,  $fields = array())
	{
		$join_i = $this->join_i++;
		$fields_query = " `$tableKey` AS `j$join_i$tableKey`,";

		foreach ($fields as $key => $v) {
			$fields_query .= " `$v` AS `j$join_i$v` ,";
		}

		$fields_query = substr($fields_query, 0, -1);

		$this->sql_query .= " JOIN (SELECT $fields_query FROM `" . $table . "`) AS `j$join_i` ON `$JIDtable`.`$JIDtableKey` = `j$join_i`.`j$join_i$tableKey`";
		return $this;
	}



	function limit($limit)
	{
		$this->limit = $limit;
		return $this;
	}

	function start($start = 0)
	{
		$this->start = $start;
		return $this;
	}

	function  get()
	{
		return $this->model::getList(array('where' => $this->query_where, 'order' => $this->query_order, 'limit' => $this->limit, 'groupBy' => $this->query_groupBy, 'start' => $this->start), $this->sql_query);
	}


	function  all()
	{
		return $this->model::all(array('where' => $this->query_where, 'order' => $this->query_order, 'limit' => $this->limit, 'groupBy' => $this->query_groupBy, 'start' => $this->start), $this->sql_query);
	}

	function first()
	{
		$objs = $this->limit(1)->get();
		return isset($objs[0]) ? $objs[0] : null;
	}

	function  count()
	{
		return $this->model::getCount(array('where' => $this->query_where));
	}


	function  getQuery()
	{
		return $this->query_where;
	}

	function  groupBy($groupBy)
	{
		foreach ($groupBy as $value) {
			$this->query_groupBy[] = $value;
		}
		return $this;
	}

	function paginate($page = 1, $per_page = 10)
	{
		return $this->start($per_page * ($page - 1))->limit($per_page)->get();
	}
}
