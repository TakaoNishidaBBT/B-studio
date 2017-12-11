<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	// Language config
	require_once('lang_config.php');

	// System Name
	define('B_SYSTEM_NAME', 'B-studio');

	// Site Name
	define('B_SITE_NAME', 'bstudio');

	// Debaug Msode
	define('B_DEBUG_MODE', 'OFF');

	// SSL for Admin Page
	define('B_ADMIN_SSL', 'OFF');

	//HTTP HOST
	define('B_HTTP_HOST', $_SERVER['SERVER_NAME']);

	// Session Name for Admin Page
	define('B_ADMIN_SESSION_NAME', B_SITE_NAME . '-bs-admin');

	// Session Secure
	if(!preg_match('/localhost/', B_HTTP_HOST) && B_ADMIN_SSL == 'ON') {
		define('B_ADMIN_SESSION_SECURE', true);
	}
	else {
		define('B_ADMIN_SESSION_SECURE', false);
	}

	//Document Root Directory
	if(substr($_SERVER['DOCUMENT_ROOT'], -1) == '/') {
		define('B_DOC_ROOT', substr($_SERVER['DOCUMENT_ROOT'], 0, -1));
	}
	else {
		define('B_DOC_ROOT', $_SERVER['DOCUMENT_ROOT']);
	}

	// Current Root Directory (This System's Top Directory)
	define('B_CURRENT_ROOT', '%CURRENT_ROOT%');

	// Directory for Admin Page
	define('B_ADMIN_ROOT', B_CURRENT_ROOT . 'bs-admin/');

	// This is Full URL for Top Page
	define('B_SITE_ROOT', 'http://' . B_HTTP_HOST . B_CURRENT_ROOT);
	define('B_SITE_ROOT_SSL', 'https://' . B_HTTP_HOST . B_CURRENT_ROOT);

	// Site Base Directory (for Base Tag)
	if(empty($_SERVER['HTTPS']) === true || $_SERVER['HTTPS'] !== 'on') {
		define('B_SITE_BASE', B_SITE_ROOT);
	}
	else {
		define('B_SITE_BASE', B_SITE_ROOT_SSL);
	}

	// Log Files
	// Archive Log File
	define('B_ARCHIVE_LOG_FILE', B_DOC_ROOT . B_ADMIN_ROOT . 'archive/archive.log');

	// Access Log File
	define('B_ACCESS_LOG_FILE', B_DOC_ROOT . B_ADMIN_ROOT . 'log/access.log');

	// Mail Log File
	define('B_MAIL_LOG_FILE', B_DOC_ROOT . B_ADMIN_ROOT . 'log/mail.log');

	// Log File
	define('B_LOG_FILE', B_DOC_ROOT . B_ADMIN_ROOT . 'log/log.txt');

	// Language files Directory
	define('B_LNGUAGE_DIR', B_DOC_ROOT . B_ADMIN_ROOT . 'language/');

	// DataBase Connect File
	define('B_DB_CONNECT_INFO', B_DOC_ROOT . B_ADMIN_ROOT . 'db/db_connect.php');
	require_once(B_DB_CONNECT_INFO);

	// Download Directory
	define('B_DOWNLOAD_DIR', B_DOC_ROOT . B_ADMIN_ROOT . 'download/');

	// User File Directory for Resource
	define('B_ADMIN_FILES', 'bs-admin-files/');
	define('B_ADMIN_FILES_DIR', B_DOC_ROOT . B_CURRENT_ROOT . B_ADMIN_FILES);
	define('B_RESOURCE_DIR', B_ADMIN_FILES_DIR . 'files/');
	define('B_RESOURCE_URL', B_CURRENT_ROOT . B_ADMIN_FILES . 'files/');
	define('B_RESOURCE_WORK_DIR', B_ADMIN_FILES_DIR);
	define('B_RESOURCE_EXTRACT_DIR', B_ADMIN_FILES_DIR . 'extract/');

	// User File Directory for Article
	define('B_UPLOAD_FILES', 'files/');
	define('B_UPLOAD_DIR', B_DOC_ROOT . B_CURRENT_ROOT . B_UPLOAD_FILES);
	define('B_UPLOAD_THUMBDIR', B_ADMIN_FILES_DIR . 'thumbs/');
	define('B_UPLOAD_URL', B_CURRENT_ROOT . B_UPLOAD_FILES);

	// Dump File
	define('B_DUMP_FILE', 'dump/');

	// Thunbnail
	define('B_THUMB_PREFIX', 'thumb_');
	define('B_THUMB_MAX_SIZE', '100');

	// Trash
	define('B_TRASH_PATH', 'trash/');

	// File Information
	define('B_CACHE_DIR', B_DOC_ROOT . B_ADMIN_ROOT . 'cache/');
	define('B_FILE_INFO_C', B_CACHE_DIR . 'file_info_c.txt');
	define('B_FILE_INFO_W', B_CACHE_DIR . 'file_info_w.txt');
	define('B_FILE_INFO_SEMAPHORE_C', B_CACHE_DIR . 'file_info_semaphore_c.txt');
	define('B_FILE_INFO_SEMAPHORE_W', B_CACHE_DIR . 'file_info_semaphore_w.txt');
	define('B_FILE_INFO_THUMB', B_CACHE_DIR . 'file_info_thumb.txt');
	define('B_FILE_INFO_THUMB_SEMAPHORE', B_CACHE_DIR . 'file_info_thumb_semaphore.txt');
	define('B_LIMIT_FILE_INFO', B_CACHE_DIR . 'limit_info_c.txt');

	// Charset
	define('B_CHARSET', 'UTF-8');

	// Charset for Header
	define('B_CHARSET_HEADER', 'UTF-8');

	// Charset for xml header
	define('B_CHARSET_XML_HEADER', 'UTF-8');

	// ffmpeg
	if(substr(PHP_OS, 0, 3) === 'WIN') {
		define('FFMPEG', B_DOC_ROOT . B_ADMIN_ROOT . 'class/ffmpeg/ffmpeg_for_windows.exe');
	}
	else if(substr(PHP_OS, 0, 5) === 'Linux') {
		define('FFMPEG', B_DOC_ROOT . B_ADMIN_ROOT . 'class/ffmpeg/ffmpeg_for_linux');
	}
	else {
		define('FFMPEG', B_DOC_ROOT . B_ADMIN_ROOT . 'class/ffmpeg/ffmpeg_for_mac');
	}

	// Table
	define('B_CONTENTS_NODE_TABLE', 'contents_node');
	define('B_TEMPLATE_NODE_TABLE', 'template_node');
	define('B_WIDGET_NODE_TABLE', 'widget_node');
	define('B_FORM_NODE_TABLE', 'form_node');
	define('B_RESOURCE_NODE_TABLE', 'resource_node');
	define('B_CONTENTS_TABLE', 'contents');
	define('B_TEMPLATE_TABLE', 'template');
	define('B_WIDGET_TABLE', 'widget');
	define('B_FORM_TABLE', 'form');
	define('B_FORM_FIELD_TABLE', 'form_field');
	define('B_CATEGORY_TABLE', 'category');
	define('B_CATEGORY2_TABLE', 'category2');
	define('B_CATEGORY3_TABLE', 'category3');
	define('B_VERSION_TABLE', 'version');

	// View
	define('B_WORKING_CONTENTS_NODE_VIEW', 'v_w_contents_node');
	define('B_WORKING_CONTENTS_VIEW', 'v_w_contents');
	define('B_WORKING_TEMPLATE_VIEW', 'v_w_template');
	define('B_WORKING_TEMPLATE_NODE_VIEW', 'v_w_template_node');
	define('B_WORKING_WIDGET_VIEW', 'v_w_widget');
	define('B_WORKING_WIDGET_NODE_VIEW', 'v_w_widget_node');
	define('B_WORKING_FORM_FIELD_VIEW', 'v_w_form_field');
	define('B_WORKING_FORM_VIEW', 'v_w_form');
	define('B_WORKING_FORM_NODE_VIEW', 'v_w_form_node');
	define('B_WORKING_RESOURCE_NODE_VIEW', 'v_w_resource_node');

	define('B_CURRENT_CONTENTS_NODE_VIEW', 'v_c_contents_node');
	define('B_CURRENT_CONTENTS_VIEW', 'v_c_contents');
	define('B_CURRENT_TEMPLATE_VIEW', 'v_c_template');
	define('B_CURRENT_TEMPLATE_NODE_VIEW', 'v_c_template_node');
	define('B_CURRENT_WIDGET_VIEW', 'v_c_widget');
	define('B_CURRENT_WIDGET_NODE_VIEW', 'v_c_widget_node');
	define('B_CURRENT_FORM_FIELD_VIEW', 'v_c_form_field');
	define('B_CURRENT_FORM_VIEW', 'v_c_form');
	define('B_CURRENT_FORM_NODE_VIEW', 'v_c_form_node');
	define('B_CURRENT_RESOURCE_NODE_VIEW', 'v_c_resource_node');

	define('B_CURRENT_VERSION_VIEW', 'v_current_version');

	define('B_CATEGORY_VIEW', 'v_category');
	define('B_CATEGORY2_VIEW', 'v_category2');
	define('B_CATEGORY3_VIEW', 'v_category3');

	// View for compare
	define('B_COMPARE_CONTENTS_NODE_VIEW', 'v_compare_contents_node');
	define('B_COMPARE_RESOURCE_NODE_VIEW', 'v_compare_resource_node');
	define('B_COMPARE_TEMPLATE_NODE_VIEW', 'v_compare_template_node');
	define('B_COMPARE_WIDGET_NODE_VIEW', 'v_compare_widget_node');

	// PHP Display Errors
	ini_set('display_errors','Off');
	ini_set('log_errors','On');
	ini_set('error_log', B_DOC_ROOT . B_ADMIN_ROOT . 'log/system.log');
