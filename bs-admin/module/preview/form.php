<?php
/*
 * B-studio : Content Management System
 * Copyright (c) BigBeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class preview_form extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);
		}

		function view() {
			// Start buffering
			ob_start();

			require_once('./view/view_form.php');

			// Get buffer
			$contents = ob_get_clean();

			// Send HTTP header
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/preview.css" type="text/css" rel="stylesheet" media="all" />');

			// Show HTML header
			$this->showHtmlHeader();

			// Show HTML body
			echo $contents;
		}
	}
