<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$form_config = array(
	array('class' => 'B_Hidden', 'name' => 'node_id'),
	array('class' => 'B_Hidden', 'name' => 'template_id'),
	array(
		'start_html'	=> '<div class="editor_container bframe_adjustwindow" data-param="margin:8" >',
		'end_html'		=> '</div>',
		array(
			'start_html'	=> '<div id="html" class="bframe_adjustwindow" data-param="margin:4">',
			'end_html'		=> '</div>',
			array(
				'start_html'	=> '<div class="bframe_adjustparent bframe_compare start_html" data-param="margin:11,height:50%">',
				'end_html'		=> '</div>',
				array(
					'name'			=> 'start_html_left',
					'class'			=> 'B_Hidden',
					'attr'			=> 'class="bframe_compare_left"',
				),
				array(
					'name'			=> 'start_html_right',
					'class'			=> 'B_Hidden',
					'attr'			=> 'class="bframe_compare_right"',
				),
				array(
					'start_html'		=> '<div id="compare_start_html_display_field" class="bframe_compare_display_field">',
					'end_html'			=> '</div>',
				),
			),
			array(
				'start_html'	=> '<div class="bframe_adjustparent bframe_compare end_html" data-param="margin:12,height:50%" style="margin-top:7px">',
				'end_html'		=> '</div>',
				array(
					'name'			=> 'end_html_left',
					'class'			=> 'B_Hidden',
					'attr'			=> 'class="bframe_compare_left"',
				),
				array(
					'name'			=> 'end_html_right',
					'class'			=> 'B_Hidden',
					'attr'			=> 'class="bframe_compare_right"',
				),
				array(
					'start_html'	=> '<div id="compare_end_html_display_field" class="bframe_compare_display_field">',
					'end_html'		=> '</div>',
				),
			),
		),
		array(
			'start_html'	=> '<div id="css" class="text_editor bframe_adjustparent" data-param="margin:22" style="display:none">',
			'end_html'		=> '</div>',
			array(
				'start_html'	=> '<div class="bframe_compare" id="compare_css">',
				'end_html'		=> '</div>',
				array(
					'name'			=> 'css_left',
					'class'			=> 'B_Hidden',
					'attr'			=> 'class="bframe_compare_left"',
				),
				array(
					'name'			=> 'css_right',
					'class'			=> 'B_Hidden',
					'attr'			=> 'class="bframe_compare_right"',
				),
				array(
					'start_html'	=> '<div id="compare_css_display_field" class="bframe_compare_display_field" style="height: 100%">',
					'end_html'		=> '</div>',
				),
			),
		),
		array(
			'start_html'	=> '<div id="php" class="text_editor bframe_adjustparent" data-param="margin:22" style="display:none">',
			'end_html'		=> '</div>',
			array(
				'start_html'	=> '<div class="bframe_compare" id="compare_php">',
				'end_html'		=> '</div>',
				array(
					'name'			=> 'php_left',
					'class'			=> 'B_Hidden',
					'attr'			=> 'class="bframe_compare_left"',
				),
				array(
					'name'			=> 'php_right',
					'class'			=> 'B_Hidden',
					'attr'			=> 'class="bframe_compare_right"',
				),
				array(
					'start_html'	=> '<div id="compare_php_display_field" class="bframe_compare_display_field" style="height: 100%">',
					'end_html'		=> '</div>',
				),
			),
		),
		array(
			'start_html'	=> '<div id="config" class="bframe_adjustparent" data-param="margin:30" style="display:none">',
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
		'name'			=> 'html_editor_index',
		'class'			=> 'B_Link',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		'link'			=> 'html',
		'attr'			=> 'class="bframe_tab"',
		'value'			=> 'HTML',
	),
	array(
		'name'			=> 'css_editor_index',
		'class'			=> 'B_Link',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		'link'			=> 'css',
		'value'			=> 'CSS',
		'attr'			=> 'class="bframe_tab"',
	),
	array(
		'name'			=> 'php_editor_index',
		'class'			=> 'B_Link',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		'link'			=> 'php',
		'value'			=> 'PHP',
		'attr'			=> 'class="bframe_tab"',
	),
	array(
		'name'			=> 'config_index',
		'class'			=> 'B_Link',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		'link'			=> 'config',
		'value'			=> '設定',
		'attr'			=> 'class="bframe_tab"',
	),
	array(
		'start_html'	=> '<li class="view-mode">',
		'end_html'		=> '</li>',
		array(
			'start_html'	=> '<span id="unified">',
			'end_html'		=> '</span>',
			'value'			=> '<img src="images/common/splith.png" title="unified" alt="unified" />',
		),
		array(
			'start_html'	=> '<span id="split">',
			'end_html'		=> '</span>',
			'value'			=> '<img src="images/common/splitv.png" title="split" alt="split" />',
		),
		array(
			'value'			=> '<input id="mode" type="hidden" value="s">',
		),
		array(
			'value'			=> '<input id="range" type="checkBox" value="1">',
		),
		array(
			'start_html'	=> '<span id="view-all">',
			'end_html'		=> '</span>',
			'value'			=> '<img src="images/common/view_all.png" title="view all" alt="view all" />',
		),
	),
);

$config_form_config = array(
	array(
		// テーブル
		'start_html'	=> '<table class="form"><tbody>',
		'end_html'		=> '</tbody></table>',

		// タイトル
		array(
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			'error_group'	=> true,
			array(
				'start_html'		=> '<th>',
				'invalid_start_html'=> '<th class="diff">',
				'end_html'			=> '</th>',
				array(
					'value'			=> 'タイトル',
					'no_linefeed'	=> true,
				),
			),
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				array(
					'name'			=> 'title_left',
					'class'			=> 'B_InputText',
					'attr'			=> 'class="title ime-on" maxlength="100" readonly="readonly"',
					'validate'		=>
					array(
						array(
							'type' 		=> 'match',
							'target'	=> 'title_right',
						),
					),
				),
			),
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				array(
					'name'			=> 'title_right',
					'class'			=> 'B_InputText',
					'attr'			=> 'class="title ime-on" maxlength="100" readonly="readonly"',
				),
			),
		),
		// bread crumb 表示名
		array(
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			'error_group'	=> true,
			array(
				'start_html'		=> '<th>',
				'invalid_start_html'=> '<th class="diff">',
				'end_html'			=> '</th>',
				array(
					'value'			=> 'bread crumb 表示名',
					'no_linefeed'	=> true,
				),
			),
			array(
				'start_html'		=> '<td>',
				'end_html'			=> '</td>',
				array(
					'name'			=> 'bread_crumb_name_left',
					'class'			=> 'B_InputText',
					'attr'			=> 'class="bread_crumb ime-on" maxlength="100" ',
					'validate'		=>
					array(
						array(
							'type' 		=> 'match',
							'target'	=> 'bread_crumb_name_right',
						),
					),
				),
			),
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				array(
					'name'			=> 'bread_crumb_name_right',
					'class'			=> 'B_InputText',
					'attr'			=> 'class="bread_crumb ime-on" maxlength="100" ',
				),
			),
		),
		// テンプレート
		array(
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			'error_group'	=> true,
			array(
				'start_html'		=> '<th>',
				'invalid_start_html'=> '<th class="diff">',
				'end_html'			=> '</th>',
				'value'				=> 'テンプレート',
			),
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				array(
					'name'			=> 'template_name_left',
					'class'			=> 'B_InputText',
					'attr'			=> 'class="template ime-off" readonly="readonly"',
					'validate'		=>
					array(
						array(
							'type' 		=> 'match',
							'target'	=> 'template_name_right',
						),
					),
				),
			),
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				array(
					'name'			=> 'template_name_right',
					'class'			=> 'B_InputText',
					'attr'			=> 'class="template ime-off" readonly="readonly"',
				),
			),
		),
		// keyword
		array(
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			'error_group'	=> true,
			array(
				'start_html'		=> '<th>',
				'invalid_start_html'=> '<th class="diff">',
				'end_html'			=> '</th>',
				'value'				=> 'keyword',
			),
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				array(
					'name'			=> 'keyword_left',
					'class'			=> 'B_TextArea',
					'attr'			=> 'class="keyword ime-on"',
					'validate'		=>
					array(
						array(
							'type' 		=> 'match',
							'target' 	=> 'keyword_right',
						),
					),
				),
			),
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				array(
					'name'			=> 'keyword_right',
					'class'			=> 'B_TextArea',
					'attr'			=> 'class="keyword ime-on"',
				),
			),
		),
		// description
		array(
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			'error_group'	=> true,
			array(
				'start_html'		=> '<th>',
				'invalid_start_html'=> '<th class="diff">',
				'end_html'			=> '</th>',
				'value'				=> 'description',
			),
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				array(
					'name'			=> 'description_left',
					'class'			=> 'B_TextArea',
					'attr'			=> 'class="description ime-on"',
					'validate'		=>
					array(
						array(
							'type' 		=> 'match',
							'target'	=> 'description_right',
						),
					),
				),
			),
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				array(
					'name'			=> 'description_right',
					'class'			=> 'B_TextArea',
					'attr'			=> 'class="description ime-on"',
				),
			),
		),
		// 外部css
		array(
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			'error_group'	=> true,
			array(
				'start_html'		=> '<th>',
				'invalid_start_html'=> '<th class="diff">',
				'end_html'			=> '</th>',
				'value'				=> '外部css',
			),
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				array(
					'name'			=> 'external_css_left',
					'class'			=> 'B_TextArea',
					'attr'			=> 'class="external_css ime-off"',
					'validate'		=>
					array(
						array(
							'type'		=> 'match',
							'target'	=> 'external_css_right',
						),
					),
				),
			),
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				array(
					'name'			=> 'external_css_right',
					'class'			=> 'B_TextArea',
					'attr'			=> 'class="external_css ime-off"',
				),
			),
		),
		// 外部javascript
		array(
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			'error_group'	=> true,
			array(
				'start_html'		=> '<th>',
				'invalid_start_html'=> '<th class="diff">',
				'end_html'			=> '</th>',
				'value'				=> '外部javascript',
			),
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				array(
					'name'			=> 'external_js_left',
					'class'			=> 'B_TextArea',
					'attr'			=> 'class="external_js ime-off"',
					'validate'		=>
					array(
						array(
							'type'		=> 'match',
							'target'	=> 'external_js_right',
						),
					),
				),
			),
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				array(
					'name'			=> 'external_js_right',
					'class'			=> 'B_TextArea',
					'attr'			=> 'class="external_js ime-off"',
				),
			),
		),
		// ヘッダー要素
		array(
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			'error_group'	=> true,
			array(
				'start_html'		=> '<th>',
				'invalid_start_html'=> '<th class="diff">',
				'end_html'			=> '</th>',
				'value'				=> 'ヘッダー要素',
			),
			array(
				'start_html'		=> '<td>',
				'end_html'			=> '</td>',
				array(
					'class'			=> 'B_TextArea',
					'name'			=> 'header_element_left',
					'attr'			=> 'class="header_element"',
					'validate'		=>
					array(
						array(
							'type'		=> 'match',
							'target'	=> 'header_element_right',
						),
					),
				),
			),
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				array(
					'class'			=> 'B_TextArea',
					'name'			=> 'header_element_right',
					'attr'			=> 'class="header_element"',
				),
			),
		),
	),
);
