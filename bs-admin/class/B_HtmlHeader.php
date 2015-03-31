<?php
/*
 * B-frame : php web application framework
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	// -------------------------------------------------------------------------
	// class B_HtmlHeader
	// 
	// -------------------------------------------------------------------------
	class B_HtmlHeader {
		function __construct($config) {
			if(!is_array($config)) {
				$this->error('CONFIG ERROR');
				return;
			}
			foreach($config as $key => $value) {
				$this->$key = $value;
			}
		}

		function appendProperty($key, $value) {
			$property = &$this->$key;
			if(is_array($property)) {
				$property[] = $value;
			}
			else {
				$property = $value;
			}
		}

		function appendMeta($name, $content) {
			$str = '<meta name="' . $name . '" content="' . $content . '" />';
			$this->appendProperty('meta', $str);
		}

		function removeProperty($key) {
			unset($this->$key);
		}

		function setCharset($charset) {
			$this->charset = $charset;
		}

		function setTitle($title) {
			$this->title = $title;
		}

		function getHtml() {
			switch($this->charset) {
			case 'SJIS':
				$this->appendProperty('declaration', '<?xml version="1.0" encoding="Shift-JIS"?>');
				$this->appendProperty('meta', '<meta http-equiv="Content-Type" content="text/html; charset=SJIS" />');
				break;

			case 'EUC':
				$this->appendProperty('declaration', '<?xml version="1.0" encoding="EUC-JP"?>');
				$this->appendProperty('meta', '<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP" />');
				break;

			default:
				$this->appendProperty('meta', '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />');
				break;
			}

			$html = '';

			if(!(preg_match('/Windows/', $_SERVER['HTTP_USER_AGENT']) && preg_match('/MSIE 6/', $_SERVER['HTTP_USER_AGENT']))) {
				$html.= $this->_outValue($this->declaration);
			}

			// set console log
			if(console::$buffer) {
				$this->appendProperty('script', console::$buffer);
			}

			$html.= $this->_outValue($this->doc_type);
			$html.= $this->_outValue($this->html);
			$html.= '<head>' . "\n";
			$html.= $this->_outValue($this->meta);
			$html.= $this->_outValue($this->misc);
			$html.= $this->_outValue($this->base);
			$html.= $this->_outValue($this->css);
			$html.= $this->_outValue($this->script);
			$html.= '<title>' . $this->title . '</title>';

			$html.= "\n" . '</head>' . "\n";

			return $html;
		}

		function _outValue($param) {
			if(!$param) return;

			if(is_array($param)) {
				foreach($param as $value) {
					$html.= $value . "\n";
				}
			}
			else {
				$html = $param . "\n";
			}
			return $html;
		}

		function error($message) {
			echo '<pre>' . "\n";
			echo $message . "\n";
			debug_print_backtrace();
			echo '</pre>' . "\n";
		}
	}
