<?php
/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
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
			$str = '<meta name="' . $name . '" content="' . $content . '">';
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

		function setViewPort($content) {
			$this->viewport = $content;
		}

		function getHtml() {
			// set console log
			if(console::$buffer) {
				$this->appendProperty('script', console::$buffer);
			}

			$html = '';
			$html.= $this->_outValue($this->doc_type);
			$html.= $this->_outValue($this->html);
			$html.= '<head>' . "\n";
			$html.= $this->_outValue($this->viewport);
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
