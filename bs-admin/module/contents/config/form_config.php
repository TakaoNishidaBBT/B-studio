<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
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
		'start_html'	=> '<div class="editor_container bframe_adjustwindow" param="margin:8" >',
		'end_html'		=> '</div>',
		array(
			'start_html'	=> '<div id="html" class="text_editor bframe_adjustparent" param="margin:22">',
			'end_html'		=> '</div>',
			array(
				'name'				=> 'html1',
				'class'				=> 'B_TextArea',
				'special_html'		=> 'class="textarea bframe_adjustparent bframe_texteditor" param="margin:34" style="width:100%"',
				'no_trim'			=> true,
			),
			array(
				'id'				=> 'open_widgetmanager',
				'class'				=> 'B_Link',
				'link'				=> 'index.php',
				'value'				=> 'ウィジェット',
				'special_html'		=> 'title="ウィジェット" style="display:none"',
				'fixedparam'		=>
				array(
					'terminal_id'	=> TERMINAL_ID,
					'module'		=> 'widget', 
					'page'			=> 'select_tree',
					'target_id'		=> 'html1',
				),
			),
		),
		array(
			'start_html'	=> '<div id="visual" class="visual_editor bframe_adjustparent" param="margin:22" style="display:none">',
			'end_html'		=> '</div>',
			array(
				'start_html'	=> '<iframe id="inline_frame" name="inline_frame" class="bframe_adjustparent" frameborder="0" align="top" scrolling="auto" noresize="noresize" width="100%">',
				'end_html'		=> '</iframe>',
			),
		),
		array(
			'start_html'	=> '<div id="css" class="text_editor bframe_adjustparent" param="margin:22" style="display:none">',
			'end_html'		=> '</div>',
			array(
				'id'				=> 'css_editor',
				'name'				=> 'css',
				'class'				=> 'B_TextArea',
				'special_html'		=> 'class="textarea bframe_adjustparent bframe_texteditor" param="margin:34" syntax="css" style="width:100%"',
				'no_trim'			=> true,
			),
		),
		array(
			'start_html'	=> '<div id="php" class="text_editor bframe_adjustparent" param="margin:22" style="display:none">',
			'end_html'		=> '</div>',
			array(
				'id'				=> 'php_editor',
				'name'				=> 'php',
				'class'				=> 'B_TextArea',
				'special_html'		=> 'class="textarea bframe_adjustparent bframe_texteditor" param="margin:34" syntax="php" style="width:100%"',
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
			'start_html'	=> '<div id="preview" class="bframe_adjustparent" param="margin:22" style="display:none">',
			'end_html'		=> '</div>',
			array(
				'start_html'	=> '<iframe id="preview_frame" name="preview_frame" class="bframe_adjustparent" frameborder="0" align="top" scrolling="auto" noresize="noresize" width="100%">',
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
		'special_html'		=> 'class="bframe_tab" onclick="bframe.inline.blur()"',
		'value'				=> 'HTML',
	),
	array(
		'name'				=> 'visual_editor_index',
		'class'				=> 'B_Link',
		'start_html'		=> '<li>',
		'end_html'			=> '</li>',
		'link'				=> 'visual',
		'special_html'		=> 'class="bframe_tab" onclick="bframe.inline.submit(\'F1\', \'' . B_SITE_ROOT_SSL . 'index.php' . '\', \'inline\', \'inline_frame\'); return false;"',
		'value'				=> 'ビジュアル',
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
		'special_html'		=> 'class="bframe_tab" onclick="bframe.preview.submit(\'F1\', \'' . B_SITE_ROOT_SSL . 'index.php' . '\', \'preview\', \'preview_frame\'); return false;"',
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
				'class'			=> 'B_Button',
				'special_html'	=> 'class="regist-button" onClick="bframe.ajaxSubmit.submit(\'F1\', \'' . $this->module . '\', \'form\', \'regist\', \'confirm\', true)"',
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

$config_form_config = array(
	array(
		// テーブル
		'start_html'	=> '<table class="form" border="0" cellspacing="0" cellpadding="0"><tbody>',
		'end_html'		=> '</tbody></table>',

		// タイトル
		array(
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'end_html'				=> '</th>',
				array(
					'value'				=> 'タイトル',
					'no_linefeed'		=> true,
				),
			),
			array(
				'start_html'			=> '<td>',
				'end_html'				=> '</td>',
				array(
					'name'					=> 'title',
					'class'					=> 'B_InputText',
					'special_html'			=> 'class="title ime-on" maxlength="100" ',
					'no_trim'				=> true,
				),
			),
		),
		// bread crumb 表示名
		array(
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'end_html'				=> '</th>',
				array(
					'value'				=> 'bread crumb 表示名',
					'no_linefeed'		=> true,
				),
			),
			array(
				'start_html'			=> '<td>',
				'end_html'				=> '</td>',
				array(
					'name'					=> 'bread_crumb_name',
					'class'					=> 'B_InputText',
					'special_html'			=> 'class="bread_crumb ime-on" maxlength="100" ',
					'no_trim'				=> true,
				),
			),
		),
		// テンプレート
		array(
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'		=> '<th>',
				'end_html'			=> '</th>',
				'value'				=> 'テンプレート　　',
				array(
					'class'				=> 'B_Link',
					'link'				=> '#',
					'special_html'		=> 'title="クリア" class="button" onclick="clearText(\'template_name\', \'template_id\'); return false;" style="padding:4px 10px;display:inline;"',
					'specialchars'		=> 'none',
					'value'				=> 'クリア',
				),
			),
			array(
				'start_html'		=> '<td>',
				'end_html'			=> '</td>',
				array(
					'name'				=> 'template_name',
					'class'				=> 'B_InputText',
					'special_html'		=> 'class="template ime-off" readonly="readonly"',
				),
				array(
					'class'				=> 'B_Hidden',
					'name'				=> 'template_id',
				),
				array(
					'name'				=> 'open_template',
					'class'				=> 'B_Link',
					'link'				=> 'index.php',
					'special_html'		=> 'title="テンプレート設定" onclick="top.bframe.modalWindow.activate(this, window, \'template_id\'); return false;" params="width:350,height:400"',
					'fixedparam'		=>
					array(
						'terminal_id'		=> TERMINAL_ID,
						'module'			=> 'template', 
						'page'				=> 'select_tree',
					),
					array(
						'value'			=> '<img alt="テンプレート" src="./images/folders/dialog_group.png" width="16" height="16" />',
					),
				),
			),
		),
		// keyword
		array(
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'end_html'				=> '</th>',
				'value'					=> 'keyword',
			),
			array(
				'start_html'			=> '<td>',
				'end_html'				=> '</td>',
				array(
					'name'					=> 'keyword',
					'class'					=> 'B_TextArea',
					'special_html'			=> 'class="keyword ime-on"',
				),
			),
		),
		// description
		array(
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'end_html'				=> '</th>',
				'value'					=> 'description',
			),
			array(
				'start_html'			=> '<td>',
				'end_html'				=> '</td>',
				array(
					'name'					=> 'description',
					'class'					=> 'B_TextArea',
					'special_html'			=> 'class="description ime-on"',
				),
			),
		),
		// 外部css
		array(
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'end_html'				=> '</th>',
				'value'					=> '外部css',
			),
			array(
				'start_html'			=> '<td>',
				'end_html'				=> '</td>',
				array(
					'name'					=> 'external_css',
					'class'					=> 'B_TextArea',
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
					'name'					=> 'external_js',
					'class'					=> 'B_TextArea',
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
