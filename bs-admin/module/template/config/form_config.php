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
		'start_html'	=> '<div class="editor_container bframe_adjustwindow" param="margin:8">',
		'end_html'		=> '</div>',
		array(
			'start_html'	=> '<div id="html" class="bframe_adjustwindow" param="margin:4">',
			'end_html'		=> '</div>',
			array(
				'start_html'	=> '<div class="bframe_adjustparent text_editor start_html" param="margin:11,height:50%">',
				'end_html'		=> '</div>',
				array(
					'name'				=> 'start_html',
					'class'				=> 'B_TextArea',
					'special_html'		=> 'class="bframe_texteditor" param="margin:32"',
					'no_trim'			=> true,
				),
				array(
					'id'				=> 'open_widgetmanager',
					'class'				=> 'B_Link',
					'link'				=> 'index.php',
					'value'				=> 'ウィジェット管理',
					'special_html'		=> 'title="ウィジェット設定" style="display:none"',
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
				'start_html'	=> '<div class="bframe_adjustparent text_editor end_html" param="margin:12,height:50%" style="margin-top:7px">',
				'end_html'		=> '</div>',
				array(
					'name'				=> 'end_html',
					'class'				=> 'B_TextArea',
					'special_html'		=> 'class="bframe_texteditor" param="margin:32"',
					'no_trim'			=> true,
				),
				array(
					'id'				=> 'open_widgetmanager',
					'class'				=> 'B_Link',
					'link'				=> 'index.php',
					'value'				=> 'ウィジェット管理',
					'special_html'		=> 'title="ウィジェット設定" style="display:none"',
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
			'start_html'	=> '<div id="css" class="text_editor bframe_adjustparent" param="margin:21" style="display:none">',
			'end_html'		=> '</div>',
			array(
				'id'				=> 'css_editor',
				'name'				=> 'css',
				'class'				=> 'B_TextArea',
				'special_html'		=> 'class="textarea bframe_adjustparent bframe_texteditor" param="margin:32" syntax="css" style="width:100%"',
				'no_trim'			=> true,
			),
		),
		array(
			'start_html'	=> '<div id="php" class="text_editor bframe_adjustparent" param="margin:21" style="display:none">',
			'end_html'		=> '</div>',
			array(
				'id'				=> 'php_editor',
				'name'				=> 'php',
				'class'				=> 'B_TextArea',
				'special_html'		=> 'class="textarea bframe_adjustparent bframe_texteditor" param="margin:32" syntax="php" style="width:100%"',
				'no_trim'			=> true,
			),
		),
		array(
			'start_html'	=> '<div id="config" class="bframe_adjustparent" param="margin:26" style="display:none">',
			'end_html'		=> '</div>',
			array(
				'name'				=> 'config_form',
			),
		),
		array(
			'start_html'	=> '<div id="preview" class="bframe_adjustparent" param="margin:15" style="display:none">',
			'end_html'		=> '</div>',
			array(
				'start_html'	=> '<iframe id="preview_frame" name="preview_frame" class="bframe_adjustparent" frameborder="0" align="top" scrolling="auto" noresize="noresize" width="100%">',
				'end_html'		=> '</iframe>',
			),
		),
	),
);

$config_form_config = array(
	array(
		// テーブル
		'start_html'	=> '<table class="form" border="0" cellspacing="0" cellpadding="0"><tbody>',
		'end_html'		=> '</tbody></table>',

		// 外部CSS
		array(
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'end_html'				=> '</th>',
				'value'					=> '外部CSS',
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

		// 外部javascript
		array(
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'end_html'				=> '</th>',
				'value'					=> '外部javascript',
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

		// ヘッダー要素
		array(
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'end_html'				=> '</th>',
				'value'					=> 'ヘッダー要素',
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
		'special_html'		=> 'class="bframe_tab"',
		'value'				=> 'HTML',
	),
	array(
		'name'				=> 'css_editor_index',
		'class'				=> 'B_Link',
		'start_html'		=> '<li>',
		'end_html'			=> '</li>',
		'link'				=> 'css',
		'value'				=> 'CSS',
		'special_html'		=> 'class="bframe_tab"',
	),
	array(
		'name'				=> 'php_editor_index',
		'class'				=> 'B_Link',
		'start_html'		=> '<li>',
		'end_html'			=> '</li>',
		'link'				=> 'php',
		'value'				=> 'PHP',
		'special_html'		=> 'class="bframe_tab"',
	),
	array(
		'name'				=> 'config_index',
		'class'				=> 'B_Link',
		'start_html'		=> '<li>',
		'end_html'			=> '</li>',
		'link'				=> 'config',
		'value'				=> '設定',
		'special_html'		=> 'class="bframe_tab"',
	),
	array(
		'name'				=> 'preview_index',
		'class'				=> 'B_Link',
		'start_html'		=> '<li>',
		'end_html'			=> '</li>',
		'link'				=> 'preview',
		'value'				=> 'プレビュー',
		'special_html'		=> 'class="bframe_tab" onclick="bframe.preview.submit(\'F1\', \'' . B_SITE_BASE . 'index.php' . '\', \'template_preview\', \'preview_frame\'); return false;"',
	),
	array(
		'name'				=> 'regist_button',
		'start_html'		=> '<li class="regist">',
		'end_html'			=> '</li>',
		array(
			'start_html'	=> '<div class="input_container">',
			'end_html'		=> '</div>',
			array(
				'name'			=> 'regist',
				'start_html'	=> '<span id="regist" class="regist-button" onclick="bframe.ajaxSubmit.submit(\'F1\', \'' . $this->module . '\', \'form\', \'regist\', \'confirm\', true)">',
				'end_html'		=> '</span>',
				'value'			=> '<img src="images/common/save.png" alt="保存" />保存',
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
