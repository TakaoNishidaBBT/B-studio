<?php
/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	// -------------------------------------------------------------------------
	// class B_UserAuth
	// 
	// -------------------------------------------------------------------------
	class B_UserAuth {
		function getUserInfo(&$user_id, &$user_name) {
			if(!$_SESSION['user_id'] || !$_SESSION['user_name']) {
				return false;
			}
			$user_id = $_SESSION['user_id'];
			$user_name = $_SESSION['user_name'];
			return true;
		}

		function login($db, $login_id, $pwd) {
			$sql = "select user_id
						  ,user_name
					from " . B_DB_PREFIX . "account
					where user_id = binary '%login_id%' and pwd = binary '%pwd%'";

			$sql = str_replace('%login_id%', $db->real_escape_string($login_id), $sql);
			$sql = str_replace('%pwd%', $db->real_escape_string($pwd), $sql);

			$rs = $db->query($sql);
			$row = $db->fetch_assoc($rs);
			if($row) {
				$_SESSION['user_id'] = $row['user_id'];
				$_SESSION['user_name'] = $row['user_name'];
				return true;
			}
			return false;
		}
	}
