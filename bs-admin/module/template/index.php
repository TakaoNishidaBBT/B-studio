<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class template_index extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);
		}

		function view() {
			// HTTPヘッダー出力
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css','<link href="css/template.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('script','<script src="js/bframe_splitter.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script','<script src="js/bframe_effect.js" type="text/javascript"></script>');

			// HTMLヘッダー出力
			$this->showHtmlHeader();

			require_once('./view/view_index.php');
		}
	}
