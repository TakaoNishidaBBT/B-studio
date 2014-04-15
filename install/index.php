<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	define('B_CHARSET', 'UTF-8');
	mb_internal_encoding(B_CHARSET);
	ini_set('display_errors', 'On');

	require_once('config/form_config.php');
	require_once('../bs-admin/class/B_Element.php');
	require_once('../bs-admin/class/B_Session.php');
	$db_install_form = new B_Element($db_install_form_config);
	$admin_basic_auth_form = new B_Element($admin_basic_auth_config);
	$admin_user_form = new B_Element($admin_user_form_config);
	$root_htaccess = new B_Element($root_htaccess_config);

	// start session
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

	$ses = new B_Session;
	$ses->start('nocache', 'bs-install', SESSION_DIR);

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
	if(!function_exists('gd_info')) {
		$error_message = 'GDライブラリを有効にしてください';
	}

	// HTTPヘッダー出力
	header('Cache-Control: no-cache, must-revalidate'); 
	header('Content-Language: ja');
	header('Content-Type: text/html; charset=UTF-8');

	// HTML 出力
	include('./view/view_index.php');
	exit;

	function setHtaccess($root_htaccess) {
		// setup htaccess
		if(file_exists('../.htaccess')) {
			$htaccess = file_get_contents('../.htaccess') . "\n";
		}
		$contents = file_get_contents('./config/htaccess.txt');
		$contents = str_replace('%REWRITE_BASE%', ROOT_DIR, $contents);

		$param['htaccess'] = $htaccess . $contents;

		$root_htaccess->setValue($param);
	}

	function confirmPermission(&$message) {
		$status = checkPermission(DOC_ROOT . ROOT_DIR . '.htaccess', $message);
		$status&= checkPermission(DOC_ROOT . ROOT_DIR . 'bs-admin/archive', $message);
		$status&= checkPermission(DOC_ROOT . ROOT_DIR . 'bs-admin/cache', $message);
		$status&= checkPermission(DOC_ROOT . ROOT_DIR . 'bs-admin/config/core_config.php', $message);
		$status&= checkPermission(DOC_ROOT . ROOT_DIR . 'bs-admin/db/db_connect.php', $message);
		$status&= checkPermission(DOC_ROOT . ROOT_DIR . 'bs-admin/download', $message);
		$status&= checkPermission(DOC_ROOT . ROOT_DIR . 'bs-admin/log', $message);
		$status&= checkPermission(DOC_ROOT . ROOT_DIR . 'bs-admin/user/users.php', $message);
		$status&= checkPermission(DOC_ROOT . ROOT_DIR . 'bs-admin-files/files', $message);
		$status&= checkPermission(DOC_ROOT . ROOT_DIR . 'bs-admin-files/thumbs', $message);
		$status&= checkPermission(DOC_ROOT . ROOT_DIR . 'bs-admin-files/work', $message);
		$status&= checkPermission(DOC_ROOT . ROOT_DIR . 'files', $message);
		return $status;
	}

	function checkPermission($path, &$message) {
		if(!file_exists($path)) {
			$message.= '<span class="status_ok">' . $path  . ' の書き込み権限はOKです。(file not exist))</span><br />';
			return true;
		}
		else {
			$perms = fileperms($path);
			if(is_writable($path)) {
				$message.= '<span class="status_ok">' . $path  . ' の書き込み権限はOKです。(permission:' . substr(sprintf('%o',$perms), -3) . ')</span><br />';
				return true;
			}
			else {
				$message.= '<span class="status_ng">' . $path  . ' に書き込み権限がありません。(permission:' . substr(sprintf('%o',$perms), -3) . ')</span><br />';
				return false;
			}
		}
	}

	function confirm($db_install_form, $admin_basic_auth_form, $admin_user_form, $root_htaccess, &$perm_message, &$error_message) {
		// ディレクトリパーミッションの確認
		$status = confirmPermission($perm_message);

		// POSTされた値を設定
		$db_install_form->setValue($_POST);
		$admin_basic_auth_form->setValue($_POST);
		$admin_user_form->setValue($_POST);
		$root_htaccess->setValue($_POST);
		$status&= $db_install_form->validate();
		$status&= $admin_basic_auth_form->validate();
		$status&= $admin_user_form->validate();
		$status&= $root_htaccess->validate();

		if($status) {
			// DB接続テスト
			if(class_exists('mysqli')) {
				$db = new mysqli($_POST['db_srv'], $_POST['db_usr'], $_POST['db_pwd'], $_POST['db_nme']);
			}
			else {
				$db = @mysql_connect($_POST['db_srv'], $_POST['db_usr'], $_POST['db_pwd']);
				if($db) {
					$status = @mysql_select_db($_POST['db_nme'], $db);
					if(!$status) {
						$obj = $db_install_form->getElementByName('db_nme');
						$obj->status = false;
						$status = $db_install_form->validate();
						$error_message = 'DBへ接続はできましたがスキーマの選択に失敗しました。';
					}
				}
			}
			if(!$db || $db->connect_error) {
				// DB接続エラー
				$obj = $db_install_form->getElementByName('db_srv');
				$obj->status = false;
				$obj = $db_install_form->getElementByName('db_usr');
				$obj->status = false;
				$obj = $db_install_form->getElementByName('db_pwd');
				$obj->status = false;
				$obj = $db_install_form->getElementByName('db_nme');
				$obj->status = false;
				$status = $db_install_form->validate();
				$error_message = 'DBへの接続に失敗しました。<br />(' . $db->connect_error . ')';
			}
		}
		else {
			// 入力確認エラー
			$error_message = '入力内容に誤りがあります。<br />各欄のエラーメッセージをご覧の上、入力し直してください。';
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
