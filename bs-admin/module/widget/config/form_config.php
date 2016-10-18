<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$form_config = array(
	array('class' => 'B_Hidden', 'name' => 'node_id'),
	array('class' => 'B_Hidden', 'name' => 'contents_id'),
	array('class' => 'B_Hidden', 'name' => 'update_datetime'),
	array(
		'start_html'	=> '<div class="editor_container bframe_adjustwindow" param="margin:14" >',
		'end_html'		=> '</div>',
		array(
			'start_html'	=> '<div id="html" class="text_editor bframe_adjustparent" param="margin:15">',
			'end_html'		=> '</div>',
			array(
				'id'			=> 'html_editor',
				'name'			=> 'html',
				'class'			=> 'B_TextArea',
				'special_html'	=> 'class="textarea bframe_adjustparent bframe_texteditor" param="margin:32" style="width:100%"',
				'no_trim'		=> true,
			),
		),
		array(
			'start_html'	=> '<div id="css" class="text_editor bframe_adjustparent" param="margin:15" style="display:none">',
			'end_html'		=> '</div>',
			array(
				'id'			=> 'css_editor',
				'name'			=> 'css',
				'class'			=> 'B_TextArea',
				'special_html'	=> 'class="textarea bframe_adjustparent bframe_texteditor" param="margin:32" syntax="css" style="width:100%"',
				'no_trim'		=> true,
			),
		),
		array(
			'start_html'	=> '<div id="php" class="text_editor bframe_adjustparent" param="margin:15" style="display:none">',
			'end_html'		=> '</div>',
			array(
				'id'			=> 'php_editor',
				'name'			=> 'php',
				'class'			=> 'B_TextArea',
				'special_html'	=> 'class="textarea bframe_adjustparent bframe_texteditor" param="margin:32" syntax="php" style="width:100%"',
				'no_trim'		=> true,
			),
		),
		array(
			'start_html'	=> '<div id="preview" class="bframe_adjustparent" param="margin:16" style="display:none">',
			'end_html'		=> '</div>',
			array(
				'start_html'	=> '<iframe id="preview_frame" name="preview_frame" class="bframe_adjustparent" frameborder="0" align="top" scrolling="auto" noresize="noresize" width="100%" height="500px">',
				'end_html'		=> '</iframe>',
			),
		),
	),
);

//tab control
$tab_control_config = array(
	'start_html'	=> '<ul class="tabcontrol">',
	'end_html'		=> '</ul>',
	array(
		'name'				=> 'html_index',
		'class'				=> 'B_Link',
		'start_html'		=> '<li>',
		'end_html'			=> '</li>',
		'link'				=> 'html',
		'value'				=> __('HTML'),
		'special_html'		=> 'class="bframe_tab"',
	),
	array(
		'name'				=> 'css_index',
		'class'				=> 'B_Link',
		'start_html'		=> '<li>',
		'end_html'			=> '</li>',
		'link'				=> 'css',
		'value'				=> __('CSS'),
		'special_html'		=> 'class="bframe_tab"',
	),
	array(
		'name'				=> 'php_index',
		'class'				=> 'B_Link',
		'start_html'		=> '<li>',
		'end_html'			=> '</li>',
		'link'				=> 'php',
		'value'				=> __('PHP'),
		'special_html'		=> 'class="bframe_tab"',
	),
	array(
		'name'				=> 'preview_index',
		'class'				=> 'B_Link',
		'start_html'		=> '<li>',
		'end_html'			=> '</li>',
		'link'				=> 'preview',
		'value'				=> __('Preview'),
		'special_html'		=> 'class="bframe_tab" onclick="bframe.preview.submit(\'F1\', \'' . B_SITE_BASE . 'index.php' . '\', \'widget_preview\', \'preview_frame\'); return false;"',
	),
	array(
		'name'				=> 'register_button',
		'start_html'		=> '<li class="register">',
		'end_html'			=> '</li>',
		array(
			'start_html'	=> '<div class="input_container">',
			'end_html'		=> '</div>',
			array(
				'name'			=> 'register',
				'start_html'	=> '<span id="register" class="register-button" onclick="bframe.ajaxSubmit.submit(\'F1\', \'' . $this->module . '\', \'form\', \'register\', \'confirm\', true)">',
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
