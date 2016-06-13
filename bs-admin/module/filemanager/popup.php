<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class filemanager_popup extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

			$this->dir = B_UPLOAD_DIR;

			$this->_setProperty('target', '');
			$this->_setProperty('target_id', '');

			require_once('./config/popup_tree_config.php');
			$this->tree = new B_FileNode($this->dir, '');
			$this->tree->setConfig($tree_config);

			if(is_array($_FILES['upload'])) {
				$response['CKEditorFuncNum'] = $this->request['CKEditorFuncNum'];
				$response['url'] = '';
				$response['message'] = 'サーバブラウザを使用してください';

				// HTTPヘッダー出力
				$this->sendHttpHeader();

				// HTMLヘッダー出力
				$this->showHtmlHeader();

				require_once('./view/view_quick_upload.php');
				exit;
			}
		}

		function open() {
			// target
			$this->_setRequest('target');
			$this->_setRequest('target_id');
		}

		function view() {
			// HTTPヘッダー出力
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/filemanager_tree.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('css', '<link href="css/filemanager.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('css', '<link href="css/upload.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('script', '<script src="js/bframe_tree.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_dialog.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_splitter.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_effect.js" type="text/javascript"></script>');
//			$this->html_header->appendProperty('script', '<script src="js/bframe_modal_window.js" type="text/javascript"></script>');

			// HTMLヘッダー出力
			$this->showHtmlHeader();

			require_once('./view/view_index.php');
		}
	}
