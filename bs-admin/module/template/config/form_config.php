<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$form_config = array(
	array('class' => 'B_Hidden', 'name' => 'node_id'),
	array('class' => 'B_Hidden', 'name' => 'contents_id'),
	array('class' => 'B_Hidden', 'name' => 'update_datetime'),
	array(
		'start_html'	=> '<div class="editor_container bframe_adjustwindow" data-param="margin:8">',
		'end_html'		=> '</div>',
		array(
			'start_html'	=> '<div id="html" class="bframe_adjustwindow" data-param="margin:4">',
			'end_html'		=> '</div>',
			array(
				'start_html'	=> '<div class="bframe_adjustparent text_editor start_html" data-param="margin:11,height:50%">',
				'end_html'		=> '</div>',
				array(
					'name'				=> 'start_html',
					'class'				=> 'B_TextArea',
					'special_html'		=> 'class="bframe_texteditor" data-param="margin:32"',
					'no_trim'			=> true,
				),
				array(
					'class'				=> 'B_Link',
					'link'				=> 'index.php',
					'value'				=> __('Widgets'),
					'special_html'		=> 'class="open_widgetmanager" title="' . __('Widgets') . '" style="display:none"',
					'fixedparam'		=>
					array(
						'terminal_id'	=> TERMINAL_ID,
						'module'		=> 'widget', 
						'page'			=> 'select_tree',
						'target_id'		=> 'start_html',
					),
				),
			),
			array(
				'start_html'	=> '<div class="bframe_adjustparent text_editor end_html" data-param="margin:12,height:50%" style="margin-top:7px">',
				'end_html'		=> '</div>',
				array(
					'name'				=> 'end_html',
					'class'				=> 'B_TextArea',
					'special_html'		=> 'class="bframe_texteditor" data-param="margin:32"',
					'no_trim'			=> true,
				),
				array(
					'class'				=> 'B_Link',
					'link'				=> 'index.php',
					'value'				=> __('Widgets'),
					'special_html'		=> 'class="open_widgetmanager" title="' . __('Widgets') . '" style="display:none"',
					'fixedparam'		=>
					array(
						'terminal_id'	=> TERMINAL_ID,
						'module'		=> 'widget', 
						'page'			=> 'select_tree',
						'target_id'		=> 'end_html',
					),
				),
			),
		),
		array(
			'start_html'	=> '<div id="css" class="text_editor bframe_adjustparent" data-param="margin:21" style="display:none">',
			'end_html'		=> '</div>',
			array(
				'id'				=> 'css_editor',
				'name'				=> 'css',
				'class'				=> 'B_TextArea',
				'special_html'		=> 'class="textarea bframe_adjustparent bframe_texteditor" data-param="margin:32" data-syntax="css" style="width:100%"',
				'no_trim'			=> true,
			),
		),
		array(
			'start_html'	=> '<div id="php" class="text_editor bframe_adjustparent" data-param="margin:21" style="display:none">',
			'end_html'		=> '</div>',
			array(
				'id'				=> 'php_editor',
				'name'				=> 'php',
				'class'				=> 'B_TextArea',
				'special_html'		=> 'class="textarea bframe_adjustparent bframe_texteditor" data-param="margin:32" data-syntax="php" style="width:100%"',
				'no_trim'			=> true,
			),
			array(
				'id'				=> 'open_widgetmanager',
				'class'				=> 'B_Link',
				'link'				=> 'index.php',
				'value'				=> __('Widgets'),
				'special_html'		=> 'title="' . __('Widgets') . '" style="display:none"',
				'fixedparam'		=>
				array(
					'terminal_id'	=> TERMINAL_ID,
					'module'		=> 'widget', 
					'page'			=> 'select_tree',
					'target_id'		=> 'start_html',
				),
			),
		),
		array(
			'name'			=> 'settings_form',
			'start_html'	=> '<div id="settings" class="bframe_adjustparent" data-param="margin:25" style="display:none">',
			'end_html'		=> '</div>',
		),
		array(
			'start_html'	=> '<div id="preview" class="bframe_adjustparent" data-param="margin:15" style="display:none">',
			'end_html'		=> '</div>',
			array(
				'start_html'	=> '<iframe id="preview_frame" name="preview_frame" class="bframe_adjustparent">',
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
		'name'				=> 'html_editor_index',
		'class'				=> 'B_Link',
		'start_html'		=> '<li>',
		'end_html'			=> '</li>',
		'link'				=> 'html',
		'value'				=> __('HTML'),
		'special_html'		=> 'class="bframe_tab"',
	),
	array(
		'name'				=> 'css_editor_index',
		'class'				=> 'B_Link',
		'start_html'		=> '<li>',
		'end_html'			=> '</li>',
		'link'				=> 'css',
		'value'				=> __('CSS'),
		'special_html'		=> 'class="bframe_tab"',
	),
	array(
		'name'				=> 'php_editor_index',
		'class'				=> 'B_Link',
		'start_html'		=> '<li>',
		'end_html'			=> '</li>',
		'link'				=> 'php',
		'value'				=> __('PHP'),
		'special_html'		=> 'class="bframe_tab"',
	),
	array(
		'name'				=> 'settings_index',
		'class'				=> 'B_Link',
		'start_html'		=> '<li>',
		'end_html'			=> '</li>',
		'link'				=> 'settings',
		'value'				=> __('Settings'),
		'special_html'		=> 'class="bframe_tab"',
	),
	array(
		'name'				=> 'preview_index',
		'class'				=> 'B_Link',
		'start_html'		=> '<li>',
		'end_html'			=> '</li>',
		'link'				=> 'preview',
		'value'				=> __('Preview'),
		'special_html'		=> 'class="bframe_tab" onclick="bframe.preview.submit(\'F1\', \'' . B_SITE_BASE . 'index.php' . '\', \'template_preview\', \'preview_frame\'); return false;"',
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
			'start_html'	=> '<div class="message-container">',
			'end_html'		=> '</div>',
			array(
				'start_html'	=> '<span id="message">',
				'end_html'		=> '</span>',
			),
		),
	),
);

$settings_form_config = array(
	array(
		// Table
		'start_html'	=> '<table class="form"><tbody>',
		'end_html'		=> '</tbody></table>',

		// External CSS
		array(
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'			=> '<th>',
				'end_html'				=> '</th>',
				'value'					=> __('External css'),
			),
			array(
				'start_html'			=> '<td>',
				'end_html'				=> '</td>',
				array(
					'class'					=> 'B_TextArea',
					'name'					=> 'external_css',
					'special_html'			=> 'class="external_css ime-off"',
				),
			),
		),

		// External javascript
		array(
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'			=> '<th>',
				'end_html'				=> '</th>',
				'value'					=> __('External javascript'),
			),
			array(
				'start_html'			=> '<td>',
				'end_html'				=> '</td>',
				array(
					'class'					=> 'B_TextArea',
					'name'					=> 'external_js',
					'special_html'			=> 'class="external_js ime-off"',
				),
			),
		),

		// Header Elements
		array(
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'			=> '<th>',
				'end_html'				=> '</th>',
				'value'					=> __('Header elements'),
			),
			array(
				'start_html'			=> '<td>',
				'end_html'				=> '</td>',
				array(
					'class'					=> 'B_TextArea',
					'name'					=> 'header_element',
					'special_html'			=> 'class="header_element"',
				),
			),
		),
	),
);
