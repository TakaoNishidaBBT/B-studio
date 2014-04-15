<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	require_once('core_config.php');

	// ログインユーザ定義ファイル
	require_once(B_DOC_ROOT . B_ADMIN_ROOT . 'user/users.php');

	// クラスファイル
	require_once(B_DOC_ROOT . B_ADMIN_ROOT . 'class/B_AdminAuth.php');
	require_once(B_DOC_ROOT . B_ADMIN_ROOT . 'class/B_Article.php');
	require_once(B_DOC_ROOT . B_ADMIN_ROOT . 'class/B_Calendar.php');
	require_once(B_DOC_ROOT . B_ADMIN_ROOT . 'class/B_Controller.php');
	require_once(B_DOC_ROOT . B_ADMIN_ROOT . 'class/B_DataGrid.php');
	if(class_exists('mysqli')) {
		require_once(B_DOC_ROOT . B_ADMIN_ROOT . 'class/B_DBaccess_mysqli.php');
	}
	else {
		require_once(B_DOC_ROOT . B_ADMIN_ROOT . 'class/B_DBaccess.php');
	}
	require_once(B_DOC_ROOT . B_ADMIN_ROOT . 'class/B_Element.php');
	require_once(B_DOC_ROOT . B_ADMIN_ROOT . 'class/B_FileNode.php');
	require_once(B_DOC_ROOT . B_ADMIN_ROOT . 'class/B_HtmlHeader.php');
	require_once(B_DOC_ROOT . B_ADMIN_ROOT . 'class/B_Log.php');
	require_once(B_DOC_ROOT . B_ADMIN_ROOT . 'class/B_Mail.php');
	require_once(B_DOC_ROOT . B_ADMIN_ROOT . 'class/B_Module.php');
	require_once(B_DOC_ROOT . B_ADMIN_ROOT . 'class/B_Node.php');
	require_once(B_DOC_ROOT . B_ADMIN_ROOT . 'class/B_Session.php');
	require_once(B_DOC_ROOT . B_ADMIN_ROOT . 'class/B_Table.php');
	require_once(B_DOC_ROOT . B_ADMIN_ROOT . 'class/B_UserAuth.php');
	require_once(B_DOC_ROOT . B_ADMIN_ROOT . 'class/B_Util.php');
	require_once(B_DOC_ROOT . B_ADMIN_ROOT . 'class/B_VNode.php');
