<?php
/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	// -------------------------------------------------------------------------
	// class B_Log
	// 
	// -------------------------------------------------------------------------
	class B_Log {
		function __construct($file_name, $option=null) {
			if($option == 'one-file') {
				$path = substr($file_name, 0, strrpos($file_name, '/')+1);
				$file = substr($file_name, strrpos($file_name, '/')+1, strlen($file_name));
				$this->file_name = $path . $file;
			}
			else {
				$prefix = date('Ymd');
				$path = substr($file_name, 0, strrpos($file_name, '/')+1);
				$file = substr($file_name, strrpos($file_name, '/')+1, strlen($file_name));
				$this->file_name = $path . $prefix . '_' . $file;
			}

			$this->fp = fopen($this->file_name, 'a+');
		}

		function write() {
			if($this->fp) {
				for($i=0; $i<func_num_args(); $i++) {
					$param = func_get_arg($i);
					if(is_array($param)) {
						$param2 = $this->shortenText($param);
						$param = print_r($param2, true);
					}
					if($message) {
						$message.= ' ';
					}
					$message.= $param;
				}
				fwrite($this->fp, date('Y/m/d H:i:s') . ' ' . $message . "\n");
			}
		}

		function shortenText($param) {
			if(is_array($param)) {
				foreach($param as $key => $value) {
					$param2[$key] = $this->shortenText($value);
				}
				return $param2;
			}
			else if(mb_strlen($param, B_CHARSET) > 400) {
				return mb_substr($param, 0, 100, B_CHARSET) . '...' .  mb_substr($param, -40, 40, B_CHARSET);
			}
			return $param;
		}

		function write_archive_log($message) {
			if($this->fp) {
				$message = $this->replaceLFcode($message);
				fwrite($this->fp, $message . ";\n");
			}
		}

		function close() {
			fclose($this->fp);
		}

		function replaceLFcode($str) {
			$str = str_replace("\r\n", "\n", $str);
			$str = str_replace("\r", "\n", $str);

			return $str;
		}
	}

	// -------------------------------------------------------------------------
	// class console
	// 
	// -------------------------------------------------------------------------
	class console {
		public static $buffer;

		public static function log() {
			for($i=0; $i<func_num_args(); $i++) {
				$param = func_get_arg($i);
				$message[] = $param;
			}
			$json = json_encode($message);
			self::$buffer.= '<script type="text/javascript">' . "console.log({$json})</script>" . "\n";
		}

		public static function trace() {
			$message = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
			$json = json_encode($message);
			self::$buffer.= '<script type="text/javascript">' . "console.log({$json})</script>" . "\n";
		}

		public static function buffer() {
			return self::$buffer;
		}
	}
