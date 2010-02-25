<?php
//CoreDB - MySQLi settings
// Jud Stephenson - 2009


class CoreDB extends mysqli
{

	private $host;
	private $user;
	private $password;
	private $dbase;
	
	// construct the DB Connection
	//--------------------------------------------------
	public function __construct()
	{
		$this->host		= 'localhost';
		$this->user		= 'user';
		$this->password	= 'pass';
		$this->dbase	= 'db';
		
		// Connect using the mysqli __construct
		// should be used like $CoreDB = new CoreDB();
		// now CoreDB has all the mysqli stuff;
		return parent::__construct($this->host, $this->user, $this->password, $this->dbase);
	}
	
	// escape_string alias
	//--------------------------------------------------
	public function clean($value)
	{
		return parent::escape_string($value);
	}
	
	// mysql_query alias
	//--------------------------------------------------
	public function query($fields, $table, $where, $debug=false)
	{
		// start sql
		$sql = 'select ';
		
		// add the fields to the sql
		if(is_array($fields))
		{
			//loop through fields if its an array
			for($i=0; $i<count($fields); $i++)
			{
				$sql .= ((count($fields) - 1) != $i) ? $fields[$i] . ', ' : $fields[$i] . ' ';
			}
		}else{
			// else just add it to the sql
			$sql .= $fields . ' ';
		}
		
		// add 'from'
		$sql .= 'from ';
		
		// do the same for tables
		if(is_array($table))
		{
			//loop through tables if its an array
			for($i=0; $i<count($table); $i++)
			{
				$sql .= ((count($table) - 1) != $i) ? $table[$i] . ', ' : $table[$i] . ' ';
			}
		}else{
			// else just add it to the sql
			$sql .= $table . ' ';
		}
		
		// now add the where clause
		$sql .= 'where ' . $where;
		
		echo ($debug) ? $sql : '';
		return parent::query($sql);
	}
	
	
	// help with insert sql
	//--------------------------------------------------
	public function insert($rows, $values, $table, $debug=false)
	{
		$sql_rows   = '';
		$sql_values = '';
		
		// build the sql string
		$sql = 'insert into ' . $table . ' (';
		
		// now attach the rows to the string
		for($i=0; $i<count($rows); $i++)
		{
			$sql_rows .= ((count($rows) - 1) != $i) ? $rows[$i] . ', ' : $rows[$i] . ') values (';
			
			if(strtolower($values[$i]) == 'null')
			{
				$values[$i] = 'NULL';
				$sql_values .= ((count($values) - 1) != $i) ? $this->clean($values[$i]) . ", " : $this->clean($values[$i]) . ")";
			}else{
				$sql_values .= ((count($values) - 1) != $i) ? "'" . $this->clean($values[$i]) . "', " : "'" . $this->clean($values[$i]) . "')";
			}
		}
		// finish sql build
		$sql .= $sql_rows . $sql_values;
		
		echo ($debug) ? $sql : '';
		parent::query($sql);
		
		return $this->insert_id;
	}
	
	// help with update sql
	//--------------------------------------------------
	public function update ($field, $value, $table, $where)
	{
		// build the sql string
		if(!is_array($field) && !is_array($value))
		{
			$sql = 'update ' . $table . ' set ' . $field . ' = \'' . $this->clean($value) . '\' where ' . $where;
		} else
		{
			$sql = 'update ' . $table . ' set ';
			for($i=0;$i<count($field); $i++)
			{
				$sql .= $field[$i];
				$sql .= (substr($this->clean($value[$i]), 0, 2) == '+=') ? ' +=' : ' =';
				$sql .= ' \'' . $this->clean($value[$i]) . '\', ';
			}
			$sql = rtrim($sql, ', ');
			$sql .= ' where ' . $where;
			
		}
		
		// exec query
		return parent::query($sql);
	}
	
	// fetch_assoc alias
	//--------------------------------------------------
	public function fetch($query)
	{
		// fetch assoc array
		return $query->fetch_assoc();
	
	}
	
	// pass a raw query to class
	//--------------------------------------------------
	public function rawQuery($sql)
	{
		return parent::query($sql);
	}

}