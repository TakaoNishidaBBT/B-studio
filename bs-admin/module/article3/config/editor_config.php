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
	array('class' => 'B_Hidden', 'name' => 'visual_editor_styles', 'value' => 'default:' . B_CURRENT_ROOT . 'visualeditor/article3/styles/styles.js'),
	array('class' => 'B_Hidden', 'name' => 'visual_editor_css',	'value' => B_CURRENT_ROOT . 'visualeditor/article3/css/default.css'),
	array('class' => 'B_Hidden', 'name' => 'visual_editor_templates', 'value' => B_CURRENT_ROOT . 'visualeditor/article3/templates/default.js'),

	// File browser
	array(
		'id'			=> 'filebrowser',
		'class'			=> 'B_Link',
		'link'			=> 'index.php',
		'value'			=> __('File manager'),
		'special_html'	=> 'style="display:none"',
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

		// Title
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
						'name'					=> 'title',
						'class'					=> 'B_TextArea',
						'special_html'			=> 'class="textarea title ime_on bframe_textarea" maxlength="300" placeholder="' . __('Title') . '"',
						'validate'				=>
						array(
							array(
								'type' 			=> 'required',
								'error_message'	=> __('Please enter title'),
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

		// Content1
		array(
			'error_group'	=> true,
			'name'			=> 'contents_row',
			'start_html'	=> '<tr id="content1_index">',
			'end_html'		=> '</tr>',
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				array(
					'start_html'	=> '<div class="contents">',
					'end_html'		=> '</div>',
					array(
						'name'			=> 'content1',
						'class'			=> 'B_TextArea',
						'special_html'	=> 'class="textarea bframe_visualeditor" data-param="container:content1_index,scroller:content,bodyclass:content1"',
					),
				),
			),
		),

		// Content2
		array(
			'error_group'	=> true,
			'name'			=> 'contents_row',
			'start_html'	=> '<tr id="content2_index" style="display:none">',
			'end_html'		=> '</tr>',
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				array(
					'start_html'	=> '<div class="contents">',
					'end_html'		=> '</div>',
					array(
						'name'			=> 'content2',
						'class'			=> 'B_TextArea',
						'special_html'	=> 'class="textarea bframe_visualeditor" data-param="container:content2_index,scroller:content,bodyclass:content2"',
					),
				),
			),
		),

		// Content3
		array(
			'error_group'	=> true,
			'name'			=> 'contents_row',
			'start_html'	=> '<tr id="content3_index" style="display:none">',
			'end_html'		=> '</tr>',
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				array(
					'start_html'	=> '<div class="contents">',
					'end_html'		=> '</div>',
					array(
						'name'			=> 'content3',
						'class'			=> 'B_TextArea',
						'special_html'	=> 'class="textarea bframe_visualeditor" data-param="container:content3_index,scroller:content,bodyclass:content3"',
					),
				),
			),
		),

		// Content4
		array(
			'error_group'	=> true,
			'name'			=> 'contents_row',
			'start_html'	=> '<tr id="content4_index" style="display:none">',
			'end_html'		=> '</tr>',
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				array(
					'start_html'	=> '<div class="contents">',
					'end_html'		=> '</div>',
					array(
						'name'			=> 'content4',
						'class'			=> 'B_TextArea',
						'special_html'	=> 'class="textarea bframe_visualeditor" data-param="container:content4_index,scroller:content,bodyclass:content4"',
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
		'name'				=> 'content1_index',
		'class'				=> 'B_Link',
		'start_html'		=> '<li>',
		'end_html'			=> '</li>',
		'link'				=> 'content1_index',
		'special_html'		=> 'class="bframe_tab"',
		'value'				=> __('Content1'),
	),
	array(
		'name'				=> 'content2_index',
		'class'				=> 'B_Link',
		'start_html'		=> '<li>',
		'end_html'			=> '</li>',
		'link'				=> 'content2_index',
		'special_html'		=> 'class="bframe_tab"',
		'value'				=> __('Content2'),
	),
	array(
		'name'				=> 'content3_index',
		'class'				=> 'B_Link',
		'start_html'		=> '<li>',
		'end_html'			=> '</li>',
		'link'				=> 'content3_index',
		'special_html'		=> 'class="bframe_tab"',
		'value'				=> __('Content3'),
	),
	array(
		'name'				=> 'content4_index',
		'class'				=> 'B_Link',
		'start_html'		=> '<li>',
		'end_html'			=> '</li>',
		'link'				=> 'content4_index',
		'special_html'		=> 'class="bframe_tab"',
		'value'				=> __('Content4'),
	),
);
