<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class template_compare extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

			$this->version_left = $this->global_session['version_left'];
			$this->version_right = $this->global_session['version_right'];

			// Set version info
			$this->version_info = __('Compare Versions Left: %LEFT_VERSION% &nbsp;Right: %RIGHT_VERSION%');
			$this->version_info = str_replace('%LEFT_VERSION%', $this->version_left['version'], $this->version_info);
			$this->version_info = str_replace('%RIGHT_VERSION%', $this->version_right['version'], $this->version_info);
		}

		function view() {
			// HTTPヘッダー出力
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/template.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('script', '<script src="js/bframe_splitter.js" type="text/javascript"></script>');

			// HTMLヘッダー出力
			$this->showHtmlHeader();

			require_once('./view/view_compare.php');
		}
	}
