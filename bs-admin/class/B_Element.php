<?php
/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	// -------------------------------------------------------------------------
	// class B_Element
	// 
	// -------------------------------------------------------------------------
	class B_Element {
		function __construct($config, $user_auth=NULL, $config_filter=NULL, &$parent=NULL, $level=0) {
			if(!is_array($config)) {
				$this->error('CONFIG ERROR');
				return;
			}

			$this->config = $config;
			$this->parent = &$parent;
			$this->validation = true;
			$this->level = $level;

			foreach($config as $key => $value) {
				if(is_array($value)) {
					if(!$this->checkFilter($value['auth_filter'], $user_auth) || !$this->checkFilter($value['config_filter'], $config_filter)) {
						continue;
					}
					if(is_numeric($key) || $key == 'element') {
						$class = $value['class'] ? $value['class'] : 'B_Element';
						if(class_exists($class)) {
							$element = new $class($value, $user_auth, $config_filter, $this, $level+1);
						}
						else {
							$this->error('CLASS:' . $class . ' not exists');
						}
						$this->addElement($element);
						continue;
					}
					switch($key) {
						case 'validate':
						case 'convert_text':
						case 'script':
						case 'row':
						case 'data_set':
						case 'item':
						case 'special_text':
							$this->$key = $value;
							break;
					}

					$this->config_org[$key] = $value;
				}
				else {
					$this->$key = $value;
					$this->config_org[$key] = $value;
				}
			}
			$this->data_set_value = $this->getDataSetValue($this->data_set);
		}

		function addElement($element) {
			$this->elements[] = &$element;
		}

		function checkFilter($filter, $value) {
			if(!isset($filter)) return true;

			if(substr($filter, 0, 1) == '!') {
				$filter = substr($filter, 1);
				$mode = 'deny';
			}

			$filter_array = explode('/', $filter);
			foreach($filter_array as $f) {
				if($this->_checkFilter($f, $value)) {
					return $mode == 'deny' ? false : true;
				}
			}
			return $mode == 'deny' ? true : false;
		}

		function _checkFilter($filter, $value) {
			if(is_array($value)) {
				foreach($value as $v) {
					if($this->_checkFilter($filter, $v)) {
						return true;
					}
				}
			}
			else if($filter == $value) {
				return true;
			}
			return false;
		}

		function getConfig() {
			if(is_array($this->config_org)) {
				foreach($this->config_org as $key => $value) {
					if(!is_numeric($key) && $key != 'element') {
						$config[$key] = $value;
					}
				}
			}
			if($this->id) $config['id'] = $this->id;
			if($this->name) $config['name'] = $this->name;
			if($this->value) $config['value'] = $this->value;
			if($this->data_set) $config['data_set'] = $this->data_set;
			if($this->fixedparam) $config['fixedparam'] = $this->fixedparam;
			if($this->param) $config['param'] = $this->param;

			if(isset($this->elements)) {
				foreach($this->elements as $element) {
					$config[] = $element->getConfig();
				}
			}
			return $config;
		}

		function getDataSetValue($data_set) {
			global $g_data_set, ${$g_data_set};

			if($data_set) {
				if(is_array($data_set)) {
					if($this->local) {
						$data_set_value = $data_set;
					}
					else {
						foreach($data_set as $value) {
							if(is_array($data_set_value)) {
								$data_set_value = $data_set_value + ${$g_data_set}[$value];
							}
							else {
								$data_set_value = ${$g_data_set}[$value];
							}
						}
					}
				}
				else {
					$data_set_value = ${$g_data_set}[$data_set];
				}
				return $data_set_value;
			}
		}

		function getElementById($id) {
			if($id == $this->id) {
				return $this;
			}
			if(isset($this->elements)) {
				foreach($this->elements as $element) {
					$target = $element->getElementById($id);
					if($target != NULL) {
						return $target;
					}
				}
			}
			return NULL;
		}

		function getElementByName($name) {
			if($name == $this->name) {
				return $this;
			}
			if(isset($this->elements)) {
				foreach($this->elements as $element) {
					$target = $element->getElementByName($name);
					if($target != NULL) {
						return $target;
					}
				}
			}
			return NULL;
		}

		function getElementsByName($name) {
			$target = array();

			if(isset($this->elements)) {
				foreach($this->elements as $element) {
					$t = $element->getElementsByName($name);
					if(is_array($t)) {
						$target = array_merge($target, $t);
					}
				}
			}
			if($name == $this->name) {
				$target[] = $this;
			}
			return $target;
		}

		function getElementByFieldName($field_name, $name) {
			if($name == $this->$field_name) {
				return $this;
			}
			if(isset($this->elements)) {
				foreach($this->elements as $element) {
					$target = $element->getElementByFieldName($field_name, $name);
					if($target != NULL) {
						return $target;
					}
				}
			}
			return NULL;
		}

		function getElementsByClassName($className) {
			$target = array();

			if(isset($this->elements)) {
				foreach($this->elements as $element) {
					$t = $element->getElementsByClassName($className);
					if(is_array($t)) {
						$target = array_merge($target, $t);
					}
				}
			}
			$class = explode('/', $className);
			foreach($class as $c) {
				if(get_class($this) == $c) {
					$target[] = $this;
				}
			}
			return $target;
		}

		function setProperty($property, $value) {
			$this->$property = $value;
		}

		function setNamePrefix($prefix) {
			if(isset($this->elements)) {
				foreach($this->elements as $element) {
					$element->setNamePrefix($prefix);
				}
			}
			$this->name_prefix = $prefix;
		}

		function setValue($value) {
			if(isset($this->elements)) {
				foreach($this->elements as $element) {
					$element->setValue($value);
				}
			}
			$this->_setValue($value);
		}

		function _setValue($value) {
			if(substr($this->name, strlen($this->name)-2, 2) == '[]') {
				$name = $this->name_prefix . substr($this->name, 0, strlen($this->name)-2);
			}
			else {
				$name = $this->name_prefix . $this->name;
			}

			if(isset($value[$name])) {
				$this->value = $this->_prepareInput($value[$name]);
			}

			if($this->number_format) {
				$this->value = str_replace(',', '', $this->value);
			}
		}

		function setFilterValue($value) {
			if(isset($this->elements)) {
				foreach($this->elements as $element) {
					$element->setFilterValue($value);
				}
			}
			$this->filter_value[] = $value;
		}

		function replaceText($text, $data) {
			foreach($data as $key => $value) {
				$replace_string = '%' . $key . '%';
				$text = str_replace($replace_string, $value, $text);
			}
			return $text;
		}

		function getValue(&$param) {
			if(isset($this->elements)) {
				foreach($this->elements as $element) {
					$element->getValue($param);
				}
			}
			$this->_getValue($param);
		}

		function _getValue(&$param) {
			if(!$this->class || !$this->name) return;

			if(substr($this->name, strlen($this->name)-2, 2) == '[]') {
				$name = substr($this->name, 0, strlen($this->name)-2);
			}
			else {
				$name = $this->name;
			}

			$param[$name] = $this->value;
		}

		function getHtml($mode=NULL) {
			if(!$this->checkFilter($this->filter, $this->filter_value)) {
				return;
			}

			if(isset($this->display) && $this->display == 'none') {
				return;
			}

			if($mode == 'confirm' && $this->confirm_mode == 'none') {
				return;
			}

			$start_html = $this->getStartHtml($mode);
			$inner_html = $this->getInnerHTML($mode);
			$end_html = $this->getEndHtml($mode);

			if($this->empty == 'no-display' && !$inner_html) {
				return;
			}
			$html = $start_html . $inner_html . $end_html;
			if(!isset($this->no_linefeed) && $html) {
				$html.= "\n";
			}
			return $html;
		}

		function getStartHtml($mode=null) {
			if($this->getValidationStatus() == false && isset($this->invalid_start_html)) {
				return $this->invalid_start_html;
			}
			if($mode == 'confirm' && isset($this->confirm_start_html)) {
				return $this->confirm_start_html;
			}
			return $this->start_html;
		}

		function getValidationStatus() {
			for($obj=$this->parent ; $obj->parent ; $obj=$obj->parent) {
				if($obj->error_group) {
					return $obj->validation;
				}
			}
			return $this->validation;
		}

		function getEndHtml($mode=null) {
			if($mode == 'confirm' && isset($this->confirm_end_html)) {
				$html = $this->confirm_end_html;
			}
			else {
				$html = $this->end_html;
			}
			return $html;
		}

		function getInnerHTML($mode) {
			$html = $this->getElementsHtml($mode);

			if(isset($this->elements)) {
				foreach($this->elements as $element) {
					$html.= $element->getHtml($mode);
				}
			}
			return $html;
		}

		function getElementsHtml($mode=null) {
			$value = $this->value;

			if($this->strip_tags) {
				$value = strip_tags($value);
			}

			if($this->number_format && is_numeric($value)) {
				if($value || !$this->zero_suppress) {
					$value = number_format(str_replace(',', '', $value));
				}
			}

			if($this->shorten_text) {
				$value = $this->shortenText($value, $this->shorten_text);
			}

			return $value;
		}

		function shortenText($item, $length) {
			$text = mb_strimwidth($item, 0, $length, '...');
			return $text;
		}

		function getHiddenHtml() {
			if(!$this->checkFilter($this->filter, $this->filter_value)) {
				return;
			}

			if(isset($this->display) && $this->display == 'none') {
				return;
			}

			if(isset($this->elements)) {
				foreach($this->elements as $element) {
					$html.= $element->getHiddenHtml();
				}
			}

			if(method_exists($this, '_getHiddenHtml')) {
				$html.= $this->_getHiddenHtml();
			}

			return $html;
		}

		function _gethtmlid() {
			$id = strlen($this->id) ? $this->id : $this->name;
			return $this->name_prefix . $id;
		}

		function _prepareInput($input) {
		    if(is_array($input)) {
				foreach($input as $key => $value) {
					if(is_array($value)) {
						$output[$key] = $this->_prepareInput($value);
					}
					else {
						$value = $this->sanitize($value);
						$output[$key] = $value;
					}
				}
			}
			else {
				$output = $this->sanitize($input);
			}
		    return $output;
		}

		function sanitize($value) {
			// trim
			if($this->mb_no_trim) {
				$value = trim($value);
			}
			else if(!$this->no_trim) {
				$value = $this->mb_trim($value);
			}

			// convert sigle byte to multi byte
			if(function_exists('mb_convert_kana')) $value = mb_convert_kana($value, 'KV');

			// convert
			if($this->convert) {
				if(function_exists('mb_convert_kana')) $value = mb_convert_kana($value, $this->convert);
			}
			if($this->convert_text) {
				$value = $this->convert_text($value, $this->convert_text);
			}
			if($this->convert_dateformat && $value) {
				$value = $this->convert_dateformat($value, $this->convert_dateformat);
			}

			return $value;
		}

		function mb_trim($str) {
			if(function_exists('mb_convert_kana')) {
				$s = mb_convert_kana(' ', 'S');
				$expression = '/^[\s' . $s . ']*(.*?)[\s' . $s . ']*$/u';
				$str = preg_replace($expression, '\1', $str);
			}
			return $str;
		}

		function convert_text($value, $config) {
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

		function convert_dateformat($value, $config) {
			if($value) {
				$date = explode('/', $value);
				return sprintf($config, $date[0], $date[1], $date[2]);
			}
		}

		function setValidateCallBack($obj, $method, $param, $message) {
			$array['type'] = 'callback';
			$array['obj'] = $obj;
			$array['method'] = $method;
			$array['param'] = $param;
			$array['error_message'] = $message;

			$this->validate[] = $array;
		}

		function checkAlt($row, $error_message) {
			$status = true;
			if(isset($this->elements)) {
				foreach($this->elements as $element) {
					if(method_exists($element, 'checkAlt')) {
						$status &= $element->checkAlt($row, $error_message);
					}
				}
			}

			$status &= $this->_checkAlt($row);

			if(!$status) {
				$err_obj = $this->searchElementByName('error_message');
				if($err_obj) $err_obj->value = $error_message;
				$this->validation = false;
			}

			return $status;
		}

		function _checkAlt($row) {
			if(isset($row[$this->name])) {
				$value = isset($this->data_value) ? $this->data_value : $this->value;
				if($value != $row[$this->name]) {
					return false;
				}
			}
			return true;
		}

		function validate() {
			if($this->validate == 'none') return true;

			if(isset($this->elements)) {
				foreach($this->elements as $element) {
					$this->validation &= $element->validate();
				}
			}
			if(isset($this->validate) && is_array($this->validate)) {
				$this->validation &= $this->_validate();
			}

			return $this->validation;
		}

		function _validate() {
			foreach($this->validate as $config) {
				switch($config['type']) {
				case 'status':
					if(!$this->status) {
						$err_obj = $this->searchElementByName('error_message');
						if($err_obj) $err_obj->value = $config['error_message'];
						return false;
					}
					break;

				case 'callback':
					$param = $config['param'];
					$param['obj'] = $this;
					$param['value'] = $this->value;
					$obj = $config['obj'];
					$method = $config['method'];
					if(method_exists($obj, $method)) {
						if(!$obj->$method($param)) {
							$err_obj = $this->searchElementByName('error_message');
							if($err_obj) {
								if($config['error_message']) {
									$err_obj->value = $config['error_message'];
								}
								if($param['error_message']) {
									$err_obj->value = $param['error_message'];
								}
							}
							return false;
						}
					}
					break;

				case 'required':
					if($config['option'] == 'numeric') {
						if(!(int)$this->value) {
							$err_obj = $this->searchElementByName('error_message');
							if($err_obj) $err_obj->value = $config['error_message'];
							return false;
						}
					}
					else if(($this->class == 'B_Radio' && $this->data_value == '') ||
					   ($this->class == 'B_Checkbox' && !$this->checked) ||
					   ($this->class != 'B_Radio' && $this->value == '') ||
					   ($this->class == 'B_SelectBox' && $this->data_set_value[$this->value] == '')) {
						$err_obj = $this->searchElementByName('error_message');
						if($err_obj) $err_obj->value = $config['error_message'];
						return false;
					}
					break;

				case 'type_message':
					$message_obj = $this->searchElementByName('type_message');
					$message_obj->value = $config[$status];
					break;

				case 'match':
					if(isset($config['target'])) {
						$top = $this->getRootObject();

						$pair_obj = $top->getElementByName($config['target']);
						if(isset($pair_obj)) {
							if($this->value != $pair_obj->value) {
								$err_obj = $this->searchElementByName('error_message');
								if($err_obj) $err_obj->value = $config['error_message'];
								return false;
							}
						}
					}
					break;

				case 'kana':
					$this->value = mb_convert_kana($this->value, 'CKV');
					if(!$this->checkKana($this->value)) {
						$err_obj = $this->searchElementByName('error_message');
						if($err_obj) $err_obj->value = $config['error_message'];
						return false;
					}
					break;

				case 'hiragana':
					$this->value = mb_convert_kana($this->value, 'cHV');
					if(!$this->checkKana($this->value)) {
						$err_obj = $this->searchElementByName('error_message');
						if($err_obj) $err_obj->value = $config['error_message'];
						return false;
					}
					break;

				case 'alpha':
					if(!$this->checkAlpha($this->value)) {
						$err_obj = $this->searchElementByName('error_message');
						if($err_obj) $err_obj->value = $config['error_message'];
						return false;
					}
					break;

				case 'numeric':
					if(trim($this->value) && !is_numeric($this->value)) {
						$err_obj = $this->searchElementByName('error_message');
						if($err_obj) $err_obj->value = $config['error_message'];
						return false;
					}
					break;

				case 'alphanum':
					if(!$this->checkAlphaNum($this->value)) {
						$err_obj = $this->searchElementByName('error_message');
						if($err_obj) $err_obj->value = $config['error_message'];
						return false;
					}
					break;

				case 'range':
					if(!$this->checkNum($this->value) || 
						($config['min'] && $this->value < $config['min']) ||
						($config['max'] && $this->value > $config['max'])) {
						$err_obj = $this->searchElementByName('error_message');
						if($err_obj) $err_obj->value = $config['error_message'];
						return false;
					}
					break;

				case 'length':
					$len = mb_strlen($this->value);
					if($len < $config['min'] || $len > $config['max']) {
						$err_obj = $this->searchElementByName('error_message');
						if($err_obj) $err_obj->value = $config['error_message'];
						return false;
					}
					break;

				case 'pattern':
					if(isset($this->value) && $this->value != '') {
						if($config['delimiter']) {
							$value_list = explode($config['delimiter'], $this->value);
						}
						else {
							$value_list[0] = $this->value;
						}
						foreach($value_list as $value) {
							if(!$this->checkPattern($value, $config['pattern'])) {
								$err_obj = $this->searchElementByName('error_message');
								if($err_obj) $err_obj->value = $config['error_message'];
								return false;
							}
						}
					}
					break;

				case 'denaial_pattern':
					if(isset($this->value)) {
						if($config['delimiter']) {
							$value_list = explode($config['delimiter'], $this->value);
						}
						else {
							$value_list[0] = $this->value;
						}
						foreach($value_list as $value) {
							if($this->checkPattern($value, $config['pattern'])) {
								$err_obj = $this->searchElementByName('error_message');
								if($err_obj) $err_obj->value = $config['error_message'];
								return false;
							}
						}
					}
					break;

				case 'emailMX':
					if(isset($this->value) && $this->value != '') { // not required
						if(substr(PHP_OS, 0, 3) === 'WIN') break;
						if($config['delimiter']) {
							$value_list = explode($config['delimiter'], $this->value);
						}
						else {
							$value_list[0] = $this->value;
						}
						foreach($value_list as $value) {
							if(!$this->checkEmailMX($value)) {
								$err_obj = $this->searchElementByName('error_message');
								if($err_obj) $err_obj->value = $config['error_message'];
								return false;
							}
						}
					}
					break;

				case 'combination_require':
					if(!$this->checkCombinationRequire($config['target'])) {
						$err_obj = $this->searchElementByName('error_message');
						if($err_obj) $err_obj->value = $config['error_message'];
						return false;
					}
					break;

				case 'compareValue':
					if($this->value != $this->compareValue) {
						$err_obj = $this->searchElementByName('error_message');
						if($err_obj) $err_obj->value = $config['error_message'];
						return false;
					}
					break;

				case 'text_datetime':
					if(isset($this->value) && $this->value != '') { // not required
						if(preg_match('/^(\d\d\d\d)\/(\d\d)\/(\d\d) (\d\d):(\d\d)$/', $this->value, $m)) {
							if(@checkdate($m[2], $m[3], $m[1])) { // checkdate(month, date, year)
								if($this->checkTime($m[4], $m[5])) {
									if(strtotime($this->value)) {
										break;
									}
									else {
										$reason = 1;
									}
								}
								else {
									$reason = 2;
								}
							}
							else {
								$reason = 3;
							}
						}
						else {
							$reason = 4;
						}
						$reason_message = $this->data_set_value[$reason];
						$err_obj = $this->searchElementByName('error_message');
						if($err_obj) $err_obj->value = $config['error_message'] . $reason_message;
						return false;
					}
					break;

				case 'text_date':
					if(isset($this->value) && $this->value != '') { // not required
						$date = explode('/', $this->value);
						if(is_array($date) && count($date) == 3) {
							$ret = @checkdate($date[1], $date[2], $date[0]); // checkdate(month, date, year)
							if($ret) {
								break;
							}
						}
						$err_obj = $this->searchElementByName('error_message');
						if($err_obj) $err_obj->value = $config['error_message'];
						return false;
					}
					break;

				case 'text_year_month':
					if(isset($this->value) && $this->value != '') { // not required
						$date = explode($config['delimiter'], $this->value);
						if(is_array($date) && count($date) == 2) {
							$ret = @checkdate($date[1], '01', $date[0]); // checkdate(month, date, year)
							if($ret) {
								break;
							}
						}
						$err_obj = $this->searchElementByName('error_message');
						if($err_obj) $err_obj->value = $config['error_message'];
						return false;
					}
					break;

				case 'exist':
					// up to top
					if(!$this->checkExist($this->value)) {
						$err_obj = $this->searchElementByName('error_message');
						if($err_obj) $err_obj->value = $config['error_message'];
						return false;
					}
					break;

				case 'not_exist':
					// up to top
					if(!$this->checkNotExist($this->value)) {
						$err_obj = $this->searchElementByName('error_message');
						if($err_obj) $err_obj->value = $config['error_message'];
						return false;
					}
					break;

				default:
					break;

				}
			}
			return true;
		}

		function getRootObject() {
			for($top = $this->parent ; $top->parent ; $top = $top->parent);
			return $top;
		}

		function getTarget($config) {
			if(!$this->target_obj) {
				if($config['target']) {
					$top = getRootObject();
					$this->target_obj = $top->getElementByName($config['target']);
				}
				else {
					$this->target_obj = $this->parent;
				}
			}
			return $this->target_obj;
		}

		function checkExist($value) {
			$target = $this->getTarget($config);
			if(is_array($target->data_set_value)) {
				foreach($target->data_set_value as $v) {
					if($v == $value) {
						return false;
					}
				}
			}
			return true;
		}

		function checkNotExist($value) {
			$target = $this->getTarget($config);
			if(is_array($target->data_set_value)) {
				foreach($target->data_set_value as $key => $v) {
					if($key == $value || $v == $value) {
						return true;
					}
				}
			}
			return false;
		}

		function checkTime($h, $m) {
			if($h >= 0 && $h < 24) {
				if($m >= 0 && $m < 60) {
					return true;
				}
			}
			return false;
		}

		function searchElementByName($name) {
			$current = $this;
			while($current) {
				if(isset($current->elements)) {
					foreach($current->elements as $element) {
						if($element->name == $name) {
							return $element;
						}
					}
				}
				$current = $current->parent;
			}
		}

		function bindDataSet($data_set) {
			$this->data_set_value = $data_set;
		}

		function checkKana($item) {
			$len = mb_strlen($item);
			$num = 0;
			switch(mb_internal_encoding()) {
			case 'EUC':
				for($i=0 ; $i < $len ; $i++) {
					$str = mb_substr($item, $i, 1);
					if(!preg_match('/\x20/', $str)){
						if(!preg_match('/\xA5[\xA1-\xF6]/', $str)){
							if(!preg_match('/\xA1[\xA1\xA6\xBC\xB3\xB4]/', $str)){
								return false;
							}
						}
					}
				}
				break;

			case 'SJIS':
				for($i=0 ; $i < $len ; $i++) {
					$str = mb_substr($item, $i, 1);
					if(!preg_match('/\x20/', $str)){
						if(!preg_match('/\x83[\x40-\x96]/', $str)){
							if(!preg_match('/\x81[\x40\x45\x5B]/', $str)){
								return false;
							}
						}
					}
				}
				break;

			case 'UTF-8':
				for($i=0 ; $i < $len ; $i++) {
					$str = mb_substr($item, $i, 1);
					if(!preg_match('/\x20/', $str)){
						if(!preg_match('/\xE3[\x82A1-\x82BF]/', $str)){
							if(!preg_match('/\xE3[\x8380-\x83B6]/', $str)){
								if(!preg_match('/\xE3[\x8080\x83BB\x83BC]/', $str)){
									return false;
								}
							}
						}
					}
				}
				break;
			}
			return true;
		}

		function checkAlphaNum($item) {
			$len = mb_strlen($item);
			$num = 0;
			
			for($i=0 ; $i < $len ; $i++) {
				$str = mb_substr($item, $i, 1);
				if(!preg_match('/[a-zA-Z0-9 .,\"\'\/^]/',$str)) { // space is valid
					return false;
				}
			}
			return true;
		}

		function checkAlpha($item) {
			$len = mb_strlen($item);
			$num = 0;
			
			for($i=0 ; $i < $len ; $i++) {
				$str = mb_substr($item, $i, 1);
				if(!preg_match('/[a-zA-Z\/^]/',$str)) {
					return false;
				}
			}
			return true;
		}

		function checkNum($item) {
			$len = mb_strlen($item);
			$num = 0;
			
			for($i=0 ; $i < $len ; $i++) {
				$str = mb_substr($item, $i, 1);
				if(!preg_match('/[0-9\/^]/',$str)) {
					return false;
				}
			}
			return true;
		}

		function checkEmailMX($item) {
			$exp = explode('@', $item);
			$domain = $exp[1];
			//check MX
			if(!checkdnsrr($domain, 'MX')) {
				if(!checkdnsrr($domain, 'A')) {
					if(!checkdnsrr($domain, 'CNAME')) {
						return false;
					}
				}
			}
			return true;
		}

		function checkPattern($item, $pattern) {
			if(!isset($item)) {
				return true;
			}

			return preg_match('/' . $pattern . '/', $item);
		}

		function checkCombinationRequire($target) {
			if(!$target) {
				return true;
			}
			$name = explode('/', $target);
			$i=0;
			foreach($name as $value) {
				$target = $this->getElementByName($value);
				if($target) {
					if($i != 0) {
						if(($target->value && !$last_value) || (!$target->value && $last_value)) {
							return false;
						}
					}
					$last_value = $target->value;
					$i++;
				}
			}
			return true;
		}

		function error($message) {
			echo '<pre>' . "\n";
			echo $message . "\n";
			debug_print_backtrace();
			echo '</pre>' . "\n";
		}
	}

	// -------------------------------------------------------------------------
	// class B_Text
	// 
	// -------------------------------------------------------------------------
	class B_Text extends B_Element {
		function getElementsHtml($mode=null) {
			if($this->shorten_text) {
				$this->value = $this->shortenText($this->value, $this->shorten_text);
			}
			$html = htmlspecialchars($this->value, ENT_QUOTES, B_CHARSET);
			return $html;
		}

		function _getHiddenHtml() {
			if(!isset($this->name)) return;

			return	'<input type="hidden" ' .
					'name="' . $this->name . '" ' .
					'id="' . $this->_gethtmlid() . '" ' .
					'value="' . htmlspecialchars($this->value, ENT_QUOTES, B_CHARSET) . '" ' .
					$this->attr .
					' />' . "\n";
		}
	}

	// -------------------------------------------------------------------------
	// class B_TextArea
	// 
	// -------------------------------------------------------------------------
	class B_TextArea extends B_Element {
		function getElementsHtml($mode=null) {
			if($mode == 'confirm') {
				return '<pre>' . htmlspecialchars($this->value, ENT_QUOTES, B_CHARSET) . '</pre>';
			}
			else {
				return 
					'<textarea ' .
					$this->attr . ' ' .
					'id="' . $this->_gethtmlid() . '" ' .
					'name="' . $this->name_prefix . $this->name . '" ' .
					$this->html .'>' . "\n" .
					htmlspecialchars($this->value, ENT_QUOTES, B_CHARSET) .
					'</textarea>' . "\n";
			}
		}
	}

	// -------------------------------------------------------------------------
	// class B_TextField
	// 
	// -------------------------------------------------------------------------
	class B_TextField extends B_Element {
		function getElementsHtml($mode=null) {
			if($this->specialchars == 'none') {
				$value = $this->value;
			}
			else {
				$value = htmlspecialchars($this->value, ENT_QUOTES, B_CHARSET);
			}

			return str_replace("\n", '<br />', $value);
		}

		function _getHiddenHtml() {
			if(!isset($this->name)) return;

			if($this->specialchars == 'none') {
				$value = $this->value;
			}
			else {
				$value = htmlspecialchars($this->value, ENT_QUOTES, B_CHARSET);
			}

			return	'<input type="hidden" ' .
					'name="' . $this->name_prefix . $this->name . '" ' .
					'id="' . $this->_gethtmlid() . '" ' .
					'value="' . $value . '" ' .
					$this->attr .
					' />' . "\n";
		}
	}

	// -------------------------------------------------------------------------
	// class B_Guidance
	// 
	// -------------------------------------------------------------------------
	class B_Guidance extends B_Element {
		function getElementsHtml($mode=null) {
			if($mode != 'confirm') {
				return $this->value;
			}
		}
	}

	// -------------------------------------------------------------------------
	// class B_InputText
	// 
	// -------------------------------------------------------------------------
	class B_InputText extends B_Element {
		function getElementsHtml($mode=null) {
			if($mode == 'confirm') {
				return htmlspecialchars($this->value, ENT_QUOTES, B_CHARSET);
			}
			if($this->label) {
				$html = '<label>' . $this->label . '</label>';
			}
			$disabled = '';
			if($this->disabled && $this->disabled == 'disabled') {
				$disabled = ' disabled="disabled" ';
			}

			$value = htmlspecialchars($this->value, ENT_QUOTES, B_CHARSET);
			if($this->zero_suppress && is_numeric($this->value) && $this->value == 0) {
				$value = '';
			}
			if($this->type) {
				$type = $this->type;
			}
			else {
				$type = 'text';
			}
			$html.= 
				'<input ' .
				$this->attr . $disabled . ' ' .
				'type="' . $type . '" ' .
				'name="' . $this->name_prefix . $this->name . '" ' .
				'id="' . $this->_gethtmlid() . '" ' .
				'value="' . $value . '"' .
				' />';
			return $html;
		}
	}

	// -------------------------------------------------------------------------
	// class B_InputImage
	// 
	// -------------------------------------------------------------------------
	class B_InputImage extends B_Element {
		function getElementsHtml($mode=null) {
			if($this->label) {
				$html = '<label>' . $this->label . '</label>';
			}
			$disabled = '';
			if($this->disabled && $this->disabled == 'disabled') {
				$disabled = ' disabled="disabled" ';
			}

			$value = htmlspecialchars($this->value, ENT_QUOTES, B_CHARSET);
			if($this->zero_suppress && $this->value == 0) {
				$value = '';
			}
			$html.= 
				'<input ' .
				$this->attr . $disabled . ' ' .
				'type="image" ' .
				'src="' . $this->src . '" ' .
				'name="' . $this->name_prefix . $this->name . '" ' .
				'id="' . $this->_gethtmlid() . '" ' .
				'value="' . $value . '"' .
				' />';

			return $html;
		}
	}

	// -------------------------------------------------------------------------
	// class B_InputFile
	// 
	// -------------------------------------------------------------------------
	class B_InputFile extends B_Element {
		function getElementsHtml($mode=null) {
			if($mode == 'confirm') {
				$confirm = 'data-confirm="true"';
			}
			$html.= 
				'<input ' .
				'type="file" ' .
				'name="' . $this->data_name . '" ' .
				'data-action="' . $this->data_action . '" ' .
				'data-rel="' . $this->name_prefix . $this->name . '" ' .
				'data-value="' . $this->value . '" ' .
				$confirm . ' ' .
				$this->attr . ' ' .
				' />';

			return $html;
		}
	}

	// -------------------------------------------------------------------------
	// class B_Button
	// 
	// -------------------------------------------------------------------------
	class B_Button extends B_Element {
		function getElementsHtml($mode=null) {
			if($mode == 'confirm') {
				return;
			}

			if($this->disabled == 'disabled') {
				$disabled = ' disabled="disabled" ';
			}
			return 
				'<input ' .
				'type="button" ' .
				'name="' . $this->name_prefix . $this->name . '" ' .
				'id="' . $this->_gethtmlid() . '" ' .
				'value="' . $this->value . '" ' .
				$this->attr .
				$disabled .
				' />';
		}
	}

	// -------------------------------------------------------------------------
	// class B_Password
	// 
	// -------------------------------------------------------------------------
	class B_Password extends B_Element {
		function getElementsHtml($mode=null) {
			if($mode == 'confirm') {
				if($this->value) {
					return $this->confirm_message;
				}
				else {
					return;
				}
			}

			$html = 
				'<input ' .
				$this->attr . ' ' .
				'type="password" ' .
				'name="' . $this->name_prefix . $this->name . '" ' .
				'id="' . $this->_gethtmlid() . '" ' .
				'value="' . $this->value . '" ' .
				' />' . "\n";

			return $html;
		}
	}

	// -------------------------------------------------------------------------
	// class B_Submit
	// 
	// -------------------------------------------------------------------------
	class B_Submit extends B_Element {
		function getElementsHtml($mode=null) {
			return 
				'<input ' .
				$this->attr . ' ' .
				'type="submit" ' .
				'name="' . $this->name_prefix . $this->name . '" ' .
				'id="' . $this->_gethtmlid() . '" ' .
				'value="' . $this->value . '" ' .
				' />' . "\n";
		}
	}

	// -------------------------------------------------------------------------
	// class B_Reset
	// 
	// -------------------------------------------------------------------------
	class B_Reset extends B_Element {
		function getElementsHtml($mode=null) {
			return 
				'<input ' .
				$this->attr . ' ' .
				'type="reset" ' .
				'name="' . $this->name_prefix . $this->name . '" ' .
				'id="' . $this->_gethtmlid() . '" ' .
				'value="' . $this->value . '" ' .
				' />' . "\n";
		}
	}

	// -------------------------------------------------------------------------
	// class B_Hidden
	// 
	// -------------------------------------------------------------------------
	class B_Hidden extends B_Element {
		function getElementsHtml($mode=null) {
			if(is_array($this->value)) {
				foreach($this->value as $value2) {
					$html.=
							'<input ' .
							'type="hidden" ' .
							'name="' . $this->name_prefix . $this->name . '[]" ' .
							'id="' . $this->_gethtmlid() . '[]" ' .
							'value="' . htmlspecialchars($value2, ENT_QUOTES, B_CHARSET) . '" ' .
							$this->attr .
							' />' . "\n";
				}
			}
			else {
				$name = $this->name;
				if($this->mode == 'array') {
					$name.='[]';
				}
				$html =
					'<input ' .
					'type="hidden" ' .
					'name="' . $this->name_prefix . $name . '" ' .
					'id="' . $this->_gethtmlid() . '" ' .
					'value="' . htmlspecialchars($this->value, ENT_QUOTES, B_CHARSET) . '" ' .
					$this->attr .
					' />' . "\n";
			}

			return $html;
		}

		function getConfirmHtml() {
			return $this->getElementsHtml();
		}

		function getHiddenHtml() {
			return;
		}
	}

	// -------------------------------------------------------------------------
	// class B_SelectBox
	// 
	// -------------------------------------------------------------------------
	class B_SelectBox extends B_SelectedText {
		function getElementsHtml($mode=null) {
			if($mode == 'confirm') {
				$html = parent::getElementsHtml($mode);
			}
			else {
				$html = 
					'<select ' .
					$this->attr . ' ' .
					'name="' . $this->name_prefix . $this->name . '" ' .
					'id="' . $this->_gethtmlid() . '">' . "\n";

				if(isset($this->data_set_value) && is_array($this->data_set_value)) {
					foreach($this->data_set_value as $key => $value) {
						$html.=
							'  <option value="' .
							$key . '" ';
						if(isset($this->value)) {
							if($key == $this->value) {
								$html.= 'selected="selected"';
							}
						}
						$html.= '>' . $value . '</option>' ."\n";
					}
				}
				$html.= '</select>' . "\n";

				if($this->special_text) {
					foreach($this->elements as $obj) {
						if($obj->index == $key) {
							$html.= $obj->getElementsHtmlSpecial();
						}
					}
				}
			}

			return $html;
		}

		function _getHiddenHtml() {
			return;
		}
	}

	// -------------------------------------------------------------------------
	// class B_SelectedText
	// 
	// -------------------------------------------------------------------------
	class B_SelectedText extends B_Element {
		function getElementsHtml($mode=null) {
			if(isset($this->data_set_value)) {
				if(is_array($this->value)) {
					foreach($this->value as $value) {
						if($html) $html.= "&nbsp;";
						$html.= $this->data_set_value[$value];
						if($this->special_text) {
							foreach($this->elements as $obj) {
								if($obj->index == $value) {
									if($html) $html.= "&nbsp;";
									$html.= $obj->label . $obj->getConfirmHtmlSpecial();
								}
							}
						}
					}
				}
				else {
					$html = $this->data_set_value[$this->value];
					if($this->special_text) {
						foreach($this->elements as $obj) {
							if($obj->index == $this->value) {
								$html.= $obj->label . $obj->getConfirmHtmlSpecial();
							}
						}
					}
				}
			}
			else {
				$html = $this->value;
			}

			return $html;
		}

		function _getHiddenHtml() {
			if(!isset($this->name)) return;

			return	'<input type="hidden" ' .
					'name="' . $this->name_prefix . $this->name . '" ' .
					'id="' . $this->_gethtmlid() . '" ' .
					'value="' . htmlspecialchars($this->value, ENT_QUOTES, B_CHARSET) . '" ' .
					$this->attr .
					' />' . "\n";
		}
	}

	// -------------------------------------------------------------------------
	// class B_SpecialInput
	// 
	// -------------------------------------------------------------------------
	class B_SpecialInput extends B_Element {

		function getElementsHtml($mode=null) {
			if($mode == 'confirm') {
				if($this->value && $this->parent->checked) {
					$html = $this->label . htmlspecialchars($this->value, ENT_QUOTES, B_CHARSET);
				}
			}
			else {
				$id = $this->_gethtmlid();
				$html.= 
					'<input ' .	$this->attr . ' ' .
					'type="text" ' .
					'name="' . $this->name_prefix . $this->name . '" ' .
					'id="' . $id . '" ' .
					'value="' . htmlspecialchars($this->value, ENT_QUOTES, B_CHARSET) . '" ' .
					' />' . "\n";
				if($this->label) {
					$html = '<label for="' . $id . '">' . $this->label . '</label>'. $html;
				}
			}
			return $html;
		}

		function _getValue(&$param) {
			$param[$this->name] = $this->value;
		}
	}

	// -------------------------------------------------------------------------
	// class B_ErrMsg
	// 
	// -------------------------------------------------------------------------
	class B_ErrMsg extends B_Element {
		function getStartHtml($mode=null) {
			if(!$this->parent->validation) {
				return $this->start_html;
			}
		}

		function getEndHtml($mode=null) {
			if(!$this->parent->validation) {
				return $this->end_html;
			}
		}

		function getElementsHtml($mode=null) {
			if(!$this->parent->validation) {
				return $this->value;
			}
		}
	}

	// -------------------------------------------------------------------------
	// class B_CheckboxContainer
	// 
	// -------------------------------------------------------------------------
	class B_CheckboxContainer extends B_Element {
		function __construct($config, $user_auth=NULL, $config_filter=NULL, &$parent=NULL, $level=0) {
			parent::__construct($config, $user_auth, $config_filter, $parent, $level);
			$this->createInstance();
		}

		function createInstance() {
			if(isset($this->data_set_value)) {
				$i=0;
				foreach($this->data_set_value as $key => $label) {
					unset($config);
					if(substr($key, 0, 2) == 'LF') {
						$config['confirm_mode'] = 'none';
						$config['value'] = '<br />' . "\n";
						$class = 'B_Element';
					}
					else {
						// create item instance
						$config = $this->item;
						$config['name'] = $this->name;
						$config['id'] = $this->name . '_' . $key;
						$config['value'] = $key;
						$config['label'] = htmlspecialchars($label, ENT_QUOTES, B_CHARSET);
						$class = 'B_Checkbox';
					}

					$item = new $class($config);
					$item->parent = $this;
					$this->addElement($item);

					// special text
					if($this->special_text && is_array($this->special_text)) {
						foreach($this->special_text as $value) {
							if($value['index'] == $key) {
								$special_input = new B_SpecialInput($value);
								$special_input->parent = $item;
								$item->addElement($special_input);
							}
						}
					}
				}
			}
		}

		function reCreateInstance() {
			unset($this->elements);
			$this->createInstance();
		}

		function getElementsHtml($mode=null) {
			return;
		}

		function _checkAlt($row) {
			if(isset($row[$this->name])) {
				if(is_array($this->value)) {
					foreach($this->value as $val) {
						if(isset($v)) $v.= '/';
						$v.= $val;
					}
				}
				else {
					$v = $this->value;
				}

				if($v != $row[$this->name]) {
					return false;
				}
			}
			return true;
		}

		function clear() {
			if(isset($this->elements)) {
				foreach($this->elements as $element) {
					$element->clear();
				}
			}
			unset($this->value);
		}
	}

	// -------------------------------------------------------------------------
	// class B_Checkbox
	// 
	// -------------------------------------------------------------------------
	class B_Checkbox extends B_Element {
		function getStartHtml($mode=null) {
			if($mode == 'confirm') {
				if($this->checked) {
					return $this->confirm_start_html ? $this->confirm_start_html : $this->start_html;
				}
			}
			else {
				return $this->start_html;
			}
		}

		function getEndHtml($mode=null) {
			if($mode == 'confirm') {
				if($this->checked) {
					return $this->confirm_end_html ? $this->confirm_end_html : $this->end_html;
				}
			}
			else {
				return $this->end_html;
			}
		}

		function getElementsHtml($mode=null) {
			if($mode == 'confirm') {
				if($this->checked) {
					$html = $this->label;
				}
			}
			else {
				$name = $this->name_prefix . $this->name . $this->name_index;
				$id = $this->_gethtmlid();
				if(!$this->fixed && $this->value) {
					$name.= '[' . $this->value . ']';
				}

				$html.= '<input type="checkbox" name="' . $name . '" id="' . $id . '" value="' . $this->value . '"';
				$html.= ' ' . $this->attr;

				if($this->disabled) {
					$html.= ' disabled="disabled"';
				}
				if($this->checked) {
					$html.= ' checked="checked"';
				}
				$html.= ' />';
				if(isset($this->label)) {
					$html.= '<label for="' . $id . '">' . $this->label . '</label>';
				}
			}
			return $html;
		}

		function _setValue($value) {
			if(isset($value[$this->name_index])) {
				$this->name_index = $value[$this->name_index];
			}
			$name = $this->name_prefix . $this->name . $this->name_index;

			if(isset($value[$name])) {
				if(is_array($value[$name])) {
					if(isset($value[$name][$this->value])) {
						$this->checked = true;
					}
				}
				else {
					if(($this->sequence = array_search($this->value, explode('/', $value[$name]))) !== FALSE) {
						$this->checked = true;
						$this->sequence++;
					}
				}
			}

			if(isset($value[$this->index])) {
				$this->value = $value[$this->index];
			}
			if(!$this->id) {
				$this->id = $name;
			}
		}

		function _getValue(&$param) {
			if($this->checked) {
				$param[$this->name_prefix . $this->name . $this->name_index] = $this->value;
			}
			else {
				$param[$this->name_prefix . $this->name . $this->name_index] = '';
			}
		}

		function clear() {
			unset($this->checked);
		}

		function _checkAlt($row) {
			$name = $this->name_prefix . $this->name . $this->name_index;
			if(is_array($row[$name])) {
				if(!$this->checked && isset($row[$name][$this->value])) return false;
				if($this->checked && !isset($row[$name][$this->value])) return false;
			}
			else {
				if(!$this->checked && array_search($this->value, explode('/', $row[$name])) !== FALSE) return false;
				if($this->checked && array_search($this->value, explode('/', $row[$name])) === FALSE) return false;
			}
			return true;
		}
	}

	// -------------------------------------------------------------------------
	// class B_RadioContainer
	// 
	// -------------------------------------------------------------------------
	class B_RadioContainer extends B_Element {
		function __construct($config, $user_auth=NULL, $config_filter=NULL, &$parent=NULL, $level=0) {
			parent::__construct($config, $user_auth, $config_filter, $parent, $level);
			$this->createInstance();
		}

		function createInstance() {
			if(isset($this->data_set_value)) {
				foreach($this->data_set_value as $key => $label) {
					unset($config);
					if(substr($key, 0, 2) == 'LF') {
						$config['confirm_mode'] = 'none';
						$config['value'] = '<br />' . "\n";
						$class = 'B_Element';
					}
					else {
						// create item instance
						$config = $this->item;
						$config['name'] = $this->name;
						$config['id'] = $this->name . '_' . $key;
						$config['value'] = $key;
						$config['label'] = htmlspecialchars($label, ENT_QUOTES, B_CHARSET);
						if($this->index) {
							$config['index'] = $this->index;
						}
						$class = 'B_Radio';

						// default value
						if($this->value == $config['value']) {
							$config['checked'] = true;
						}
					}

					$item = new $class($config);
					$item->parent = $this;
					$this->addElement($item);

					// special text
					if($this->special_text && is_array($this->special_text)) {
						foreach($this->special_text as $value) {
							if($value['index'] == $key) {
								$special_input = new B_SpecialInput($value);
								$special_input->parent = $item;
								$item->addElement($special_input);
							}
						}
					}
				}
			}
		}

		function reCreateInstance() {
			unset($this->elements);
			$this->createInstance();
		}

		function getElementsHtml($mode=null) {
			return;
		}

		function clear() {
			if(isset($this->elements)) {
				foreach($this->elements as $element) {
					$element->clear();
				}
			}
			unset($this->value);
		}
	}

	// -------------------------------------------------------------------------
	// class B_Radio
	// 
	// -------------------------------------------------------------------------
	class B_Radio extends B_Element {
		function getStartHtml($mode=null) {
			if($mode == 'confirm') {
				if($this->checked) {
					return $this->confirm_start_html ? $this->confirm_start_html : $this->start_html;
				}
			}
			else {
				return $this->start_html;
			}
		}

		function getEndHtml($mode=null) {
			if($mode == 'confirm') {
				if($this->checked) {
					return $this->confirm_end_html ? $this->confirm_end_html : $this->end_html;
				}
			}
			else {
				return $this->end_html;
			}
		}

		function getElementsHtml($mode=null) {
			if($mode == 'confirm') {
				if($this->checked) {
					$html = $this->label;
				}
			}
			else {
				$name = $this->name_prefix . $this->name . $this->name_index;
				$id = $this->_gethtmlid();
				if($this->index) {
					$name.= '[' . $this->index . ']';
					$id.= '[' . $this->index . ']';
				}
				$html.= '<input type="radio" name="' . $name . '" id="' . $id . '" value="' . $this->value . '"';
				if($this->attr) {
					$html.= ' ' . $this->attr;
				}
				if($this->disabled) {
					$html.= ' disabled';
				}
				if($this->checked) {
					$html.= ' checked="checked"';
				}
				$html.= ' />';
				if(isset($this->label)) {
					$html.= '<label for="' . $id . '">' . $this->label . '</label>';
				}
			}
			return $html;
		}

		function _setValue($value) {
			if(isset($value[$this->index])) {
				$this->index = $value[$this->index];
			}
			if(isset($value[$this->name_index])) {
				$this->name_index = $value[$this->name_index];
			}
			if(isset($value[$this->value_index])) {
				$this->value = $value[$this->value_index];
			}
			if(substr($this->name, strlen($this->name)-2, 2) == '[]') {
				$name = substr($this->name, 0, strlen($this->name)-2);
			}
			else {
				$name = $this->name;
			}

			$name = $this->name_prefix . $name . $this->name_index;
			if(isset($value[$name])) {
				if($value[$name] == $this->value) {
					$this->checked = true;
				}
				else {
					$this->checked = false;
				}
			}
			if(!$this->id) {
				$this->id = $name . $this->value;
			}
		}

		function _getValue(&$param) {
			if($this->checked) {
				$param[$this->name_prefix . $this->name . $this->name_index] = $this->value;
			}
		}

		function clear() {
			unset($this->checked);
		}

		function _checkAlt($row) {
			return true;
		}
	}

	// -------------------------------------------------------------------------
	// class B_Label
	// 
	// -------------------------------------------------------------------------
	class B_Label extends B_Element {
		function getElementsHtml($mode=null) {
			return '<label for="' . $this->parent->_getHtmlid() . '">' . $this->value . '</label>';
		}
	}

	// -------------------------------------------------------------------------
	// class B_Link
	// 
	// -------------------------------------------------------------------------
	class B_Link extends B_Element {
		function getElementsHtml($mode=null) {
			if($this->specialchars == 'none') {
				return $this->value;
			}
			else {
				return htmlspecialchars($this->value, ENT_QUOTES, B_CHARSET);
			}
		}

		function getStartHtml($mode=null) {
			if($this->link && $this->link == 'none') {
				return $this->start_html;
			}
			if(!$this->param && $this->config_org['fixedparam']) {
				foreach($this->config_org['fixedparam'] as $key2 => $value2) {
					$this->setParamProperty($key2, $value2);
				}
			}
			$this->element_start_html =	'<a href="' . $this->link . $this->permalink . $this->param . '"';
			if($this->id) {
				$this->element_start_html.= ' id="' . $this->_gethtmlid() . '"';
			}
			if($this->target) {
				$this->element_start_html.= ' target="' . $this->target . '"';
			}
			if($this->title) {
				$this->element_start_html.= ' title="' . $this->title . '"';
			}
			if($this->attr) $this->attr = ' ' . $this->attr;

			return $this->start_html . $this->element_start_html . $this->attr . $this->event . '>';
		}

		function getEndHtml($mode=null) {
			if($this->element_start_html) {
				$this->element_end_html = '</a>';
			}

			return $this->element_end_html . $this->end_html;
		}

		function setValue($value) {
			if(!$value) return;

			if(isset($this->elements)) {
				foreach($this->elements as $element) {
					$element->setValue($value);
				}
			}

			// set value
			if(isset($value[$this->name])) {
				$this->value = $this->_prepareInput($value[$this->name]);
				$this->value_exist = true;
			}
			else {
				$this->value_exist = false;
			}

			// set param
			if($this->param_exist == true) {
				return;
			}

			if(is_array($this->config_org['permalink'])) {
				foreach($this->config_org['permalink'] as $value2) {
					$this->setPermalink($value[$value2]);
				}
			}
			if(is_array($this->config_org['param'])) {
				foreach($this->config_org['param'] as $key2 => $value2) {
					$this->setParamProperty($key2, $value[$value2]);
				}
			}
			if(is_array($this->config_org['data_param'])) {
				foreach($this->config_org['data_param'] as $key2 => $value2) {
					$this->setParamProperty($key2, $value[$value2]);
				}
			}
			if(is_array($this->config_org['fixedparam'])) {
				foreach($this->config_org['fixedparam'] as $key2 => $value2) {
					$this->setParamProperty($key2, $value2);
				}
			}

			if($this->config_org['anchor']) {
				$this->param = '#' . $value[$this->config_org['anchor']];
			}

			if($this->config_org['event']) {
				foreach($this->config_org['event'] as $key2 => $value2) {
					$html = ' ' . $key2 . '="return ' . $value2['function'] . '(';
					$param = '';

					if($value2['param']) {
						if(is_array($value2['param'])) {
							foreach($value2['param'] as $key2 => $value3) {
								if($value3 == 'fixed') {
									$this->setParam($param, $key2);
								}
								else {
									$this->setParam($param, $value[$key2]);
								}
							}
						}
						else {
							$this->setParam($param, $value2['param']);
						}
					}
					$html.= $param . '); ';

					if($value2['after_proc']) {
						$html.= $value2['after_proc'];
					}
					$html.= '"';
				}
				$this->event = $html;
			}
			$this->param_exist = true;
		}

		function setParamProperty($key, $value) {
			if(($this->param && substr($this->param, 0, 1) != '/') || strstr($this->link, '?')) {
				$this->param.= '&amp;';
			}
			else{
				$this->param.= '?';
			}
			$this->param.= $key . '=' . urlencode($value);
		}

		function setParam(&$param, $value) {
			if($param) {
				$param.= ',';
			}
			$param.= "'" . $value . "'";
		}

		function setPermalink($value) {
			$this->permalink = '/' . $value;
		}
	}

	// -------------------------------------------------------------------------
	// class B_PlaceHolder
	// 
	// -------------------------------------------------------------------------
	class B_PlaceHolder extends B_Element {
		function getElementsHtml($mode=null) {
			return;
		}

		function getValue(&$param) {
			return;
		}
	}

	// -------------------------------------------------------------------------
	// class B_Data
	// 
	// -------------------------------------------------------------------------
	class B_Data extends B_Element {
		function getElementsHtml($mode=null) {
			return;
		}
	}

	// -------------------------------------------------------------------------
	// class B_DateTime
	// 
	// -------------------------------------------------------------------------
	class B_DateTime extends B_Element {
		function getElementsHtml($mode=null) {
			if($this->value) {
				return $this->myDate($this->format, $this->value);
			}
		}

		function myDate($format, $value) {
			// convert to UTF-8
			$encoding = mb_internal_encoding();
			mb_internal_encoding('UTF-8');
			$formatUtf8 = mb_convert_encoding($format,'UTF-8', $encoding);
			$resultUtf8 = date($formatUtf8, $value);
			$result = mb_convert_encoding($resultUtf8, $encoding, 'UTF-8');
			mb_internal_encoding($encoding);
			return $result;
		}

		function _getHiddenHtml() {
			if(!isset($this->name)) return;

			return	'<input type="hidden" ' .
					'name="' . $this->name_prefix . $this->name . '" ' .
					'id="' . $this->_gethtmlid() . '" ' .
					'value="' . htmlspecialchars($this->value, ENT_QUOTES, B_CHARSET) . '" ' .
					$this->attr .
					' />' . "\n";
		}
	}

	// -------------------------------------------------------------------------
	// class B_Row
	// 
	// -------------------------------------------------------------------------
	class B_Row extends B_Element {
		function getElementsHtml($mode=null) {
			return;
		}
	}

	// -------------------------------------------------------------------------
	// class B_Cell
	// 
	// -------------------------------------------------------------------------
	class B_Cell extends B_Element {
		function setValue($value) {
			if(isset($this->elements)) {
				foreach($this->elements as $element) {
					$element->setValue($value);
				}
			}
			// set col_span
			if($this->config_org['col_span'] && $value[$this->config_org['col_span']] > 1) {
				$this->colspan = ' colspan="' . $value[$this->config_org['col_span']] . '"';
			}
			if($this->config_org['row_span'] && $value[$this->config_org['row_span']] > 1) {
				$this->rowspan = ' rowspan="' . $value[$this->config_org['row_span']] . '"';
			}

			$this->start_html = 
				'<' . $this->tag . ' ' .
				$this->attr .
				$this->rowspan .
				$this->colspan .
				'>';
		}

		function getStartHtml($mode=null) {
			return
				'<' . $this->tag . ' ' .
				$this->attr .
				$this->rowspan .
				$this->colspan .
				'>';
		}

		function getEndHtml($mode=null) {
			return '</' . $this->tag . '>';
		}

		function getElementsHtml($mode=null) {
			return;
		}
	}

	// -------------------------------------------------------------------------
	// class B_Tag
	// 
	// -------------------------------------------------------------------------
	class B_Tag extends B_Element {
		function setValue($value) {
			if(isset($this->elements)) {
				foreach($this->elements as $element) {
					$element->setValue($value);
				}
			}

			if(isset($value[$this->name_prefix . $this->name])) {
				$this->value = $this->_prepareInput($value[$this->name_prefix . $this->name]);
				$this->value_exist = true;
			}
			else {
				$this->value_exist = false;
			}

		}

		function getStartHtml($mode=null) {
			if($this->attr) {
				$attr = ' ' . $this->attr;
			}
			if($this->name) {
				$name = ' name="' . $this->name_prefix . $this->name . '"';
			}
			if($this->id) {
				$id = ' id="' . $this->id . '"';
			}
			return '<' . $this->tag . $attr . $name . $id . '>';
		}

		function getEndHtml($mode=null) {
			return '</' . $this->tag . '>';
		}

		function getElementsHtml($mode=null) {
			return;
		}
	}

	// -------------------------------------------------------------------------
	// class B_Iframe
	// 
	// -------------------------------------------------------------------------
	class B_Iframe extends B_Element {
		function getStartHtml($mode=null) {
			if($this->name) {
				$name = ' name="' . $this->name_prefix . $this->name . '"';
				$id = ' id="' . $this->_gethtmlid() . '"';
			}

			return
				'<iframe' . $id . $name .
				' src="' . $this->src . '"' .
				$this->attr .
				'>';
		}

		function getEndHtml($mode=null) {
			return '</iframe>';
		}

		function getElementsHtml($mode=null) {
			return;
		}
	}

	// -------------------------------------------------------------------------
	// class B_Image
	// 
	// -------------------------------------------------------------------------
	class B_Image extends B_Element {
		function getElementsHtml($mode=null) {
			if($this->path) {
				$src = __getPath($this->path, $this->value);
			}
			else {
				$src = $this->value;
			}

			return
				'<img ' .
				'src="' . $src . '" ' .
				'alt="' . $this->alt . '" ' .
				$this->attr .
				' />' . "\n";
		}
	}
