<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_STRICT);
	ini_set('display_errors','On');
	set_error_handler('exception_error_handler');

	// CHARSET
	define('B_CHARSET', 'UTF-8');
	mb_internal_encoding(B_CHARSET);

	// Start session
	$info = pathinfo($_SERVER['SCRIPT_NAME']);
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
	define('LANG', 'en');

	require_once('../bs-admin/class/B_Session.php');

	$ses = new B_Session;
	$ses->start('nocache', 'bs-install', SESSION_DIR);

	// Document root directory
	if(substr($_SERVER['DOCUMENT_ROOT'], -1) == '/') {
		define('B_DOC_ROOT', substr($_SERVER['DOCUMENT_ROOT'], 0, -1));
	}
	else {
		define('B_DOC_ROOT', $_SERVER['DOCUMENT_ROOT']);
	}

	// Directory for admin page
	define('B_ADMIN_ROOT', ROOT_DIR . 'bs-admin/');

	require_once('../bs-admin/config/language.php');

	require_once('config/_form_config.php');
	require_once('../bs-admin/class/B_Element.php');
	require_once('../bs-admin/class/B_Session.php');
	$db_install_form = new B_Element($db_install_form_config);
	$admin_basic_auth_form = new B_Element($admin_basic_auth_config);
	$admin_user_form = new B_Element($admin_user_form_config);
	$root_htaccess = new B_Element($root_htaccess_config);

	$db_install_form->setValue($_SESSION['param']);
	$admin_basic_auth_form->setValue($_SESSION['param']);
	$admin_user_form->setValue($_SESSION['param']);
	$root_htaccess->setValue($_SESSION['param']);

	if($_POST['action'] == 'install') {
		$status = install($db_install_form, $admin_basic_auth_form, $admin_user_form, $root_htaccess, $error_message);
		if($status) {
			require_once('../bs-admin/config/config.php');
			$status = db_install($error_message);
		}
		if($status) {
			$status = contents_install($error_message);
		}
		if($status) {
			$path = 'complete.php';
			header("Location:$path");
			exit;
		}
	}

	// Send HTTP header
	header('Cache-Control: no-cache, must-revalidate'); 
	header('Content-Language: ja');
	header('Content-Type: text/html; charset=UTF-8');

	// Show HTML
	$view_folder = getViewFolder();
	include('./view/' . $view_folder . 'view_confirm.php');
	exit;

	function getViewFolder() {
		switch($_SESSION['language']) {
		case 'jp':
			return 'jp/';

		default:
			return;
		}
	}

	function install($db_install_form, $admin_basic_auth_form, $admin_user_form, $root_htaccess, &$error_message) {
		$db_install_form->getValue($param);
		$admin_basic_auth_form->getValue($param);
		$admin_user_form->getValue($param);

		try {
			// setup htaccess
			$obj = $root_htaccess->getElementByName('htaccess');
			file_put_contents('../.htaccess', $obj->value);

			// setup bs-admin htaccess basic authentication
			$contents = file_get_contents('./config/_admin_htaccess.txt');
			$contents = str_replace('%AUTH_USER_FILE%', DOC_ROOT . ROOT_DIR . 'bs-admin/.htpassword', $contents);
			file_put_contents('../bs-admin/.htaccess', $contents);
			file_put_contents('../bs-admin-files/.htaccess', $contents);

			// setup bs-admin password
			$password = $param['basic_auth_id'] . ':' . htpasswd($param['basic_auth_pwd']);
			file_put_contents('../bs-admin/.htpassword', $password);

			// setup built-in admin user file
			$contents = file_get_contents('./config/_users.php');
			$contents = str_replace('%USER_NAME%',  $param['admin_user_name'], $contents);
			$contents = str_replace('%USER_ID%',  $param['admin_user_id'], $contents);
			$contents = str_replace('%PASSWORD%', md5($param['admin_user_pwd']), $contents);
			$contents = str_replace('%LANGUAGE%', $_SESSION['language'], $contents);
			file_put_contents('../bs-admin/user/users.php', $contents);

			// setup core_config(current_root)
			$contents = file_get_contents('./config/_core_config.php');
			$contents = str_replace('%LANGUAGE%', $_SESSION['language'], $contents);
			$contents = str_replace('%CURRENT_ROOT%', ROOT_DIR, $contents);
			$contents = str_replace('%DB_PREFIX%', $param['db_prefix'], $contents);
			file_put_contents('../bs-admin/config/core_config.php', $contents);

			// setup db_connect(current_root)
			$contents = file_get_contents('./config/_db_connect.php');
			$contents = str_replace('%B_DB_NME%', $param['db_nme'], $contents);
			$contents = str_replace('%B_DB_USR%', $param['db_usr'], $contents);
			$contents = str_replace('%B_DB_PWD%', $param['db_pwd'], $contents);
			$contents = str_replace('%B_DB_SRV%', $param['db_srv'], $contents);
			file_put_contents('../bs-admin/db/db_connect.php', $contents);
		}
		catch(Exception $e) {
			$error_message = '<p class="error-message">' . $e->getMessage() . '</p>';
			return false;
		}

		return true;
	}

	function db_install(&$error_message) {
		if(file_exists('./default/bstudio.zip')) {
			return true;
		}

		require_once('db_install.php');
		$db_install = new db_install();
		$status = $db_install->install();
		if(!$status) {
			$error_message = $db_install->getErrorMessage();
		}

		return $status;
	}

	function contents_install(&$error_message) {
		require_once('contents_install.php');
		$contents_install = new contents_install();
		$status = $contents_install->install();
		if(!$status) {
			$error_message = $contents_install->getErrorMessage();
		}

		return $status;
	}

	function htpasswd($plainpasswd) {
		$salt = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 8);
		$len = strlen($plainpasswd);
		$text = $plainpasswd . '$apr1$' . $salt;
		$bin = pack('H32', md5($plainpasswd . $salt . $plainpasswd));

		for($i=$len; $i>0; $i-=16) {
			$text.= substr($bin, 0, min(16, $i));
		}
		for($i=$len; $i>0; $i>>=1) {
			$text.= ($i & 1) ? chr(0) : $plainpasswd{0};
		}

		$bin = pack('H32', md5($text));

		for($i=0; $i<1000; $i++) {
			$new = ($i&1) ? $plainpasswd : $bin;
			if($i%3) $new.= $salt;
			if($i%7) $new.= $plainpasswd;
			$new.= ($i&1) ? $bin : $plainpasswd;
			$bin = pack('H32', md5($new));
		}

		for($i=0; $i<5; $i++) {
			$k = $i+6;
			$j = $i+12;
			if($j == 16) $j = 5;
			$tmp = $bin[$i] . $bin[$k] . $bin[$j] . $tmp;
		}

		$tmp = chr(0).chr(0).$bin[11].$tmp;
		$tmp = strtr(strrev(substr(base64_encode($tmp), 2)),
		'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/',
		'./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz');

		return '$apr1$' . $salt . '$' . $tmp;
	}

	function exception_error_handler($errno, $errstr, $errfile, $errline) {
		if(!(error_reporting() & $errno)) {
			// error_reporting, unexpected error has occurred
			return;
		}

		throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	}
