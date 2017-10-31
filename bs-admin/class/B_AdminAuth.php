<?php
/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	// -------------------------------------------------------------------------
	// class B_AdminAuth
	// 
	// -------------------------------------------------------------------------
	class B_AdminAuth {
		function login($db, $user_id, $pwd) {
			global $g_auth_users;
			foreach($g_auth_users as $value) {
				if($value['user_id'] === $user_id && $value['pwd'] === md5($pwd)) {
					$_SESSION['user_id'] = $value['user_id'];
					$_SESSION['user_name'] = $value['user_name'];
					$_SESSION['user_auth'] = $value['user_auth'];
					$_SESSION['language'] = $value['language'];
					return true;
				}
			}
			$sql = " select user_id, user_name, pwd, user_auth, language from " . B_DB_PREFIX . "user";
			$sql.= " where user_status = '1' and user_id = binary '%USER_ID%' and pwd = binary '%PWD%'";

			$sql = str_replace('%USER_ID%', $db->real_escape_string($user_id), $sql);
			$sql = str_replace('%PWD%', $db->real_escape_string($pwd), $sql);

			$rs = $db->query($sql);
			$row = $db->fetch_assoc($rs);
			if($row) {
				$_SESSION['user_id'] = $row['user_id'];
				$_SESSION['user_name'] = $row['user_name'];
				$_SESSION['user_auth'] = $row['user_auth'];
				$_SESSION['language'] = $row['language'];
				return true;
			}
			return false;
		}

		function checkUserAuth() {
			if($_SESSION['user_id'] && $_SESSION['user_name'] && $_SESSION['user_auth']) {
				return true;
			}
			return false;
		}

		function getUserInfo(&$user_id, &$user_name, &$user_auth, &$language) {
			if(!$_SESSION['user_id'] || !$_SESSION['user_name'] || !$_SESSION['user_auth']) {
				return false;
			}
			$user_id = $_SESSION['user_id'];
			$user_name = $_SESSION['user_name'];
			$user_auth = $_SESSION['user_auth'];
			$language = $_SESSION['language'];
			return true;
		}
	}
