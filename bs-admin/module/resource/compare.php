<?php
/*
 * B-studio : Content Management System
 * Copyright (c) BigBeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class resource_compare extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

			$this->version_left = $this->global_session['version_left'];
			$this->version_right = $this->global_session['version_right'];

			// Set version info
			$this->version_info = __('Diff Versions Left: %LEFT_VERSION% &nbsp;Right: %RIGHT_VERSION%');
			$this->version_info = str_replace('%LEFT_VERSION%', $this->version_left['version'], $this->version_info);
			$this->version_info = str_replace('%RIGHT_VERSION%', $this->version_right['version'], $this->version_info);
		}

		function view() {
			// Start buffering
			ob_start();

			require_once('./view/view_compare.php');

			// Get buffer
			$contents = ob_get_clean();

			// Send HTTP header
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/resource.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('script', '<script src="js/bframe_splitter.js" type="text/javascript"></script>');

			// Show HTML header
			$this->showHtmlHeader();

			// Show HTML body
			echo $contents;
		}
	}
