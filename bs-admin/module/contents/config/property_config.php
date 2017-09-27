<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$form_config = array(
	array('class' => 'B_Hidden', 'name' => 'terminal_id', 'value' => TERMINAL_ID),
	array('class' => 'B_Hidden', 'name' => 'node_id'),
	array('class' => 'B_Hidden', 'name' => 'update_datetime'),
	array(
		'start_html'	=> '<div class="editor_container bframe_adjustparent" data-param="margin:100" >',
		'end_html'		=> '</div>',
		array(
			'start_html'	=> '<div id="config" class="bframe_adjustparent" data-param="margin:16">',
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
		'name'				=> 'config_index',
		'class'				=> 'B_Link',
		'start_html'		=> '<li>',
		'end_html'			=> '</li>',
		'link'				=> 'property1',
		'value'				=> __('Settings'),
		'special_html'		=> 'class="bframe_tab"',
	),
);
$config_form_config = array(
	'start_html'	=> '<div class="property">',
	'end_html'		=> '</div>',
	array(
		'name'			=> 'property1',
		'start_html'	=> '<div id="property1">',
		'end_html'		=> '</div>',
		array(
			'start_html'	=> '<fieldset class="first-child">',
			'end_html'		=> '</fieldset>',
			array(
				'start_html'	=> '<legend>',
				'end_html'		=> '</legend>',
				'value'			=> __('Display'),
			),
			array(
				// Table
				'start_html'	=> '<table class="form file-title" border="0" cellspacing="0" cellpadding="0"><tbody>',
				'end_html'		=> '</tbody></table>',

				// Text color
				array(
					'start_html'	=> '<tr>',
					'end_html'		=> '</tr>',
					array(
						'start_html'		=> '<th>',
						'end_html'			=> '</th>',
						array(
							'value'			=> __('Status'),
						),
					),
					array(
						'start_html'		=> '<td>',
						'end_html'			=> '</td>',
						array(
							'name'			=> 'display',
							'class'			=> 'B_RadioContainer',
							'data_set'		=> 'display',
							'value'			=> '1',
							'item'			=>
							array(
								'special_html'		=> ' class=radio',
							),
						),
					),
				),
			),
		),
	),
);
