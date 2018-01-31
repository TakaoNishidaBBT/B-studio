<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class contents_index extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);
		}

		function view() {
			// Start buffering
			ob_start();

			require_once('./view/view_index.php');

			// Get buffer
			$contents = ob_get_clean();

			// Send HTTP header
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link rel="stylesheet" href="css/contents.css">');
			$this->html_header->appendProperty('script', '<script src="js/bframe_splitter.js"></script>');

			// Show HTML header
			$this->showHtmlHeader();

			// Show HTML body
			echo $contents;
		}
	}
