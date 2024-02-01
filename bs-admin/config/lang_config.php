<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	// Language
$log = new B_LOG_(B_LOG_FILE);
$log->write('defined(LANG)', defined('LANG'), 'LANG', LANG);
	if(!defined('LANG')) define('LANG', 'ja');
