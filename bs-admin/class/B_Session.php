<?php
/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	// -------------------------------------------------------------------------
	// class B_Session
	// 
	// -------------------------------------------------------------------------
	class B_Session {
		function start($limiter, $name, $path, $secure=true) {
			if(empty($_SERVER['HTTPS']) === true || $_SERVER['HTTPS'] !== 'on') $secure = false;

			session_cache_limiter($limiter);
			session_name($name);
			session_set_cookie_params(0, $path, null, $secure, true);
			session_start();
		}

		function end() {
			$_SESSION = array();
			if(ini_get('session.use_cookies')) {
				$params = session_get_cookie_params();
				setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure']);
			}
			session_destroy();
		}

		function read($session_name) {
			$session_id = htmlspecialchars($_COOKIE[$session_name], ENT_QUOTES, B_CHARSET);
			$session_file = session_save_path() . '/sess_' . $session_id;

			if(is_file($session_file)) {
				$serializedString = file_get_contents($session_file);
			    $session_array = $this->unserializeSession($serializedString);
			}
			return $session_array;
		}

		function unserializeSession($data) {
			$vars=preg_split('/([a-zA-Z0-9_]+)\|/', $data, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

			for($i=0; $vars[$i]; $i++) {
				$result[$vars[$i++]]=unserialize($vars[$i]);
			}
			return $result;
		}
	}
