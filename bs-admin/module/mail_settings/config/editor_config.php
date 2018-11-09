<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$editor_config = array(
	array('class' => 'B_Hidden', 'name' => 'baseHref', 'value' => B_SITE_BASE),
	array('class' => 'B_Hidden', 'name' => 'readOnly', 'value' => ''),
	array('class' => 'B_Hidden', 'name' => 'visual_editor_language', 'value' => $_SESSION['language']),
	array('class' => 'B_Hidden', 'name' => 'visual_editor_styles', 'value' => 'default:' . B_CURRENT_ROOT . 'visualeditor/article1/styles/styles.js'),
	array('class' => 'B_Hidden', 'name' => 'visual_editor_css',	'value' => B_CURRENT_ROOT . 'visualeditor/article1/css/default.css'),
	array('class' => 'B_Hidden', 'name' => 'visual_editor_templates', 'value' => B_CURRENT_ROOT . 'visualeditor/article1/templates/default.js'),

	// File browser
	array(
		'id'			=> 'filebrowser',
		'class'			=> 'B_Link',
		'link'			=> 'index.php',
		'value'			=> __('File manager'),
		'attr'	=> 'style="display:none"',
		'fixedparam'	=>
		array(
			'terminal_id'	=> TERMINAL_ID,
			'module'		=> 'filemanager', 
			'page'			=> 'popup', 
		),
	),

	array(
		// Table
		'start_html'	=> '<table class="editor"><tbody>',
		'end_html'		=> '</tbody></table>',

		// Subject
		array(
			'error_group'	=> true,
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				array(
					'id'			=> 'title-container',
					'start_html'	=> '<div id="title-container">',
					'end_html'		=> '</div>',
					array(
						'name'					=> 'subject',
						'class'					=> 'B_InputText',
						'attr'			=> 'class="subject" maxlength="300" placeholder="' . __('Subject') . '"',
						'validate'				=>
						array(
							array(
								'type' 			=> 'required',
								'error_message'	=> __('Please enter subject'),
							),
						),
					),
					array(
						'name'					=> 'error_message',
						'class'					=> 'B_ErrMsg',
						'start_html'			=> '<p class="error-message">',
						'end_html'				=> '</p>',
					),
				),
			),
		),

		// Text
		array(
			'error_group'	=> true,
			'name'			=> 'contents_row',
			'start_html'	=> '<tr id="texteditor_index">',
			'end_html'		=> '</tr>',
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				array(
					'start_html'	=> '<div class="contents">',
					'end_html'		=> '</div>',
					array(
						'name'			=> 'body',
						'class'			=> 'B_TextArea',
						'attr'	=> 'class="mail-body bframe_contenteditor" data-param="autogrow:true"',
					),
				),
			),
		),

		// Content1
		array(
			'error_group'	=> true,
			'name'			=> 'contents_row',
			'start_html'	=> '<tr id="htmleditor_index" style="display:none">',
			'end_html'		=> '</tr>',
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				array(
					'start_html'	=> '<div class="contents">',
					'end_html'		=> '</div>',
					array(
						'name'			=> 'html',
						'class'			=> 'B_TextArea',
						'attr'	=> 'class="textarea bframe_visualeditor" data-param="container:htmleditor_index,scroller:content"',
					),
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
		'name'				=> 'texteditor_index',
		'class'				=> 'B_Link',
		'start_html'		=> '<li>',
		'end_html'			=> '</li>',
		'link'				=> 'texteditor_index',
		'attr'		=> 'class="bframe_tab"',
		'value'				=> __('Text'),
	),
	array(
		'name'				=> 'htmleditor_index',
		'class'				=> 'B_Link',
		'start_html'		=> '<li>',
		'end_html'			=> '</li>',
		'link'				=> 'htmleditor_index',
		'attr'		=> 'class="bframe_tab"',
		'value'				=> __('HTML'),
	),
);
