<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class index_index extends B_AdminModule {
		function __construct() {
			if(B_ADMIN_SSL == 'ON' && !preg_match('/localhost/', B_HTTP_HOST)) {
				if(empty($_SERVER['HTTPS']) === true || $_SERVER['HTTPS'] !== 'on') {
					// Redirect to https
					$path = B_SITE_ROOT_SSL . 'bs-admin/';
					header("Location:$path");
					exit;
				}
			}
			parent::__construct(__FILE__);

			$this->site_settings_table = new B_Table($this->db, 'settings');
			$this->site_settings = $this->site_settings_table->select();
			$this->site_title = htmlspecialchars($this->site_settings['admin_site_title'], ENT_QUOTES, B_CHARSET);
			$this->title = B_TITLE_PREFIX . $this->site_title;

			// Check browser
			if(!$this->checkBrowser()) {
				$this->view_file = './view/view_support_browsers.php';
				return;
			}

			// Check logedin
			$auth = new B_AdminAuth;
			$ret = $auth->getUserInfo($user_id, $this->user_name, $this->user_auth);
			if($ret) {
				$this->admin();
			}
			else {
				$this->login();
			}
		}

		function admin() {
			if(!defined('TERMINAL_ID')) {
				// Set TERMINAL_ID
				$util = new B_Util();
				define('TERMINAL_ID', $util->getRandomText(12));
				$_SESSION['terminal_id'] = TERMINAL_ID;

				// Set session for each TERMINAL_ID
				$_SESSION[TERMINAL_ID] = array();
			}
			define('DISPATCH_URL', 'index.php?terminal_id=' . TERMINAL_ID);

			// bframe_message
			require_once('./config/bframe_message_config.php');
			$this->bframe_message = new B_Element($bframe_message_config, $this->user_auth);

			// Menu
			require_once('./config/menu_config.php');
			$this->menu = new B_Element($menu_config, $this->user_auth);
			$this->user_name = htmlspecialchars($this->user_name, ENT_QUOTES, B_CHARSET);

			switch($this->user_auth) {
			case 'super_admin':
			case 'admin':
				$this->initial_page = DISPATCH_URL . '&amp;module=contents&amp;page=index&amp;method=init';
				break;

			default:
				$this->initial_page = DISPATCH_URL . '&amp;module=article&amp;page=list';
				break;
			}

			$this->view_file = './view/view_index.php';
		}

		function login() {
			$_SESSION['language'] = LANG;

			if($_POST['login']) {
				// Check login
				$auth = new B_AdminAuth;
				$ret = $auth->login($this->db, $_POST['user_id'], $_POST['password']);
				if($ret) {
					// Generate session id
					session_regenerate_id(true);

					// Redirect
					$path = B_SITE_BASE . 'bs-admin/';
					header("Location:$path");
					exit;
				}
				else {
					$this->view_file = './view/view_login_error.php';
				}
			}
			else {
				$this->view_file = './view/view_login.php';
			}
		}

		function checkBrowser() {
			$this->agent = $_SERVER['HTTP_USER_AGENT'];
			if(preg_match('/firefox/i', $_SERVER['HTTP_USER_AGENT'])) return true;
			if(preg_match('/chrome/i', $_SERVER['HTTP_USER_AGENT'])) return true;
			if(preg_match('/safari/i', $_SERVER['HTTP_USER_AGENT'])) return true;
			if(preg_match('/msie 10/i', $_SERVER['HTTP_USER_AGENT'])) return true;
			if(preg_match('/rv:11.0/i', $_SERVER['HTTP_USER_AGENT'])) return true;

			return false;
		}

		function view() {
			// Start buffering
			ob_start();

			require_once($this->view_file);

			// Get buffer
			$contents = ob_get_clean();

			// Send HTTP header
			$this->sendHttpHeader();

			// Show HTML
			echo $contents;
		}
	}
