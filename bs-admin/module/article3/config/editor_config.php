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
	array('class' => 'B_Hidden', 'name' => 'visual_editor_body_class', 'value' => 'contents'),
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

		// Details
		array(
			'error_group'	=> true,
			'name'			=> 'contents_row',
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				array(
					'start_html'	=> '<div class="contents">',
					'end_html'		=> '</div>',
					array(
						'name'			=> 'contents',
						'class'			=> 'B_TextArea',
						'special_html'	=> 'class="textarea bframe_visualeditor" data-param="scroller:content" style="height:1000px"',
					),
				),
			),
		),
	),
);
