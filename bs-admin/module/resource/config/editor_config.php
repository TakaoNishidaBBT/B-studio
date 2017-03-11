<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$editor_config = array(
	array('class' => 'B_Hidden', 'name' => 'node_id'),
	array('class' => 'B_Hidden', 'name' => 'contents_id'),
	array('class' => 'B_Hidden', 'name' => 'extension'),
	array('class' => 'B_Hidden', 'name' => 'update_datetime'),
	array(
		'start_html'	=> '<div class="editor_container bframe_adjustwindow" data-param="margin:16" >',
		'end_html'		=> '</div>',
		array(
			'start_html'	=> '<div id="text_editor" class="text_editor bframe_adjustparent" data-param="margin:12">',
			'end_html'		=> '</div>',
			array(
				'name'				=> 'contents',
				'class'				=> 'B_TextArea',
				'special_html'		=> 'class="textarea bframe_adjustparent bframe_texteditor" data-param="margin:32" %SYNTAX% style="width:100%"',
				'no_trim'			=> true,
			),
		),
	),
);

//Tab control
$tab_control_config = array(
	'start_html'	=> '<ul class="tabcontrol">',
	'end_html'		=> '</ul>',
	array(
		'name'				=> 'text_editor_index',
		'start_html'		=> '<li class="filename">',
		'end_html'			=> '</li>',
		'special_html'		=> 'class="bframe_tab"',
	),
	array(
		'start_html'		=> '<li class="encoding">',
		'end_html'			=> '</li>',
		'name'				=> 'encoding',
		'class'				=> 'B_SelectBox',
		'data_set'			=> 'encoding',
		'special_html'		=> 'class="bframe_selectbox"',
	),
	array(
		'start_html'		=> '<li class="register">',
		'end_html'			=> '</li>',
		array(
			'start_html'	=> '<div class="input_container">',
			'end_html'		=> '</div>',
			array(
				'name'			=> 'register',
				'start_html'	=> '<span id="register" class="register-button" onclick="bstudio.registerEditor(\'F1\', \'' . $this->module . '\', \'editor\', \'register\', \'confirm\', true)">',
				'end_html'		=> '</span>',
				'value'			=> '<img src="images/common/save.png" alt="Save" />' . __('Save'),
			),
		),
		array(
			'start_html'	=> '<div class="message_container">',
			'end_html'		=> '</div>',
			array(
				'start_html'	=> '<span id="message">',
				'end_html'		=> '</span>',
			),
		),
	),
);
