<?php
/*
 * B-frame : php web application framework
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	// -------------------------------------------------------------------------
	// class B_DataGrid
	// 
	// -------------------------------------------------------------------------
	class B_DataGrid {
		function __construct($db, $config, $auth_filter=null, $config_filter=null) {
			$this->db = $db;
			$this->page_no = 1;
			$this->callback_index = 0;
			$this->tr_callback_index = 0;
			$this->excel_callback_index = 0;
			$this->config = $config;
			$this->auth_filter = $auth_filter;
			$this->config_filter = $config_filter;
			$this->row_instance = new B_Element($this->config['row'], $this->auth_filter, $this->config_filter);
			$this->terminal_id = TERMINAL_ID;
			if($config['header']) {
				$this->header_conf = $config['header'];
			}

			$this->select_sql = $config['select_sql'];
			$this->count_sql = $config['count_sql'];
			$this->empty_message = $config['empty_message'];
			$this->pager = $config['pager'];
			$this->id = $config['id'];
			$this->name = $config['name'];
			$this->script = $config['script'];
			if($config['param']) {
				$this->param = $config['param'];
			}
		}

		function setSqlGroupBy($sql_string) {
			$this->sql_group_by = $sql_string;
		}

		function setSqlOrderBy($sql_string) {
			$this->sql_order_by = $sql_string;
		}

		function setSortKey($sort_key) {
			$this->sort_key = $sort_key;
		}

		function setSqlWhere($sql_string) {
			$this->sql_where = $sql_string;
		}

		function setSqlLimit($sql_string) {
			$this->sql_limit = $sql_string;
		}

		function setRowPerPage($cnt) {
			$this->pager['row_per_page'] = $cnt;
		}

		function sqlReplace($search, $replace) {
			$this->select_sql = str_replace($search, $replace, $this->select_sql);
			$this->count_sql = str_replace($search, $replace, $this->count_sql);
		}

		function setPage($page_no) {
			$this->page_no = $page_no;
		}

		function bind($data=NULL) {
			// data clear
			$this->row = '';
			$this->bind_data = '';

			// create header objects
			if($this->header_conf) {
				$this->header = new B_Element($this->header_conf, $this->auth_filter, $this->config_filter);
			}

			if(is_array($data)) {
				// data bind from data
				$this->bind_data = $data;

				foreach($this->bind_data as $value) {
					// create row instance
					$row = new B_Element($this->config['row'], $this->auth_filter, $this->config_filter);
					$row->setValue($value);
					$this->row[] = $row;
				}
				$this->record_cnt = count($data);
			}
			else {
				$this->createSQL($sql, $sql_limit);

				// get record data
				$rs = $this->db->query($sql . $sql_limit);

				for($record_cnt=0 ; $value = $this->db->fetch_assoc($rs) ; $record_cnt++) {
					$this->bind_data[] = $value;
					// create row instance
					$row = new B_Element($this->config['row'], $this->auth_filter, $this->config_filter);
					$row->setValue($value);
					$this->row[] = $row;
				}
				if($this->pager) {
					// get record count
					if($this->count_sql) {
						$count_sql = $this->count_sql;
					}
					else {
						$count_sql = "select count(*) cnt from (" . $sql . ") a";
					}
					$rs = $this->db->query($count_sql);
					$row = $this->db->fetch_assoc($rs);
					$this->record_cnt = $row['cnt'];
				}
				else {
					$this->record_cnt = $record_cnt;
				}
			}
		}

		function createSQL(&$sql, &$sql_limit) {
			// data bind from sql
			if(isset($this->pager) && $this->pager['row_per_page'] != 'all') {
				$start_record = ($this->page_no - 1) * $this->pager['row_per_page'];

				if($this->limit_mode == 'replace') {
					$this->select_sql = str_replace('%limit_from%', $start_record, $this->select_sql);
					$this->select_sql = str_replace('%limit_to%', $this->pager['row_per_page'], $this->select_sql);

					$sql = 
						$this->select_sql . 
						$this->sql_where . 
						$this->sql_group_by . 
						$this->sql_order_by;
				}
				else {
					$sql_limit = 
						' limit ' .
						$start_record . ',' .
						$this->pager['row_per_page'];

					$sql = 
						$this->select_sql . 
						$this->sql_where . 
						$this->sql_group_by .
						$this->sql_order_by;
				}
			}
			else {
				$sql = 
					$this->select_sql . 
					$this->sql_where . 
					$this->sql_group_by .
					$this->sql_order_by;
			}

			return;
		}

		function setCaption($caption) {
			$this->caption = $caption;
		}

		function setCallBack($column, $obj, $method) {
			$this->callback[$this->callback_index]['column'] = $column;
			$this->callback[$this->callback_index]['obj'] = $obj;
			$this->callback[$this->callback_index]['method'] = $method;
			$this->callback_index++;
		}

		function setTrCallBack($obj, $method, $param=NULL) {
			$this->tr_callback[$this->tr_callback_index]['obj'] = $obj;
			$this->tr_callback[$this->tr_callback_index]['method'] = $method;
			if($param) {
				$this->_setTrCallBackParam($param);
			}
			$this->tr_callback_index++;
		}

		function _setTrCallBackParam($param) {
			if(is_array($param)) {
				$this->tr_callback[$this->tr_callback_index]['param'] = $param;
			}
			else {
				echo 'ERROR param must be array :setTrCallBackParam';
			}
		}

		function setCsvCallBack($obj, $method, $param=NULL) {
			$this->csv_callback[$this->csv_callback_index]['obj'] = $obj;
			$this->csv_callback[$this->csv_callback_index]['method'] = $method;
			if($param) {
				$this->_setCsvCallBackParam($param);
			}
			$this->csv_callback_index++;
		}

		function _setCsvCallBackParam($param) {
			if(is_array($param)) {
				$this->csv_callback[$this->csv_callback_index]['param'] = $param;
			}
			else {
				echo 'ERROR param must be array :_setCsvCallBackParam';
			}
		}

		function setExcelCallBack($obj, $method, $param=NULL) {
			$this->excel_callback[$this->excel_callback_index]['obj'] = $obj;
			$this->excel_callback[$this->excel_callback_index]['method'] = $method;
			if($param) {
				$this->_setExcelCallBackParam($param);
			}
			$this->excel_callback_index++;
		}

		function _setExcelCallBackParam($param) {
			if(is_array($param)) {
				$this->excel_callback[$this->excel_callback_index]['param'] = $param;
			}
			else {
				echo 'ERROR param must be array :_setExcelCallBackParam';
			}
		}

		function setValidateCallBack($obj, $method, $param=NULL) {
			$this->validate_callback[$this->validate_callback_index]['obj'] = $obj;
			$this->validate_callback[$this->validate_callback_index]['method'] = $method;
			if($param) {
				$this->_setValidateCallBackParam($param);
			}
			$this->validate_callback_index++;
		}

		function _setValidateCallBackParam($param) {
			if(is_array($param)) {
				$this->validate_callback[$this->validate_callback_index]['param'] = $param;
			}
			else {
				echo 'ERROR param must be array :_setValidateCallBackParam';
			}
		}

		function setPagerStatus($param) {
			switch($param) {
			case 'T':
				$this->pager['location']['top'] = 'true';
				$this->pager['location']['bottom'] = 'false';
				break;

			case 'B':
				$this->pager['location']['top'] = 'false';
				$this->pager['location']['bottom'] = 'true';
				break;

			case 'TB':
				$this->pager['location']['top'] = 'true';
				$this->pager['location']['bottom'] = 'true';
				break;

			case 'N':
				$this->pager['location']['top'] = 'false';
				$this->pager['location']['bottom'] = 'false';
				break;
			}
		}

		function getRecordCount() {
			return $this->record_cnt;
		}

		function getHiddenHtml() {
			return;
		}

		function getHtml($mode=NULL) {
			if($this->record_cnt == 0 || count($this->row) == 0 || !is_array($this->row)) {
				return $this->empty_message;
			}
			$i=0;

			// tr call back
			for($i=0 ; $i < $this->tr_callback_index ; $i++) {
				for($j=0 ; $j < count($this->row) ; $j++) {
					$param = array('row' => &$this->row[$j], 'cnt' => $j);

					if($this->tr_callback[$i]['param']) {
						if(is_array($this->tr_callback[$i]['param'])) {
							foreach($this->tr_callback[$i]['param'] as $key => $value) {
								$param[$key] = $value;
							}
						}
					}

					$obj = $this->tr_callback[$i]['obj'];
					$method = $this->tr_callback[$i]['method'];
					if($obj) {
						if(method_exists($obj, $method)) {
							$obj->$method($param);
						}
					}
					else {
						call_user_func($method, $param);
					}
				}
			}

			//pager top
			if($this->pager && $this->pager['location']['top'] == 'true') {
				$pager_html = $this->showPager();
				$html.= $pager_html;
			}

			$html.= $this->config['start_html'] . "\n";

			// get caption html
			if($this->caption) {
				$html.= '<caption>' . $this->caption . '</caption>';
			}
			// get header html
			if($this->header) {
				$html.= $this->getHeaderHtml($mode);
			}
			// get row html
			$html.= $this->getRowHtml($mode);

			$html.= $this->config['end_html'];

			//pager bottom
			if($this->pager && $this->pager['location']['bottom'] == 'true') {
				$html.= $pager_html;
			}

			return $html;
		}

		function getHeaderHtml($mode) {
			if($this->filter_value) {
				$this->header->setFilterValue($this->filter_value);
			}
			if($this->sort_key) {
				$obj =& $this->header->getElementByFieldName('sort_key', $this->sort_key);
				$obj->special_html = $obj->cond_html;
			}
			return $this->header->getHtml($mode);
		}

		function getRowHtml($mode) {
			if(!is_array($this->row)) {
				return;
			}

			foreach($this->row as $row) {
				if($this->filter_value) {
					$row->setFilterValue($this->filter_value);
				}
				$html.= $row->getHtml($mode);
			}
			return $html;
		}

		function setValue($value) {
			if(is_array($this->row)) {
				$i=0;
				foreach($this->row as $row) {
					$row->setValue($value[$i++]);
				}
			}
		}

		function getValue(&$param) {
			if($this->row) {
				$i=0;
				if(is_array($this->row)) {
					foreach($this->row as $row) {
						$row->getValue($param[$i++]);
					}
				}
				else {
					$this->row->getValue($param[$i++]);
				}
			}
		}

		function validate() {
			$ret = true;

			for($i=0 ; $i < count($this->row) ; $i++) {
				for($j=0 ; $j < $this->validate_callback_index ; $j++) {
					$param = array('row' => &$this->row[$i], 'cnt' => $i);
					if($this->validate_callback[$j]['param']) {
						if(is_array($this->validate_callback[$j]['param'])) {
							foreach($this->validate_callback[$j]['param'] as $key => $value) {
								$param[$key] = $value;
							}
						}
					}

					$obj = $this->validate_callback[$j]['obj'];
					$method = $this->validate_callback[$j]['method'];
					if(method_exists($obj, $method)) {
						$ret &= $obj->$method($param);
					}
				}

				$ret &= $this->row[$i]->validate();
			}

			return $ret;
		}

		function setFilterValue($value) {
			$this->filter_value[] = $value;
		}

		function getElementByName() {
			return;
		}

		function getElementByNameFromRow($name) {
			foreach($this->row as $row) {
				$obj = $row->getElementByName($name);
				if($obj) {
					break;
				}
			}
			return $obj;
		}

		function getElementByNameFromHeader($name) {
			$obj = $this->header->getElementByName($name);
			return $obj;
		}

		function getElementByNameFromRowInstance($name) {
			$obj = $this->row_instance->getElementByName($name);
			return $obj;
		}

		function unsetRow() {
			unset($this->row);
		}

		function getCsv($config) {
			$this->createSQL($sql, $sql_limit);
			$this->csv_config = $config;

			// get header html
			if($this->csv_config['header']) {
				$this->echoHeaderCsv();		// echo header csv
			}

			// get record data
			$rs = $this->db->query($sql . $sql_limit);
			for($record_cnt=0 ; $row = $this->db->fetch_assoc($rs) ; $record_cnt++) {
				$this->echoRowCsv($row);		// echo row csv
			}

			return;
		}

		function echoHeaderCsv() {
			unset($csv);

			foreach($this->csv_config['header'] as $value) {
				if($csv) {
					$csv.= $this->csv_config['delimiter'];
				}
				$csv.= $value;
			}

			// convert charcter set to sjis-win
			$csv = mb_convert_encoding($csv, 'sjis-win', 'auto');
			echo $csv . "\n";
		}

		function echoRowCsv($row) {
			global $g_data_set, ${$g_data_set};
			unset($csv);

			// csv call back
			for($i=0 ; $i < $this->csv_callback_index ; $i++) {
				for($j=0 ; $j < count($this->row) ; $j++) {
					$param = array('row' => &$this->row[$j], 'cnt' => $j);

					if($this->csv_callback[$i]['param']) {
						if(is_array($this->csv_callback[$i]['param'])) {
							foreach($this->csv_callback[$i]['param'] as $key => $value) {
								$param[$key] = $value;
							}
						}
					}

					$obj = $this->csv_callback[$i]['obj'];
					$method = $this->csv_callback[$i]['method'];
					if(method_exists($obj, $method)) {
						$obj->$method($param);
					}
				}
			}

			if($row['display'] == 'none') {
				continue;
			}
			foreach($this->csv_config['row'] as $key => $config) {
				unset($item);
				unset($data_set);

				switch($config['type']) {
				case 'select':
					if(isset($config['data_set'])) {
						if(isset($config['default'])) {
							if($row[$key] == '') {
								$item = $config['default'];
							}
						}
						else {
							$item = ${$g_data_set}[$config['data_set']][$row[$key]];
						}
					}
					break;

				case 'plural':
					if(isset($config['data_set'])) {
						if(is_array($config['data_set'])) {
							foreach($config['data_set'] as $value) {
								if($data_set) {
									$data_set = $data_set + ${$g_data_set}[$value];
								}
								else {
									$data_set = ${$g_data_set}[$value];
								}
							}
						}
						else {
							$data_set = ${$g_data_set}[$config['data_set']];
						}

						$a = explode('/', $row[$key]);
						$i=0;
						foreach($data_set as $key2 => $value2) {
							if(substr($key2, 0, 2) == 'LF') {
								continue;
							}
							if($i) {
								$item.= $this->csv_config['delimiter'];
							}
							foreach($a as $v) {
								if($key2 == $v) {
									$item.= $config['value'];
									break;
								}
							}
							$i++;
						}
					}
					break;

				case 'date':
					$item = date($config['format'], $row[$key]);
					break;

				case 'convert':
					$item = $this->convertText($row[$key], $config);
					break;

				case 'textarea':
					$row[$key] = str_replace("\r\n", ' ', $row[$key]);
					$row[$key] = str_replace("\n", ' ', $row[$key]);
					$item = $row[$key];
					break;

				default:
					$item = $row[$key];
					break;
				}

				if($csv) {
					$csv.= $this->csv_config['delimiter'];
				}
				$csv.= $item;
			}

			// convert charcter set to sjis-win
			$csv = mb_convert_encoding($csv, 'sjis-win', 'auto');
			echo $csv . "\n";

			return;
		}

		function convertText($value, $config) {
			if(!is_array($config)) {
				return $value;
			}
			foreach($config as $val) {
				$arr = explode('/', $val['from']);
				if($arr) {
					foreach($arr as $from) {
						$value = str_replace($from, $val['to'], $value);
					}
				}
			}
			return $value;
		}

		function getExcel($config, $outfile, $bind_data=null) {
			set_time_limit(300);

			$this->excel_config = $config;

			$this->excel = new B_Excel;
			$this->excel->setInternalCharset(B_CHARSET);

			$sheet = 0;

			// revise
			$this->excel->reviseFile1($config['template_file']);

			// header
			if($this->excel_config['header']) {
				$this->getHeaderExcel($sheet);
			}

			// row
			$this->getRowExcel($sheet, $bind_data);

			// remove template sheet
			foreach($this->excel_config['template_sheet'] as $value) {
				$this->excel->rmSheet($value);
			}

			// revise
			$this->excel->reviseFile2($outfile);

			unset($this->excel);

			return;
		}

		function getHeaderExcel($sheet) {
			$refsheet = $sheet;

			if($this->excel_config['print_date']) {
				$print_date_row = $this->excel_config['print_date_row'];
				$print_date_col = $this->excel_config['print_date_col'];
				$this->excel->_addString($sheet, $print_date_row, $print_date_col, date($this->excel_config['print_date']), $print_date_row, $print_date_col, $refsheet);
			}

			$refrow = $this->excel_config['header_start_row'];
			$refcol = $this->excel_config['header_start_col'];
			$merge_count=0;
			foreach($this->excel_config['header'] as $key => $value) {
				$this->excel->_addString($sheet, $value['row_num'], $value['col_num'], $value['value'], $refrow, $refcol, $refsheet);
				if($last_value != $value['value'] || $last_row != $value['row_num'] || $last_form_id != $value['form_id']) {
					if($merge_count) {
						$this->excel->setCellMerge($sheet, $last_row, $last_row, $last_col, $last_col + $merge_count);
					}
					$merge_count=0;
					$last_value = $value['value'];
					$last_form_id = $value['form_id'];
					$last_row = $value['row_num'];
					$last_col = $value['col_num'];
				}
				else {
					$merge_count++;
				}
			}
			if($merge_count) {
				$this->excel->setCellMerge($sheet, $last_row, $last_row, $last_col, $last_col + $merge_count);
			}
		}

		function getRowExcel($sheet, $bind_data=null) {
			global $g_data_set, ${$g_data_set};

			$callback_obj = $this->excel_callback['obj'];
			$callback_method = $this->excel_callback['method'];
			$row_num = $this->excel_config['detail_start_row'];

			if($bind_data) {
				foreach($bind_data as $row) {
					$this->_getRowExcel($sheet, $row_num, $row);
					$row_num++;
				}
			}
			else {
				$this->createSQL($sql, $sql_limit);
				$rs = $this->db->query($sql . $sql_limit);
				for($record_cnt=0 ; $row = $this->db->fetch_assoc($rs) ; $record_cnt++) {
					$this->_getRowExcel($sheet, $row_num, $row);
					$row_num++;
				}
			}

			return;
		}

		function _getRowExcel($sheet, $row_num, $row) {
			global $g_data_set, ${$g_data_set};

			if($row['display'] == 'none') {
				continue;
			}

			$refsheet = $sheet;
			$refrow = $this->excel_config['detail_start_row'];
			$refcol = $this->excel_config['detail_start_col'];

			for($i=0 ; $i < $this->excel_callback_index ; $i++) {
				$param = array( 'row' => &$row);

				$obj = $this->excel_callback[$i]['obj'];
				$method = $this->excel_callback[$i]['method'];
				if(method_exists($obj, $method)) {
					$obj->$method($param);
				}
			}

			$col_num = $this->excel_config['detail_start_col'];
			foreach($this->excel_config['row'] as $key => $config) {
				unset($item);
				unset($data_set);

				switch($config['type']) {
				case 'select':
					if(isset($config['data_set']) && $row[$key]) {
						if(is_array($config['data_set'])) {
							$item = $config['data_set'][$row[$key]];
						}
						else  {
							$item = ${$g_data_set}[$config['data_set']][$row[$key]];
						}
					}
					break;

				case 'plural':
					if(isset($config['data_set']) && $row[$key]) {
						if(is_array($config['data_set'])) {
							$data_set = $config['data_set'];
						}
						else {
							$data_set = ${$g_data_set}[$config['data_set']];
						}

						$a = explode('/', $row[$key]);
						foreach($a as $value) {
							if($item) $item.='/';
							$item.= $data_set[$value];
						}
					}
					break;

				case 'date':
					$item = date($config['format'], $row[$key]);
					break;

				case 'convert':
					$item = $this->convertText($row[$key], $config);
					break;

				case 'textarea':
					$item = $row[$key];
					break;

				default:
					$item = $row[$key];
					break;
				}

				$this->excel->_addString($sheet, $row_num, $col_num, $item, $refrow, $refcol, $refsheet);
				$col_num++;
			}
		}

		function getHeaderObj($name) {
			return $this->header->getElementByName($name);
		}

		function showPager() {
			if($this->pager['row_per_page'] == 'all') {
				$record_from = 1;
				$record_to = $this->record_cnt;
				$total_page_cnt = 1;
			}
			else {
				$record_from = $this->pager['row_per_page'] * ($this->page_no - 1) + 1;

				if($this->record_cnt < $this->pager['row_per_page'] * $this->page_no) {
					$record_to = $this->record_cnt;
				}
				else {
					$record_to = $this->pager['row_per_page'] * $this->page_no;
				}

				$total_page_cnt = floor($this->record_cnt / $this->pager['row_per_page']);
				if($this->record_cnt % $this->pager['row_per_page']) {
					$total_page_cnt += 1;
				}
			}

			$disp_image = $this->pager['disp_image'];

			$html = $this->pager['start_html'] . "\n";

			foreach($this->pager['param'] as $key => $value) {
				$this->setParam($param, $key, $value);
			}

			if($this->page_no == 1) {
				$html.= $disp_image['top_image']['start_html'];
				$html.= $disp_image['top_image']['value'];
				$html.= $disp_image['top_image']['end_html'];

				$html.= $disp_image['prev_image']['start_html'];
				$html.= $disp_image['prev_image']['value'];
				$html.= $disp_image['prev_image']['end_html'];
			}
			else {
				$html.= $disp_image['top_image']['start_html'];
				$html.= '<a href="' . $this->pager['link'] . $param . '&page_no=1">';
				$html.= $disp_image['top_image']['value'];
				$html.= '</a>';
				$html.= $disp_image['top_image']['end_html'];

				$html.= $disp_image['prev_image']['start_html'];
				$html.= '<a href="' . $this->pager['link'] . $param . '&page_no=' . ($this->page_no - 1) . '">';
				$html.= $disp_image['prev_image']['value'];
				$html.= '</a> ' . "\n";
				$html.= $disp_image['prev_image']['end_html'];
			}

			if($this->pager['page_link_max']) {
				if($this->pager['page_link_max'] < $total_page_cnt) {
					$limit = $this->pager['page_link_max'];
					$start_page = $this->page_no - floor($limit / 2);
					if($start_page < 1) {
						$start_page = 1;
					}
					if($start_page > ($total_page_cnt - $this->pager['page_link_max'])) {
						$start_page = $total_page_cnt - $this->pager['page_link_max'] + 1;
					}
				}
				else {
					$limit = $total_page_cnt;
					$start_page = 1;
				}
			}
			for($i=0 ; $i < $limit ; $i++) {
				if($i+$start_page == $this->page_no) {
					$html.= $disp_image['current_page']['start_html'] . $this->page_no . $disp_image['current_page']['end_html'];
				}
				else {
					$html.= $disp_image['other_page']['start_html'];
					$html.= '<a href="' . $this->pager['link'] . $param . '&page_no=' . ($i + $start_page) . '">'. ($i + $start_page) . '</a>';
					$html.= $disp_image['other_page']['end_html'];
				}
			}

			if($this->page_no < $total_page_cnt) {
				$html.= $disp_image['next_image']['start_html'];
				$html.= '<a href="' . $this->pager['link'] . $param . '&page_no=' . ($this->page_no + 1) . '">';
				$html.= $disp_image['next_image']['value'];
				$html.= '</a>';
				$html.= $disp_image['next_image']['end_html'];

				$html.= $disp_image['last_image']['start_html'];
				$html.= '<a href="' . $this->pager['link'] . $param . '&page_no=' . $total_page_cnt . '">';
				$html.= $disp_image['last_image']['value'];
				$html.= '</a>';
				$html.= $disp_image['last_image']['start_html'];
			}
			else {
				$html.= $disp_image['next_image']['start_html'];
				$html.= $disp_image['next_image']['value'];
				$html.= $disp_image['next_image']['end_html'];
				$html.= $disp_image['last_image']['start_html'];
				$html.= $disp_image['last_image']['value'];
				$html.= $disp_image['last_image']['end_html'];
			}

			$html.= $disp_image['information']['start_html'];
			$html.= $disp_image['information']['record_cnt']['start_html'];
			$html.= $this->record_cnt;
			$html.= $disp_image['information']['record_cnt']['end_html'];
			$html.= $disp_image['information']['record_from']['start_html'];
			$html.= $record_from;
			$html.= $disp_image['information']['record_from']['end_html'];
			$html.= $disp_image['information']['record_to']['start_html'];
			$html.= $record_to;
			$html.= $disp_image['information']['record_to']['end_html'];
			$html.= $disp_image['information']['end_html'];

			$html.= $this->pager['end_html'] . "\n";

			return $html;
		}

		function setParam(&$param, $key, $value) {
			if($param) {
				$param.= '&';
			}
			else{
				$param.= '?';
			}
			$param.= $key . '=' . $value;
		}
	}
