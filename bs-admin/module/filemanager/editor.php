<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class filemanager_editor extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

			require_once('./config/editor_config.php');

			$this->editor = new B_Element($editor_config);
			$this->tab_control = new B_Element($tab_control_config);
		}

		function open() {
			if($this->request['node_id']) {
				$file_path = B_Util::getPath(B_UPLOAD_DIR , $this->request['node_id']);
				$info = pathinfo($file_path);
				$update_datetime = filemtime($file_path);
				$contents = file_get_contents($file_path);
				$encoding = mb_detect_encoding($contents, 'UTF-8, EUC-JP, SJIS');
				$obj = $this->editor->getElementByName('contents');
				$obj->value = mb_convert_encoding($contents, 'UTF-8', 'auto');
				switch(strtolower($info['extension'])) {
				case 'js':
					$obj->special_html = str_replace('%SYNTAX%', 'syntax="javascript"', $obj->special_html);
					break;

				case 'css':
					$obj->special_html = str_replace('%SYNTAX%', 'syntax="css"', $obj->special_html);
					break;

				default:
					$obj->special_html = str_replace('%SYNTAX%', '', $obj->special_html);
					break;
				}

				$obj = $this->editor->getElementByName('file_path');
				if($obj) $obj->value = $file_path;

				$obj = $this->editor->getElementByName('extension');
				if($obj) $obj->value = $info['extension'];

				$obj = $this->tab_control->getElementByName('encoding');
				if($obj) $obj->value = $encoding;

				$obj = $this->editor->getElementByName('update_datetime');
				if($obj) $obj->value = $update_datetime;

				$obj = $this->tab_control->getElementByName('text_editor_index');
				if($obj) $obj->value = B_Util::getPath(B_SITE_BASE . B_UPLOAD_FILES, $this->request['node_id']);

				$this->setTitle($info['basename']);
			}
		}

		function register() {
			if(file_exists($this->post['file_path']) && $this->post['mode'] == 'confirm' && filemtime($file_path) > $this->post['update_datetime']) {
				$mode = 'confirm';
				$message = "他のユーザに更新されています。\n上書きしますか？";
			}
			else {
				if($this->post['encoding'] == 'ASCII' || $this->post['encoding'] == 'UTF-8') {
					$contents = $this->post['contents'];
				}
				else {
					$contents = mb_convert_encoding($this->post['contents'], $this->post['encoding'], 'auto');
				}
				file_put_contents($this->post['file_path'], $contents, LOCK_EX);

				$message = "登録しました";
			}

			$response['status'] = true;
			$response['mode'] = $mode;
			$response['message_obj'] = 'message';
			$response['message'] = $message;
			if($mode != 'confirm') {
				$response['values'] = array('update_datetime' => time());
			}

			header('Content-Type: application/x-javascript charset=utf-8');
			echo json_encode($response);
			exit;
		}

		function view() {
			// HTTPヘッダー出力
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/editor.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('css', '<link href="css/texteditor.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('css', '<link href="css/selectbox.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('script', '<script src="js/bframe_edit_check.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_effect.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_texteditor.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_selectbox.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/ace/ace.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/ace/ext-split.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/ace/theme-twilight.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/ace/mode-html.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/ace/mode-css.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/ace/mode-php.js" type="text/javascript"></script>');

			// HTMLヘッダー出力
			$this->showHtmlHeader();

			require_once('./view/view_editor.php');
		}
	}
