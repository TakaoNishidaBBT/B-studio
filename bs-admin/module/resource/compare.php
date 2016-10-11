<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class resource_compare extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

			$this->version_left = $this->global_session['version_left'];
			$this->version_right = $this->global_session['version_right'];
			$this->version_info = _('Compare versions') . '&nbsp;&nbsp;LEFT  : ' . $this->version_left['version'] . '&nbsp;&nbsp;RIGHT : ' . $this->version_right['version'];
		}

		function view() {
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/resource.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('script', '<script src="js/bframe_splitter.js" type="text/javascript"></script>');

			$this->showHtmlHeader();

			require_once('./view/view_compare.php');
		}
	}
