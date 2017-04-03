<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	$lang = LANG;
	if($_SESSION['language']) {
		$lang = $_SESSION['language'];
	}

	mb_language('uni');

	// Language file
	switch($lang) {
	case 'ja':
		require_once(B_LNGUAGE_DIR . 'lang/ja.php');
		define('B_MB_DETECT_ORDER', 'UTF-8, EUC-JP, SJIS');
		mb_detect_order(B_MB_DETECT_ORDER);
		break;

	case 'zh-cn':
		require_once(B_LNGUAGE_DIR . 'lang/zh-cn.php');
		define('B_MB_DETECT_ORDER', 'UTF-8, GB18030, EUC-CN');
		mb_detect_order(B_MB_DETECT_ORDER);
		break;

	default:
		define('B_MB_DETECT_ORDER', 'UTF-8, EUC-JP, SJIS, GB18030, EUC-CN');
		break;
	}

	// Globa Data File
	$g_data_set = 'b_global_data';
	require_once(B_DOC_ROOT . B_ADMIN_ROOT . 'global/' . $g_data_set . '.php');

	// Test Server Settings
	if(preg_match('/www.test-server.com/', B_HTTP_HOST)) {
		define('B_TITLE_PREFIX', __('(Test)'));
		define('B_ARCHIVE_LOG_MODE', 'DEBUG');
	}
	else if(preg_match('/localhost/', B_HTTP_HOST)) {
		define('B_TITLE_PREFIX', __('(Test)'));
		define('B_ARCHIVE_LOG_MODE', 'DEBUG');
	}
	else {
		define('B_TITLE_PREFIX', '');
		define('B_ARCHIVE_LOG_MODE', '');
	}

	function __($text) {
		if($_SESSION['language'] == 'en') return $text;

		global $texts;

		return $texts[$text] ? $texts[$text] : $text;
	}
