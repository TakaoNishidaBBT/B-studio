<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$html_header_config = array(
	'doc_type'	=> '<!DOCTYPE html>',
	'html'		=> '<html>',
	'meta'		=>
	array(
	),
	'script'	=> 
	array(
		'<script src="js/jquery/jquery.min.js" type="text/javascript"></script>',
		'<script src="js/utility.js" type="text/javascript"></script>',
		'<script src="js/bframe.js" type="text/javascript"></script>',
		'<script src="js/bframe_adjustwindow.js" type="text/javascript"></script>',
		'<script src="js/bframe_ajax.js" type="text/javascript"></script>',
		'<script src="js/bframe_context_menu.js" type="text/javascript"></script>',
		'<script src="js/bframe_popup.js" type="text/javascript"></script>',
	),
	'css'		=>
	array(
		'<link href="css/common.css" type="text/css" rel="stylesheet" media="all" />',
		'<link href="css/context_menu.css" type="text/css" rel="stylesheet" media="all" />',
	),
	'title'		=> B_SYSTEM_NAME,
);
