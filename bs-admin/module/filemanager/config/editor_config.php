<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$editor_config = array(
	array('class' => 'B_Hidden', 'name' => 'file_path'),
	array('class' => 'B_Hidden', 'name' => 'update_datetime'),
	array(
		'start_html'	=> '<div class="editor_container bframe_adjustwindow" param="margin:8" >',
		'end_html'		=> '</div>',
		array(
			'start_html'	=> '<div id="text_editor" class="text_editor bframe_adjustparent" param="margin:22">',
			'end_html'		=> '</div>',
			array(
				'name'				=> 'contents',
				'class'				=> 'B_TextArea',
				'special_html'		=> 'class="textarea bframe_adjustparent bframe_texteditor" param="margin:34" %SYNTAX% style="width:100%"',
				'no_trim'			=> true,
			),
		),
	),
);

//tab control
$tab_control_config = array(
	'start_html'	=> '<ul class="tabcontrol">',
	'end_html'		=> '</ul>',
	array(
		'name'				=> 'text_editor_index',
		'class'				=> 'B_Link',
		'start_html'		=> '<li class="filename">',
		'end_html'			=> '</li>',
		'link'				=> 'text_editor',
		'special_html'		=> 'class="bframe_tab"',
	),
	array(
		'start_html'		=> '<li class="encoding">',
		'end_html'			=> '</li>',
		'name'				=> 'encoding',
		'class'				=> 'B_SelectBox',
		'data_set'			=> 'encoding',
	),
	array(
		'start_html'		=> '<li class="regist">',
		'end_html'			=> '</li>',
		array(
			'start_html'	=> '<div class="input_container">',
			'end_html'		=> '</div>',
			array(
				'name'			=> 'regist',
				'class'			=> 'B_Button',
				'special_html'	=> 'class="regist-button" onClick="bframe.ajaxSubmit.submit(\'F1\', \'' . $this->module . '\', \'editor\', \'regist\', \'confirm\', true)"',
				'value'			=> '　登録　',
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
