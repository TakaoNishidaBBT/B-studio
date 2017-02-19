<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
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
		'start_html'	=> '<div class="editor_container bframe_adjustwindow" data-param="margin:8" >',
		'end_html'		=> '</div>',
		array(
			'start_html'	=> '<div id="html" class="text_editor bframe_adjustparent" data-param="margin:22">',
			'end_html'		=> '</div>',
			array(
				'start_html'	=> '<div class="bframe_compare" id="compare_html">',
				'end_html'		=> '</div>',
				array(
					'name'				=> 'html_left',
					'class'				=> 'B_Hidden',
					'special_html'		=> 'class="bframe_compare_left"',
				),
				array(
					'name'				=> 'html_right',
					'class'				=> 'B_Hidden',
					'special_html'		=> 'class="bframe_compare_right"',
				),
				array(
					'start_html'		=> '<div id="compare_html_display_field" class="bframe_compare_display_field" style="height: 100%">',
					'end_html'			=> '</div>',
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
					'name'				=> 'css_left',
					'class'				=> 'B_Hidden',
					'special_html'		=> 'class="textarea bframe_compare_left"',
				),
				array(
					'name'				=> 'css_right',
					'class'				=> 'B_Hidden',
					'special_html'		=> 'class="textarea bframe_compare_right"',
				),
				array(
					'start_html'		=> '<div id="compare_css_display_field" class="bframe_compare_display_field" style="height: 100%">',
					'end_html'			=> '</div>',
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
					'name'				=> 'php_left',
					'class'				=> 'B_Hidden',
					'special_html'		=> 'class="textarea bframe_compare_left"',
				),
				array(
					'name'				=> 'php_right',
					'class'				=> 'B_Hidden',
					'special_html'		=> 'class="textarea bframe_compare_right"',
				),
				array(
					'start_html'		=> '<div id="compare_php_display_field" class="bframe_compare_display_field" style="height: 100%">',
					'end_html'			=> '</div>',
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

//Tab control
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
		'value'				=> __('HTML'),
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
		'name'				=> 'config_index',
		'class'				=> 'B_Link',
		'start_html'		=> '<li>',
		'end_html'			=> '</li>',
		'link'				=> 'config',
		'value'				=> __('Settings'),
		'special_html'		=> 'class="bframe_tab"',
	),
	array(
		'start_html'		=> '<li class="view-mode">',
		'end_html'			=> '</li>',
		array(
			'start_html'		=> '<span id="unified">',
			'end_html'			=> '</span>',
			'value'				=> '<img src="images/common/splith.png" title="unified" alt="unified" />',
		),
		array(
			'start_html'		=> '<span id="split">',
			'end_html'			=> '</span>',
			'value'				=> '<img src="images/common/splitv.png" title="split" alt="split" />',
		),
		array(
			'value'				=> '<input id="mode" type="hidden" value="s">',
		),
		array(
			'value'				=> '<input id="range" type="checkBox" value="1">',
		),
		array(
			'start_html'		=> '<span id="view-all">',
			'end_html'			=> '</span>',
			'value'				=> '<img src="images/common/view_all.png" title="view all" alt="view all" />',
		),
	),
);

$config_form_config = array(
	array(
		// Table
		'start_html'	=> '<table class="form"><tbody>',
		'end_html'		=> '</tbody></table>',

		// Titlte
		array(
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			'error_group'	=> true,
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="diff">',
				'end_html'				=> '</th>',
				array(
					'value'				=> __('Title'),
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
		// Breadcrumbs
		array(
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			'error_group'	=> true,
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="diff">',
				'end_html'				=> '</th>',
				array(
					'value'				=> __('Breadcrumbs'),	
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
		// Template
		array(
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			'error_group'	=> true,
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="diff">',
				'end_html'				=> '</th>',
				'value'					=> __('Template'),
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
		// Keywords
		array(
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			'error_group'	=> true,
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="diff">',
				'end_html'				=> '</th>',
				'value'					=> __('Keywords'),
			),
			array(
				'start_html'			=> '<td>',
				'end_html'				=> '</td>',
				array(
					'name'					=> 'keywords_left',
					'class'					=> 'B_TextArea',
					'special_html'			=> 'class="keywords ime-on"',
					'validate'				=>
					array(
						array(
							'type' 				=> 'match',
							'target' 			=> 'keywords_right',
						),
					),
				),
			),
			array(
				'start_html'			=> '<td>',
				'end_html'				=> '</td>',
				array(
					'name'					=> 'keywords_right',
					'class'					=> 'B_TextArea',
					'special_html'			=> 'class="keywords ime-on"',
				),
			),
		),
		// Description
		array(
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			'error_group'	=> true,
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="diff">',
				'end_html'				=> '</th>',
				'value'					=> __('Description'),
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
		// External CSS
		array(
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			'error_group'	=> true,
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="diff">',
				'end_html'				=> '</th>',
				'value'					=> __('External CSS'),
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
		// External javascript
		array(
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			'error_group'	=> true,
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="diff">',
				'end_html'				=> '</th>',
				'value'					=> 'External javascript',
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
		// Header elements
		array(
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			'error_group'	=> true,
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="diff">',
				'end_html'				=> '</th>',
				'value'					=> 'Header elements',
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
