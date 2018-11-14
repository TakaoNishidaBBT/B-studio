<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$html_header_config = array(
	'doc_type'	=> '<!DOCTYPE html>',
	'html'		=> '<html>',
	'meta'		=>
	array(
		'<meta charset="UTF-8">',
		'<meta http-equiv="X-UA-Compatible" content="IE=edge">',
	),
	'script'	=> 
	array(
		'<script src="js/bstudio.js"></script>',
		'<script src="js/bframe.js"></script>',
		'<script src="js/bframe_adjustwindow.js"></script>',
		'<script src="js/bframe_ajax.js"></script>',
		'<script src="js/bframe_context_menu.js"></script>',
		'<script src="js/bframe_popup.js"></script>',
		'<script src="js/bframe_scroll.js"></script>',
		'<script src="js/bframe_textarea.js"></script>',
		'<script src="js/bframe_elastic_table.js"></script>',
	),
	'css'		=>
	array(
		'<link rel="stylesheet" href="css/common.css">',
		'<link rel="stylesheet" href="css/context_menu.css">',
	),
	'title'		=> B_SYSTEM_NAME,
);
