<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	// Language
	if(!defined('LANG')) {
		$log = new B_Log(B_LOG_FILE);
		define('LANG', '%LANGUAGE%');
		$log->write('lang_config LANG', LANG);
	}