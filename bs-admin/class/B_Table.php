<?php
/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	// -------------------------------------------------------------------------
	// class B_Table
	// 
	// -------------------------------------------------------------------------
	class B_Table {
		function __construct(&$db, $table_name, $prefix='') {
			global $g_data_set, ${$g_data_set};

			$this->db = $db;
			if($prefix) {
				$this->prefix = $prefix;
			}
			else {
				$this->prefix = B_DB_PREFIX;
			}

			$this->table = $table_name;
			$this->config = ${$g_data_set}['table'][$this->table];
		}

		function select() {
			$sql = $this->getSelectSql();
			$rs = $this->db->query($sql);
			return $this->db->fetch_assoc($rs);
		}

		function selectCount($where_sql) {
			$sql = "select count(*) cnt from $this->prefix$this->table $where_sql";
			$rs = $this->db->query($sql);
			$row = $this->db->fetch_assoc($rs);
			return $row['cnt'];
		}

		function selectAll($offset, $limit) {
			$sql = $this->getSelectSql();
			$sql.= ' limit ' . $offset .',' . $limit;
			return $this->db->query($sql);
		}

		function selectByPk($param) {
			$sql = $this->getSelectSql();
			$ret = $this->getWhereSqlByPk($param, $where_sql);
			$sql.= $where_sql;
			$rs = $this->db->query($sql);
			return $this->db->fetch_assoc($rs);
		}

		function isColumnExist($column) {
			return array_key_exists($column, $this->config);
		}

		function getSelectSql() {
			return "select * from $this->prefix$this->table";
		}

		function getWhereSqlByPk($param, &$sql_where) {
			$status = false;

			foreach($this->config as $key => $value) {
				if($value[2] && isset($param[$key]) && $param[$key] != '') { // primary key
					if(!isset($param[$key]) || $param[$key] == '') {
						return false;
					}
					switch($value[0]) {	// type
					case 'char':
					case 'text':
						if(isset($sql)) {
							$sql.= " and";
						}
						else {
							$sql = " where";
						}
						$sql.= " $key='" . $this->db->real_escape_string($param[$key]) . "'";
						break;

					case 'int':
					case 'decimal':
						if(isset($sql)) {
							$sql.= " and";
						}
						else {
							$sql = " where";
						}
						$sql.= " $key=" . $this->db->real_escape_string($param[$key]);
						break;
					}
					$status = true;
				}
			}
			$sql_where = $sql;
			return $status;
		}

		function update($param) {
			try {
				if(!is_array($param)) return false;
				foreach($param as $key => $value) {
					if(!isset($this->config[$key])) continue;
					if($this->config[$key][2]) continue; // pk

					if(isset($v)) $v.= ',';
					$v.= $this->setUpdateVlues($key, $value);
				}

				$ret = $this->getWhereSqlByPk($param, $sql_where);
				if($ret) {
					$sql = "update $this->prefix$this->table set $v $sql_where";
					$ret = $this->db->query($sql);
				}
			}
			catch (Exception $e) {
				return false;
			}
			return $ret;
		}

		function setUpdateVlues($key, $value) {
			$config = $this->config[$key];

			switch($config[0]) {
				// data type
			case 'char':
			case 'text':
			case 'mediumtext':
				if(is_array($value)) {
					foreach($value as $val) {
						if(isset($v)) {
							$v.= '/' . $val;
						}
						else {
							$v = $val;
						}
					}
					$sql = "$key ='$v'";
				}
				else {
					$sql = $key . "='" . $this->db->real_escape_string($value) . "'";
				}
				break;

			case 'int':
			case 'decimal':
				if($value) {
					$sql = $key . "=" . $this->db->real_escape_string($value);
				}
				else {
					$sql = $key . "=0";
				}
				break;

			default:
				break;
			}
			return $sql;
		}

		function insert($param) {
			try {
				foreach($this->config as $key => $config) {
					if(isset($v)) $v.= ",";
					$v.= $this->setInsertValues($key, $config, $param);
				}
				$sql = "insert into $this->prefix$this->table values($v)";
				$ret = $this->db->query($sql);
			}
			catch (Exception $e) {
				return false;
			}
			return $ret;
		}

		function selectInsert($param, $sql_where='') {
			try {
				foreach($this->config as $key => $config) {
					if(isset($v)) $v.= ',';
					$v.= $this->setInsertValues($key, $config, $param);
				}
				$sql = "insert into $this->prefix$this->table select $v from $this->prefix$this->table $sql_where";
				$ret = $this->db->query($sql);
			}
			catch (Exception $e) {
				return false;
			}
			return $ret;
		}

		function selectInsertFromDifferntTable($param, $from, $sql_where='') {
			try {
				foreach($this->config as $key => $config) {
					if(isset($v)) $v.= ',';
					$v.= $this->setInsertValues($key, $config, $param);
				}
				$sql = "insert into $this->prefix$this->table select $v from $this->prefix$from $sql_where";
				$ret = $this->db->query($sql);
			}
			catch (Exception $e) {
				return false;
			}
			return $ret;
		}

		function upsert($param) {
			$row = $this->selectByPk($param);
			if($row) {
				return $this->update($param);
			}
			else {
				return $this->insert($param);
			}
		}

		function deleteInsert($param) {
			try {
				// delete
				$ret = $this->deleteByPk($param);

				if($ret) {
					// insert
					foreach($this->config as $key => $config) {
						if(isset($v)) $v.= ',';
						$v.= $this->setInsertValues($key, $config, $param);
					}
					$sql = "insert into $this->prefix$this->table values($v)";
					$ret = $this->db->query($sql);
				}
			}
			catch (Exception $e) {
				return false;
			}
			return $ret;
		}

		function setInsertValues($key, $config, $param) {
			if($config[3] && (!isset($param[$key]) || $param[$key] == '')) { // auto increment
				$start_value = ($config[4]) ? $config[4] : 0;
				switch($config[0]) { // data type
				case 'char':
				case 'text':
				case 'mediumtext':
					$sql = "lpad(cast((ifnull(max($key), $start_value) +1) as  char), $config[1], '0')";
					break;

				case 'int':
				case 'decimal':
					$sql = "ifnull(max($key), $start_value)+1";
					break;
				}
				return $sql;
			}
			switch($config[0]) { // data type
			case 'char':
			case 'text':
			case 'mediumtext':
				if(isset($param[$key])) {
					if(is_array($param[$key])) {
						foreach($param[$key] as $value) {
							if(isset($v)) {
								$v.= '/' . $value;
							}
							else {
								$v = $value;
							}
						}
						$sql = "'" . $v . "'";
					}
					// single
					else {
						$sql = "'" . $this->db->real_escape_string($param[$key]) . "'";
					}
				}
				else {
					$sql = "''";
				}
				break;

			case 'int':
			case 'decimal':
				if(isset($param[$key]) && $param[$key] != '') {
					if(is_array($param[$key])) {
						foreach($param[$key] as $value) {
							if(isset($v)) {
								$sql.= '/' . $value;
							}
							else {
								$sql = $value;
							}
						}
					}
					else {
						$sql = $this->db->real_escape_string($param[$key]);
					}
				}
				else {
					$sql = 0;
				}
				break;

			default:
				break;
			}

			return $sql;
		}

		function deleteByPk($param) {
			try {
				if(!isset($this->config)) return;

				$ret = $this->getWhereSqlByPk($param, $sql_where);
				if(!$ret) return false;

				$sql = "delete from $this->prefix$this->table $sql_where";
				$ret = $this->db->query($sql);
			}
			catch (Exception $e) {
				return false;
			}
			return $ret;
		}

		function selectMaxValue($field,  $sql_where='') {
			$sql = "select max($field) $field from $this->prefix$this->table $sql_where";

			$rs = $this->db->query($sql);
			$row = $this->db->fetch_assoc($rs);

			return $row[$field];
		}

		function selectMaxValuePlusOne($field) {
			$config = $this->config[$field];
			if($config[3] && (!isset($param[$key]) || $param[$key] == '')) { // auto increment
				switch($config[0]) { // data type
				case 'char':
				case 'text':
				case 'mediumtext':
					$field_sql = "lpad(cast((ifnull(max($field), 0) +1) as  char), $config[1], '0')";
					break;

				case 'int':
				case 'decimal':
					$field_sql = "ifnull(max($key), 0)+1";
					break;
				}
			}
			$sql = "select $field_sql $field from $this->prefix$this->table";
			$rs = $this->db->query($sql);
			$row = $this->db->fetch_assoc($rs);

			return $row[$field];
		}

		function copy($param, $from, $sql_where='') {
			try {
				foreach($this->config as $key => $config) {
					if(isset($v)) $v.= ',';
					$v.= $this->setCopyValues($key, $config, $param);
				}
				$sql = "insert into $this->prefix$this->table select $v from $this->prefix$from $sql_where";
				$ret = $this->db->query($sql);
			}
			catch (Exception $e) {
				return false;
			}
			return $ret;
		}

		function setCopyValues($key, $config, $param) {
			switch($config[0]) { // data type
			case 'char':
			case 'text':
			case 'mediumtext':
				if(isset($param[$key])) {
					if(is_array($param[$key])) {
						foreach($param[$key] as $value) {
							if(isset($v)) {
								$v.= '/' . $value;
							}
							else {
								$v = $value;
							}
						}
						$sql = "'" . $v . "'";
					}
					// single
					else {
						$sql = "'" . $this->db->real_escape_string($param[$key]) . "'";
					}
				}
				else {
					$sql = $key;
				}
				break;

			case 'int':
			case 'decimal':
				if(isset($param[$key]) && $param[$key] != '') {
					if(is_array($param[$key])) {
						foreach($param[$key] as $value) {
							if(isset($v)) {
								$sql.= '/' . $value;
							}
							else {
								$sql = $value;
							}
						}
					}
					else {
						$sql = $this->db->real_escape_string($param[$key]);
					}
				}
				else {
					$sql = $key;
				}
				break;

			default:
				break;
			}

			return $sql;
		}

		function load($file_name, $delimiter, $title, $convert_kana, $log) {
			$fp = fopen($file_name, 'r');
			if(!$fp) return;

			for($i=0; !feof($fp); $i++) {
				$buf = B_Util::fgetcsv($fp, 10000, $delimiter, '"');

				// delete CRLF from end of record
				$buf[count($buf)-1] = trim($buf[count($buf)-1]);

				if(($i == 0 && $title) || (count($buf) == 1 && $buf[0] == '')) {
					continue;
				}

				for($j=0; $j<count($buf); $j++) {
					$buf[$j] = mb_convert_encoding($buf[$j], B_CHARSET, 'sjis-win');
					if($convert_kana) {
						$buf[$j] = mb_convert_kana($buf[$j], 'KV', B_CHARSET);
					}
				}
				if(!$this->selectInsertForLoad($buf)) {
					return false;
				}
			}
			return true;
		}

		function selectInsertForLoad($param, $sql_where='') {
			try {
				$i=0;
				foreach($this->config as $key => $config) {
					if(isset($v)) $v.= ',';
					$v.= $this->setInsertValuesForLoad($key, $config, $param, $i);
				}
				$sql = "insert into $this->prefix$this->table select $v from $this->prefix$this->table $sql_where";
				$ret = $this->db->query($sql);
			}
			catch (Exception $e) {
				return false;
			}
			return $ret;
		}

		function setInsertValuesForLoad($key, $config, $param, &$index) {
			$value = $param[$index];
			if($config[3]) { // auto increment
				$start_value = ($config[4]) ? $config[4] : 0;
				switch($config[0]) { // data type
				case 'char':
				case 'text':
				case 'mediumtext':
					$sql = "lpad(cast((ifnull(max($key), $start_value) +1) as  char), $config[1], '0')";
					break;

				case 'int':
				case 'decimal':
					$sql = "ifnull(max($key), $start_value)+1";
					break;
				}
			}
			else {
				switch($config[0]) { // data type
				case 'char':
				case 'text':
				case 'mediumtext':
					$sql = "'" . $this->db->real_escape_string($value) . "'";
					break;

				case 'int':
				case 'decimal':
					$sql = $this->db->real_escape_string($value);
					break;

				default:
					break;
				}
				$index++;
			}
			return $sql;
		}

		function create() {
			try {
				foreach($this->config as $key => $config) {
					// column
					if(isset($v)) $v.= ',';
					$v.= $this->setCreateColumn($key, $config);

					// primary key
					if($config[2]) {
						$primary_array[$config[2]] = $key;
					}
				}
				if(is_array($primary_array)) {
					ksort($primary_array);
					foreach($primary_array as $value) {
						if($primary) $primary.=',';
						$primary.= $value;
					}
				}

				if($primary) $primary = "primary key($primary) ";

				$sql = "create table $this->prefix$this->table ($v, $primary) default charset=" . B_DB_CHARSET . " engine=" . B_DB_ENGINE;
				$ret = $this->db->query($sql);
			}
			catch (Exception $e) {
				return false;
			}
			return $ret;
		}

		function setCreateColumn($key, $config) {
			switch($config[0]) { // data type
			case 'char':
			case 'decimal':
				$type = $config[0] . '(' . $config[1] . ')';
				break;

			case 'int':
			case 'text':
			case 'mediumtext':
				$type = $config[0];
				break;

			default:
				break;
			}
			return $key . ' ' . $type;
		}

		function alterTable() {
			try {
				// create tmporary table as backup
				$tmp = 'tmp_' . time();
				$sql = "create table $tmp as select * from $this->prefix$this->table";
				$ret = $this->db->query($sql);

				// drop table
				$sql = "drop table $this->prefix$this->table";
				$ret = $this->db->query($sql);

				// create new table
				$this->create();

				// insert backup records
				$sql = "show columns from $tmp";
				$rs = $this->db->query($sql);
			    while($row = mysql_fetch_assoc($rs)) {
					$column[$row['Field']] = $row;
				}
				foreach($this->config as $key => $config) {
					// column
					if(isset($v)) $v.= ',';
					$v.= $column[$key] ? $key : '';
				}

				$sql = "insert into $this->prefix$this->table select $v from $tmp";
				$ret = $this->db->query($sql);

				// drop temporary table
				$sql = "drop table $tmp";
				$ret = $this->db->query($sql);
			}
			catch (Exception $e) {
				return false;
			}
			return $ret;
		}
	}
