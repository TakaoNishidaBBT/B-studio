<?php
/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	// -------------------------------------------------------------------------
	// class B_Controller
	// 
	// -------------------------------------------------------------------------
	class B_Controller {
		function __construct() {
			$this->log = new B_Log(B_ACCESS_LOG_FILE);
			$this->log->write('----- start -----' . "\n", '$_REQUEST:', $_REQUEST);
		}

		function dispatch($dir, $file, $class=null, $method=null) {
			$this->log->write("DIR: $dir FILE: $file CLASS: $class METHOD: $method");

			// change directory and read file
			$file_name = $dir . '/' . $file;
			if(preg_match('/\.\./', $file_name)) {
				$this->log->write("file: $file_name access denied (include .. dot dot)");
				return false;
			}
			if(!file_exists($file_name)) {
				$this->log->write("file: $file_name . not exist");
				return false;
			}
			chdir($dir);
			require_once($file);

			// create class
			if(!class_exists($class)) {
				$this->log->write("file: $file_name class: $class not exist");
				return false;
			}

			$obj = new $class($class);
			$GLOBALS['current_obj'] = $obj;

			// dispatch method
			if(method_exists($obj, $method)) {
				$obj->$method();
			}

			// dispatch view method
			if(method_exists($obj, 'getView')) {
				$view = $obj->getView();
				if(method_exists($obj, $view)) {
					$obj->$view();
					echo '</html>';
				}
			}

			return true;
		}
	}
