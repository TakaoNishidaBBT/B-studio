<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$form_config = array(
	array('class' => 'B_Hidden', 'name' => 'terminal_id', 'value' => TERMINAL_ID),
	array('class' => 'B_Hidden', 'name' => 'baseHref', 'value' => B_SITE_BASE),
	array('class' => 'B_Hidden', 'name' => 'node_id'),
	array('class' => 'B_Hidden', 'name' => 'contents_id'),
	array('class' => 'B_Hidden', 'name' => 'update_datetime'),
	array(
		'start_html'	=> '<div class="editor_container bframe_adjustwindow" data-param="margin:8" >',
		'end_html'		=> '</div>',
		array(
			'start_html'	=> '<div id="html" class="text_editor bframe_adjustparent" data-param="margin:22">',
			'end_html'		=> '</div>',
			array(
				'start_html'	=> '<div class="bframe_compare" id="compare_html">',
				'end_html'		=> '</div>',
				array(
					'name'			=> 'html_left',
					'class'			=> 'B_Hidden',
					'attr'			=> 'class="bframe_compare_left"',
				),
				array(
					'name'			=> 'html_right',
					'class'			=> 'B_Hidden',
					'attr'			=> 'class="bframe_compare_right"',
				),
				array(
					'start_html'		=> '<div id="compare_html_display_field" class="bframe_compare_display_field" style="height: 100%">',
					'end_html'			=> '</div>',
				),
			),
		),
		array(
			'start_html'	=> '<div id="css" class="text_editor bframe_adjustparent" data-param="margin:22" style="display:none">',
			'end_html'		=> '</div>',
			array(
				'start_html'	=> '<div class="bframe_compare" id="compare_css">',
				'end_html'		=> '</div>',
				array(
					'name'			=> 'css_left',
					'class'			=> 'B_Hidden',
					'attr'			=> 'class="bframe_compare_left"',
				),
				array(
					'name'			=> 'css_right',
					'class'			=> 'B_Hidden',
					'attr'			=> 'class="bframe_compare_right"',
				),
				array(
					'start_html'	=> '<div id="compare_css_display_field" class="bframe_compare_display_field" style="height: 100%">',
					'end_html'		=> '</div>',
				),
			),
		),
		array(
			'start_html'	=> '<div id="php" class="text_editor bframe_adjustparent" data-param="margin:22" style="display:none">',
			'end_html'		=> '</div>',
			array(
				'start_html'	=> '<div class="bframe_compare" id="compare_php">',
				'end_html'		=> '</div>',
				array(
					'name'			=> 'php_left',
					'class'			=> 'B_Hidden',
					'attr'			=> 'class="bframe_compare_left"',
				),
				array(
					'name'			=> 'php_right',
					'class'			=> 'B_Hidden',
					'attr'			=> 'class="bframe_compare_right"',
				),
				array(
					'start_html'	=> '<div id="compare_php_display_field" class="bframe_compare_display_field" style="height: 100%">',
					'end_html'		=> '</div>',
				),
			),
		),
	),
);

//tab control
$tab_control_config = array(
	'start_html'	=> '<ul class="tabcontrol">',
	'end_html'		=> '</ul>',
	array(
		'name'			=> 'html_editor_index',
		'class'			=> 'B_Link',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		'link'			=> 'html',
		'attr'			=> 'class="bframe_tab"',
		'value'			=> 'HTML',
	),
	array(
		'name'			=> 'css_editor_index',
		'class'			=> 'B_Link',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		'link'			=> 'css',
		'value'			=> 'CSS',
		'attr'			=> 'class="bframe_tab"',
	),
	array(
		'name'			=> 'php_editor_index',
		'class'			=> 'B_Link',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		'link'			=> 'php',
		'value'			=> 'PHP',
		'attr'			=> 'class="bframe_tab"',
	),
	array(
		'start_html'	=> '<li class="view-mode">',
		'end_html'		=> '</li>',
		array(
			'start_html'	=> '<span id="unified">',
			'end_html'		=> '</span>',
			'value'			=> '<img src="images/common/splith.png" title="unified" alt="unified" />',
		),
		array(
			'start_html'	=> '<span id="split">',
			'end_html'		=> '</span>',
			'value'			=> '<img src="images/common/splitv.png" title="split" alt="split" />',
		),
		array(
			'value'			=> '<input id="mode" type="hidden" value="s">',
		),
		array(
			'value'			=> '<input id="range" type="checkBox" value="1">',
		),
		array(
			'start_html'	=> '<span id="view-all">',
			'end_html'		=> '</span>',
			'value'			=> '<img src="images/common/view_all.png" title="view all" alt="view all" />',
		),
	),
);
