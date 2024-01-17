<?php
/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	// -------------------------------------------------------------------------
	// class B_Module
	// 
	// -------------------------------------------------------------------------
	class B_Module {
		public $archive;
		public $log;
		public $db;
		public $util;
		public $file_path;
		public $module;
		public $page;
		public $session;
		public $global_session;
		public $request;
		public $post;
		public $html_header;
		public $charset;
		public $view;
		public $entry_if;
		public $name;
		public $corp;
		public $email;
		public $entry_status;
		public $entry_status_online;
		public $entry_media;
		public $page_no;
		public $row_per_page;
		public $sort_key;
		public $order;
		public $mode;
		public $entry_id;
		public $judge_status;
		public $s_session;
		public $contents_id;
		public $scale;

		function __construct($file_path) {
			$this->archive = new B_Log(B_ARCHIVE_LOG_FILE);
			$this->log = new B_Log(B_LOG_FILE);

			// Connect to DB
			$this->db = new B_DBaccess($this->archive);
			$ret = $this->db->connect(B_DB_SRV, B_DB_USR, B_DB_PWD, B_DB_CHARSET, B_DB_NME);

			$this->util = new B_Util();

			// set properties
			$this->file_path = $file_path;
			$this->module = $this->getModuleName();
			$this->page = $this->getPageName();
			if(defined('TERMINAL_ID') && isset($_SESSION[TERMINAL_ID]) && is_array($_SESSION[TERMINAL_ID])) {
				$this->session = &$_SESSION[TERMINAL_ID][$this->module];
				$this->global_session = &$_SESSION[TERMINAL_ID];
			}
			else {
				$this->session = &$_SESSION[$this->module];
				$this->global_session = &$_SESSION;
			}
			$this->request = $_REQUEST;
			$this->post = $_POST;

			$this->setView('view');

			$this->setCharset(B_CHARSET);
			mb_internal_encoding(B_CHARSET);
		}

		function getModuleName() {
			return basename(dirname($this->file_path));
		}

		function getPageName() {
			$basename = basename($this->file_path);
			$i = strrpos($basename, '.');
			if($i) {
				$basename = substr($basename, 0, $i);
			}
			return $basename;
		}

		function createHtmlHeader($config) {
			$this->html_header = new B_HtmlHeader($config);
			$this->html_header->setCharset($this->charset);
		}

		function setTitle($title) {
			if($this->html_header) {
				$this->html_header->setTitle($title);
			}
		}

		function setCharset($charset) {
			$this->charset = $charset;
		}

		function setView($func) {
			$this->view = $func;
		}

		function getView() {
			return $this->view;
		}

		function _setRequest($property) {
			$this->session[$property] = $this->request[$property];
		}

		function _setProperty($property, $default) {
			// set default
			$this->$property = $default;

			// over write when data exists in session variables
			if(isset($this->session[$property])) {
				$this->$property = $this->session[$property];
			}

			// save variables in session variables
			$this->session[$property] = $this->$property;
		}

		function sendHttpHeader() {
			header('Cache-Control: no-cache, no-store, must-revalidate');
			header('Content-Type: text/html; charset=UTF-8');
		}

		function showHtmlHeader() {
			$this->html_header->appendMeta('terminal_id', TERMINAL_ID);
			$this->html_header->appendMeta('source_module', $this->module);
			$this->html_header->appendMeta('source_page', $this->page);

			echo $this->html_header->getHtml();
		}

		function initScript() {
			$this->setCharset($this->charset);
			$this->sendHttpHeader();

			$property = get_object_vars($this);

			$data = $this->_initScript($property);
			if($data) {
				header('Content-Type: application/x-javascript charset=utf-8');
				echo $data;
			}
			exit;
		}

		function _initScript($property) {
			foreach($property as $key => $value) {
				if(!is_object($value)) continue;
				if(!$class = get_class($value)) continue;

				switch($class) {
				case 'B_Element':
					$obj = $value->getElementById($this->request['id']);
					if($obj) {
						return json_encode($obj->script[$this->request['class']]);
					}
					break;

				case 'B_DataGrid':
					if($value->id == $this->request['id']) {
						return json_encode($value->script[$this->request['class']]);
					}

					$obj = $value->getElementByNameFromRowInstance($this->request['id']);
					if($obj) {
						return json_encode($obj->script[$this->request['class']]);
					}
					break;

				case 'B_Node':
				case 'B_VNode':
				case 'B_FileNode':
					if($value->id == $this->request['id']) {
						return json_encode($value->script[$this->request['class']]);
					}
					break;

				default:
					$vars = get_object_vars($value);
					if($data = $this->_initScript($vars)) {
						return $data;
					}
					break;
				}
			}
		}
	}
