<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_STRICT);
	ini_set('display_errors', 'On');
	set_error_handler('exception_error_handler');

	// CHARSET
	define('B_CHARSET', 'UTF-8');

	// Start session
	require_once('../bs-admin/class/B_Session.php');
	$info = pathinfo($_SERVER['SCRIPT_NAME']);
	define('SESSION_DIR', $info['dirname']);

	$ses = new B_Session;
	$ses->start('nocache', 'bs-install', SESSION_DIR);

	// Define directories
	$root_dir = dirname($info['dirname']);
	if(substr($root_dir, -1) == '/') {
		$root_dir = substr($root_dir, 0, -1);
	}
	$doc_root = $_SERVER['DOCUMENT_ROOT'];
	if(substr($doc_root, -1) == '/') {
		$doc_root = substr($doc_root, 0, -1);
	}
	define('SESSION_DIR', $info['dirname']);
	define('ROOT_DIR', $root_dir . '/');
	define('DOC_ROOT', $doc_root);
	define('B_DOC_ROOT', $doc_root);
	define('B_ADMIN_ROOT', ROOT_DIR . 'bs-admin/');
	define('B_LNGUAGE_DIR', B_DOC_ROOT . B_ADMIN_ROOT . 'language/');

	// Set $_SESSION['language']
	define('LANG', 'en');
	if(!$_SESSION['language']) $_SESSION['language'] = LANG;

	if($_POST['action'] == 'select-language' && function_exists('mb_internal_encoding')) {
		$_SESSION['language'] = $_POST['language'];
		$_SESSION['install_confirm'] = false;
	}

	// Language file
	require_once('../bs-admin/language/language.php');

	// ffmpeg
	if(substr(PHP_OS, 0, 3) === 'WIN') {
		define('FFMPEG', 'ffmpeg_for_windows.exe');
	}
	else if(substr(PHP_OS, 0, 5) === 'Linux') {
		define('FFMPEG', 'ffmpeg_for_linux');
	}
	else {
		define('FFMPEG', 'ffmpeg_for_mac');
	}

	// Confirm timezone
	date('Ymd');

	require_once('config/_form_config.php');
	require_once('../bs-admin/class/B_Element.php');
	$select_language = new B_Element($select_language_config);
	$db_install_form = new B_Element($db_install_form_config);
	$admin_basic_auth_form = new B_Element($admin_basic_auth_config);
	$admin_user_form = new B_Element($admin_user_form_config);
	$root_htaccess = new B_Element($root_htaccess_config);

	if($_POST['action'] == 'confirm') {
		$_SESSION['install_confirm'] = true;

		if(confirm($db_install_form, $admin_basic_auth_form, $admin_user_form, $root_htaccess, $perm_message, $error_message)) {
			$db_install_form->getValue($_SESSION['param']);
			$admin_basic_auth_form->getValue($_SESSION['param']);
			$admin_user_form->getValue($_SESSION['param']);
			$root_htaccess->getValue($_SESSION['param']);
			$path = 'confirm.php';
			header("Location:$path");
			exit;
		}
	}
	else if($_SESSION['install_confirm']) {
		back($db_install_form, $admin_basic_auth_form, $admin_user_form, $root_htaccess, $perm_message);
	}
	else {
		setHtaccess($root_htaccess);
		confirmPermission($perm_message);
	}

	// Set value to language selectbox
	if(function_exists('mb_internal_encoding')) {
		$select_language->setValue(array('language' => $_SESSION['language']));
	}
	else {
		$error_message = __('Please enable mbstring module');
	}
	if(!session_save_path()) {
		$error_message = __('Please set session.save_path');
	}
	if(!class_exists('mysqli') && !function_exists('mysql_connect')) {
		$error_message = __('Please enable MySQL library');
	}
	if(!function_exists('gd_info')) {
		$error_message = __('Please enable GD library');
	}
	if(!function_exists('exif_read_data')) {
		$error_message = __('Please enable exif library');
	}
	if(!function_exists('simplexml_load_file')) {
		$error_message = __('Please enable SimpleXML library');
	}
	if(!class_exists('ZipArchive')) {
		$error_message = __('ZipArchive is necessary');
	}

	// Send HTTP header
	header('Cache-Control: no-cache, must-revalidate'); 
	header('Content-Language: ' . $_SESSION['language']);
	header('Content-Type: text/html; charset=UTF-8');

	// Show HTML
	$view_folder = getViewFolder();
	include('./view/' . $view_folder . 'view_index.php');
	exit;

	function getViewFolder() {
		switch($_SESSION['language']) {
		case 'ja':
			return 'ja/';

		case 'zh-cn':
			return 'zh-cn/';

		default:
			return;
		}
	}

	function setHtaccess($root_htaccess) {
		// Set up htaccess
		$contents = file_get_contents('./config/_htaccess.txt');
		$contents = str_replace('%REWRITE_BASE%', ROOT_DIR, $contents);
		$param['htaccess'].= $contents;
		$root_htaccess->setValue($param);
	}

	function confirmPermission(&$message) {
		$status  = checkWritePermission(DOC_ROOT . ROOT_DIR . '.htaccess', $message);
		$status &= checkWritePermission(DOC_ROOT . ROOT_DIR . 'bs-admin/.htaccess', $message);
		$status &= checkWritePermission(DOC_ROOT . ROOT_DIR . 'bs-admin/.htpassword', $message);
		$status &= checkWritePermission(DOC_ROOT . ROOT_DIR . 'bs-admin/archive', $message);
		$status &= checkWritePermission(DOC_ROOT . ROOT_DIR . 'bs-admin/cache', $message);
		$status &= checkWritePermission(DOC_ROOT . ROOT_DIR . 'bs-admin/config/core_config.php', $message);
		$status &= checkWritePermission(DOC_ROOT . ROOT_DIR . 'bs-admin/config/lang_config.php', $message);
		$status &= checkWritePermission(DOC_ROOT . ROOT_DIR . 'bs-admin/db/db_connect.php', $message);
		$status &= checkWritePermission(DOC_ROOT . ROOT_DIR . 'bs-admin/download', $message);
		$status &= checkWritePermission(DOC_ROOT . ROOT_DIR . 'bs-admin/log', $message);
		$status &= checkWritePermission(DOC_ROOT . ROOT_DIR . 'bs-admin/user/users.php', $message);
		$status &= checkWritePermission(DOC_ROOT . ROOT_DIR . 'bs-admin-files', $message);
		$status &= checkWritePermission(DOC_ROOT . ROOT_DIR . 'bs-admin-files/.htaccess', $message);
		$status &= checkWritePermission(DOC_ROOT . ROOT_DIR . 'bs-admin-files/files', $message);
		$status &= checkWritePermission(DOC_ROOT . ROOT_DIR . 'bs-admin-files/thumbs', $message);
		$status &= checkWritePermission(DOC_ROOT . ROOT_DIR . 'files', $message);
		$status &= checkExecutePermission(DOC_ROOT . ROOT_DIR . 'bs-admin/class/ffmpeg/' . FFMPEG, $message);
		return $status;
	}

	function checkWritePermission($path, &$message) {
		if(!file_exists($path)) {
			$message.= '<span class="status_ok">' . $path  . __(' write permission granted. ') .  '(file not exist)</span><br />';
			return true;
		}
		else {
			$perms = fileperms($path);
			if(is_writable($path)) {
				$message.= '<span class="status_ok">' . $path  . __(' : write permission granted. ') . '(permission:' . substr(sprintf('%o',$perms), -3) . ')</span><br />';
				return true;
			}
			else {
				$message.= '<span class="status_ng">' . $path  . __(' : write permission not set. ') . '(permission:' . substr(sprintf('%o',$perms), -3) . ')</span><br />';
				return false;
			}
		}
	}

	function checkExecutePermission($path, &$message) {
		if(!file_exists($path)) {
			$message.= '<span class="status_ng">' . $path  .  '(file not exist)</span><br />';
			return false;
		}
		else {
			$perms = fileperms($path);
			if(is_executable($path)) {
				$message.= '<span class="status_ok">' . $path  . __(' : execute permission granted. ') . '(permission:' . substr(sprintf('%o',$perms), -3) . ')</span><br />';
				return true;
			}
			else {
				$message.= '<span class="status_ng">' . $path  . __(' : execute permission not set. ') . '(permission:' . substr(sprintf('%o',$perms), -3) . ')</span><br />';
				return false;
			}
		}
	}

	function confirm($db_install_form, $admin_basic_auth_form, $admin_user_form, $root_htaccess, &$perm_message, &$error_message) {
		// Confirm directory permission
		$status = confirmPermission($perm_message);

		// Set value from $_POST
		$db_install_form->setValue($_POST);
		$admin_basic_auth_form->setValue($_POST);
		$admin_user_form->setValue($_POST);
		$root_htaccess->setValue($_POST);
		$status&= $db_install_form->validate();
		$status&= $admin_basic_auth_form->validate();
		$status&= $admin_user_form->validate();
		$status&= $root_htaccess->validate();

		if($status) {
			// Test of connecting to DB 
			if(class_exists('mysqli')) {
				$db = @mysqli_connect($_POST['db_srv'], $_POST['db_usr'], $_POST['db_pwd'], $_POST['db_nme']);
			}
			else {
				$db = @mysql_connect($_POST['db_srv'], $_POST['db_usr'], $_POST['db_pwd']);
				if($db) {
					$status = @mysql_select_db($_POST['db_nme'], $db);
					if(!$status) {
						$obj = $db_install_form->getElementByName('db_nme');
						$obj->status = false;
						$status = $db_install_form->validate();
						$error_message = __('Able to connect to DB but failed to select schema.');
					}
				}
			}
			if(!$db || $db->connect_error) {
				// Connecting DB error
				$obj = $db_install_form->getElementByName('db_srv');
				$obj->status = false;
				$obj = $db_install_form->getElementByName('db_usr');
				$obj->status = false;
				$obj = $db_install_form->getElementByName('db_pwd');
				$obj->status = false;
				$obj = $db_install_form->getElementByName('db_nme');
				$obj->status = false;
				$status = $db_install_form->validate();
				$error_message = __('Faild to connect to DB.');
				if($db->connect_error) {
					$error_message.= '<br />(' . $db->connect_error . ')';
				}
			}
		}
		else {
			// Confirm message
			$error_message = __('This is an error in your entry<br />Please check any error message and re-enter the necessary information');
		}

		return $status;
	}

	function back($db_install_form, $admin_basic_auth_form, $admin_user_form, $root_htaccess, &$perm_message) {
		$db_install_form->setValue($_SESSION['param']);
		$admin_basic_auth_form->setValue($_SESSION['param']);
		$admin_user_form->setValue($_SESSION['param']);
		$root_htaccess->setValue($_SESSION['param']);
		confirmPermission($perm_message);
	}

	function exception_error_handler($errno, $errstr, $errfile, $errline ) {
		if(!(error_reporting() & $errno)) {
			// Error_reporting, unexpected error has occurred
			return;
		}

		throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	}
