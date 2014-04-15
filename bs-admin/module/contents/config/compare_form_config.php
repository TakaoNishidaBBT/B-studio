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
				'start_html'	=> '<div class="bframe_compare" id="compare_html">',
				'end_html'		=> '</div>',
				array(
					'name'				=> 'html_left',
					'class'				=> 'B_TextArea',
					'special_html'		=> 'class="textarea bframe_compare_left"',
				),
				array(
					'name'				=> 'html_right',
					'class'				=> 'B_TextArea',
					'special_html'		=> 'class="textarea bframe_compare_right"',
				),
			),
		),
		array(
			'start_html'	=> '<div id="css" class="text_editor bframe_adjustparent" param="margin:22" style="display:none">',
			'end_html'		=> '</div>',
			array(
				'start_html'	=> '<div class="bframe_compare" id="compare_css">',
				'end_html'		=> '</div>',
				array(
					'name'				=> 'css_left',
					'class'				=> 'B_TextArea',
					'special_html'		=> 'class="textarea bframe_compare_left"',
				),
				array(
					'name'				=> 'css_right',
					'class'				=> 'B_TextArea',
					'special_html'		=> 'class="textarea bframe_compare_right"',
				),
			),
		),
		array(
			'start_html'	=> '<div id="php" class="text_editor bframe_adjustparent" param="margin:22" style="display:none">',
			'end_html'		=> '</div>',
			array(
				'start_html'	=> '<div class="bframe_compare" id="compare_php">',
				'end_html'		=> '</div>',
				array(
					'name'				=> 'php_left',
					'class'				=> 'B_TextArea',
					'special_html'		=> 'class="textarea bframe_compare_left"',
				),
				array(
					'name'				=> 'php_right',
					'class'				=> 'B_TextArea',
					'special_html'		=> 'class="textarea bframe_compare_right"',
				),
			),
		),
		array(
			'start_html'	=> '<div id="config" class="bframe_adjustparent" param="margin:30" style="display:none">',
			'end_html'		=> '</div>',
			array(
				'name'				=> 'config_form',
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
			'error_group'	=> true,
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="diff">',
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
					'name'					=> 'title_left',
					'class'					=> 'B_InputText',
					'special_html'			=> 'class="title ime-on" maxlength="100" readonly="readonly"',
					'validate'				=>
					array(
						array(
							'type' 				=> 'match',
							'target' 			=> 'title_right',
						),
					),
				),
			),
			array(
				'start_html'			=> '<td>',
				'end_html'				=> '</td>',
				array(
					'name'					=> 'title_right',
					'class'					=> 'B_InputText',
					'special_html'			=> 'class="title ime-on" maxlength="100" readonly="readonly"',
				),
			),
		),
		// bread crumb 表示名
		array(
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			'error_group'	=> true,
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="diff">',
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
					'name'					=> 'bread_crumb_name_left',
					'class'					=> 'B_InputText',
					'special_html'			=> 'class="bread_crumb ime-on" maxlength="100" ',
					'validate'				=>
					array(
						array(
							'type' 				=> 'match',
							'target' 			=> 'bread_crumb_name_right',
						),
					),
				),
			),
			array(
				'start_html'			=> '<td>',
				'end_html'				=> '</td>',
				array(
					'name'					=> 'bread_crumb_name_right',
					'class'					=> 'B_InputText',
					'special_html'			=> 'class="bread_crumb ime-on" maxlength="100" ',
				),
			),
		),
		// テンプレート
		array(
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			'error_group'	=> true,
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="diff">',
				'end_html'				=> '</th>',
				'value'					=> 'テンプレート',
			),
			array(
				'start_html'		=> '<td>',
				'end_html'			=> '</td>',
				array(
					'name'				=> 'template_name_left',
					'class'				=> 'B_InputText',
					'special_html'		=> 'class="template ime-off" readonly="readonly"',
					'validate'				=>
					array(
						array(
							'type' 				=> 'match',
							'target' 			=> 'template_name_right',
						),
					),
				),
			),
			array(
				'start_html'		=> '<td>',
				'end_html'			=> '</td>',
				array(
					'name'				=> 'template_name_right',
					'class'				=> 'B_InputText',
					'special_html'		=> 'class="template ime-off" readonly="readonly"',
				),
			),
		),
		// keyword
		array(
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			'error_group'	=> true,
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="diff">',
				'end_html'				=> '</th>',
				'value'					=> 'keyword',
			),
			array(
				'start_html'			=> '<td>',
				'end_html'				=> '</td>',
				array(
					'name'					=> 'keyword_left',
					'class'					=> 'B_TextArea',
					'special_html'			=> 'class="keyword ime-on"',
					'validate'				=>
					array(
						array(
							'type' 				=> 'match',
							'target' 			=> 'keyword_right',
						),
					),
				),
			),
			array(
				'start_html'			=> '<td>',
				'end_html'				=> '</td>',
				array(
					'name'					=> 'keyword_right',
					'class'					=> 'B_TextArea',
					'special_html'			=> 'class="keyword ime-on"',
				),
			),
		),
		// description
		array(
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			'error_group'	=> true,
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="diff">',
				'end_html'				=> '</th>',
				'value'					=> 'description',
			),
			array(
				'start_html'			=> '<td>',
				'end_html'				=> '</td>',
				array(
					'name'					=> 'description_left',
					'class'					=> 'B_TextArea',
					'special_html'			=> 'class="description ime-on"',
					'validate'				=>
					array(
						array(
							'type' 				=> 'match',
							'target' 			=> 'description_right',
						),
					),
				),
			),
			array(
				'start_html'			=> '<td>',
				'end_html'				=> '</td>',
				array(
					'name'					=> 'description_right',
					'class'					=> 'B_TextArea',
					'special_html'			=> 'class="description ime-on"',
				),
			),
		),
		// 外部css
		array(
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			'error_group'	=> true,
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="diff">',
				'end_html'				=> '</th>',
				'value'					=> '外部css',
			),
			array(
				'start_html'			=> '<td>',
				'end_html'				=> '</td>',
				array(
					'name'					=> 'external_css_left',
					'class'					=> 'B_TextArea',
					'special_html'			=> 'class="external_css ime-off"',
					'validate'				=>
					array(
						array(
							'type' 				=> 'match',
							'target' 			=> 'external_css_right',
						),
					),
				),
			),
			array(
				'start_html'			=> '<td>',
				'end_html'				=> '</td>',
				array(
					'name'					=> 'external_css_right',
					'class'					=> 'B_TextArea',
					'special_html'			=> 'class="external_css ime-off"',
				),
			),
		),
		// 外部javascript
		array(
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			'error_group'	=> true,
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="diff">',
				'end_html'				=> '</th>',
				'value'					=> '外部javascript',
			),
			array(
				'start_html'			=> '<td>',
				'end_html'				=> '</td>',
				array(
					'name'					=> 'external_js_left',
					'class'					=> 'B_TextArea',
					'special_html'			=> 'class="external_js ime-off"',
					'validate'				=>
					array(
						array(
							'type' 				=> 'match',
							'target' 			=> 'external_js_right',
						),
					),
				),
			),
			array(
				'start_html'			=> '<td>',
				'end_html'				=> '</td>',
				array(
					'name'					=> 'external_js_right',
					'class'					=> 'B_TextArea',
					'special_html'			=> 'class="external_js ime-off"',
				),
			),
		),
		// ヘッダー要素
		array(
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			'error_group'	=> true,
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="diff">',
				'end_html'				=> '</th>',
				'value'					=> 'ヘッダー要素',
			),
			array(
				'start_html'			=> '<td>',
				'end_html'				=> '</td>',
				array(
					'class'					=> 'B_TextArea',
					'name'					=> 'header_element_left',
					'special_html'			=> 'class="header_element"',
					'validate'				=>
					array(
						array(
							'type' 				=> 'match',
							'target' 			=> 'header_element_right',
						),
					),
				),
			),
			array(
				'start_html'			=> '<td>',
				'end_html'				=> '</td>',
				array(
					'class'					=> 'B_TextArea',
					'name'					=> 'header_element_right',
					'special_html'			=> 'class="header_element"',
				),
			),
		),
	),
);
