<?php
/*
 * B-studio : Content Management System
 * Copyright (c) BigBeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$form_config = array(
	array('class' => 'B_Hidden', 'name' => 'terminal_id', 'value' => TERMINAL_ID),
	array('class' => 'B_Hidden', 'name' => 'node_id'),
	array('class' => 'B_Hidden', 'name' => 'update_datetime'),
	array(
		'start_html'	=> '<div class="editor_container bframe_adjustparent" param="margin:100" >',
		'end_html'		=> '</div>',
		array(
			'start_html'	=> '<div id="config" class="bframe_adjustparent" param="margin:16">',
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
							'value'				=> __('Text color'),
						),
					),
					array(
						'start_html'		=> '<td>',
						'end_html'			=> '</td>',
						array(
							'name'				=> 'color',
							'class'				=> 'B_InputText',
							'special_html'		=> 'class="color ime-off"',
						),
					),
				),

				// Backgroud-color
				array(
					'start_html'	=> '<tr>',
					'end_html'		=> '</tr>',
					array(
						'start_html'		=> '<th>',
						'end_html'			=> '</th>',
						array(
							'value'				=> __('Backgroud-color'),
						),
					),
					array(
						'start_html'		=> '<td>',
						'end_html'			=> '</td>',
						array(
							'name'				=> 'background_color',
							'class'				=> 'B_InputText',
							'special_html'		=> 'class="background-color ime-off"',
						),
					),
				),

				// Icon
				array(
					'start_html'	=> '<tr>',
					'end_html'		=> '</tr>',
					array(
						'start_html'		=> '<th>',
						'end_html'			=> '</th>',
						'value'				=> __('Icon'),
					),
					array(
						'start_html'	=> '<td>',
						'end_html'		=> '</td>',
						array(
							'start_html'	=> '<table class="img-item">',
							'end_html'		=> '</table>',
							array(
								'start_html'	=> '<tr>',
								'end_html'		=> '</tr>',
								array(
									'name'			=> 'icon',
									'start_html'	=> '<td id="icon">',
									'end_html'		=> '</td>',
								),
								array(
									'start_html'	=> '<td>',
									'end_html'		=> '</td>',
									array(
										'name'			=> 'open_filelist',
										'class'			=> 'B_Link',
										'link'			=> 'index.php',
										'special_html'	=> 'title="' . __('Image selection') . '" class="settings-button" onclick="bstudio.activateModalWindow(this, 850, 500); return false;"',
										'fixedparam'	=>
										array(
											'terminal_id'	=> TERMINAL_ID,
											'module'		=> 'filemanager',
											'page'			=> 'popup',
											'method'		=> 'open',
											'target'		=> 'icon',
											'target_id'		=> 'icon_file',
											'width'			=> '110',
											'height'		=> '80',
										),
										'specialchars'	=> 'none',
										'value'			=> '<img alt="Image selection" src="images/common/gear.png" />',
									),
									array(
										'class'				=> 'B_Link',
										'link'				=> '#',
										'special_html'		=> 'title="' . __('Clear') . '" class="clear-button" onclick="bstudio.clearIMG(\'icon\', \'icon_file\'); return false;"',
										'specialchars'		=> 'none',
										'value'				=> '<img alt="Clear" src="images/common/clear.png" />',
									),
								),
								array(
									'name'			=> 'icon_file',
									'class'			=> 'B_Hidden',
									'start_html'	=> '<td>',
									'end_html'		=> '</td>',
								),
							),
						),
					),
				),
			),
		),
	),
);
