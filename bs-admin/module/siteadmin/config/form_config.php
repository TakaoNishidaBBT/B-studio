<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$form_config = array(
	array(
		// Required message
		array(
			'class'			=> 'B_Guidance',
			'start_html'	=> '<p>',
			'end_html'		=> '</p>',
			array(
				'class'			=> 'B_Guidance',
				'start_html'	=> '<span class="require">',
				'end_html'		=> '</span>',
				'value'			=> _('*'),
			),
			array(
				'class'			=> 'B_Guidance',
				'value'			=> _(' is required field'),
			),
		),

		// Table
		'start_html'	=> '<table class="form" border="0" cellspacing="0" cellpadding="0"><tbody>',
		'end_html'		=> '</tbody></table>',

		// User name
		array(
			'error_group'	=> true,
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'			=> '<th>',
				'end_html'				=> '</th>',
				'invalid_start_html'	=> '<th class="error">',
				array(
					'value'				=> _('User name'),
				),
				array(
					'class'				=> 'B_Guidance',
					'value'				=> '<span class="require">' . _('*') . '</span>',
				),				
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'					=> 'admin_user_name',
					'class'					=> 'B_InputText',
					'special_html'			=> 'class="textbox ime-on" size="40" maxlength="100" ',
					'validate'				=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> _('Please enter user name'),
						),
					),
				),
				array(
					'name'					=> 'error_message',
					'class'					=> 'B_ErrMsg',
					'start_html'			=> '<span class="error-message">',
					'end_html'				=> '</span>',
				),
			),
		),

		// Login ID
		array(
			'error_group'	=> true,
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'			=> '<th>',
				'end_html'				=> '</th>',
				'invalid_start_html'	=> '<th class="error">',
				array(
					'value'					=> _('Login ID'),
				),
				array(
					'class'				=> 'B_Guidance',
					'value'				=> '<span class="require">' . _('*') . '</span>',
				),				
			),
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				array(
					'name'					=> 'admin_user_id',
					'class'					=> 'B_InputText',
					'special_html'			=> 'class="textbox ime-off" size="40" maxlength="100" ',
					'validate'				=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> _('Please enter login ID'),
						),
						array(
							'type' 			=> 'pattern',
							'pattern'		=> '^[a-zA-Z0-9\_\-]+$',
							'error_message'	=> _('Please enter login ID using alphanumeric, hyphen(-) and underbar(_)'),
						),
						array(
							'type'			=> 'callback',
							'obj'			=> $this,
							'method'		=> '_validate_callback',
							'error_message'	=> _('This ID is already exists'),
						),
					),
				),
				array(
					'name'					=> 'error_message',
					'class'					=> 'B_ErrMsg',
					'start_html'			=> '<span class="error-message">',
					'end_html'				=> '</span>',
				),
			),
		),

		// Language
		array(
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'		=> '<th>',
				'end_html'			=> '</th>',
				'value'				=> _('Language'),
			),
			array(
				'class'				=> 'B_SelectBox',
				'name'				=> 'language',
				'data_set'			=> 'language',
				'start_html'		=> '<td>',
				'end_html'			=> '</td>',
				'value'				=> LANG,
				'special_html'		=> 'class="bframe_selectbox"',
			),
	    ),

		// Password
		array(
			'error_group'	=> true,
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'			=> '<th>',
				'end_html'				=> '</th>',
				'invalid_start_html'	=> '<th class="error">',
				'value'					=> _('Password'),
			),
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				array(
					'name'					=> 'admin_user_pwd',
					'class'					=> 'B_Password',
					'special_html'			=> 'class="textbox ime-off" size="40" maxlength="100" autocomplete="off" ',
					'confirm_message'		=> _('Password you set'),
					'validate'				=>
					array(
						array(
							'type' 			=> 'pattern',
							'pattern'		=> '^[a-zA-Z0-9\_\-]+$',
							'error_message'	=> _('Please enter User ID using alphanumeric, hyphen(-) and underbar(_)'),
						),
					),
				),
				array(
					'start_html'			=> '<span class="notice">',
					'end_html'				=> '</span>',
					'class'					=> 'B_Guidance',
					'value'					=> _('If you change password, please enter password. If you don\'t, keep this field empty'),
				),
				array(
					'name'					=> 'error_message',
					'class'					=> 'B_ErrMsg',
					'start_html'			=> '<p class="error-message">',
					'end_html'				=> '</p>',
				),
			),
		),

		// Password (Re-entry)
		array(
			'error_group'	=> true,
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			'confirm_mode'	=> 'none',
			array(
				'start_html'			=> '<th>',
				'end_html'				=> '</th>',
				'invalid_start_html'	=> '<th class="error">',
				'value'					=> _('Password (Re-entry)'),
			),
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				array(
					'name'					=> 'admin_user_pwd2',
					'class'					=> 'B_Password',
					'special_html'			=> 'class="textbox ime-off" size="40" maxlength="100" autocomplete="off" ',
					'validate'				=>
					array(
						array(
							'type' 			=> 'match',
							'target'		=> 'admin_user_pwd',
							'error_message'	=> _('Password is not matched'),
						),
					),
				),
				array(
					'start_html'			=> '<span class="notice">',
					'end_html'				=> '</span>',
					'class'					=> 'B_Guidance',
					'value'					=> _('For confirmation, please re-enter password'),
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
);

//config
$result_config = array(
	array(
		'start_html'	=> '<form name="F1" method="post" action="index.php">',
		'end_html'		=> '</form>',
		array(
			'name'			=> 'action_message',
		),
	),
);

//control
$input_control_config = array(
	'start_html'	=> '<ul class="control">',
	'end_html'		=> '</ul>',
	array(
		'name'			=> 'confirm',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		array(
			'start_html'	=> '<span class="right-button" onclick="bframe.submit(\'F1\', \'' . $this->module . '\', \'form\', \'confirm\', \'\', true)">',
			'end_html'		=> '</span>',
			'value'			=> _('Confirm'),
		),
	),
);

//confirm control
$confirm_control_config = array(
	'start_html'	=> '<ul class="control">',
	'end_html'		=> '</ul>',
	array(
		'name'			=> 'back',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		array(
			'start_html'	=> '<span class="left-button" onclick="bframe.submit(\'F1\', \'' . $this->module . '\', \'form\', \'back\', \'\')">',
			'end_html'		=> '</span>',
			'value'			=> _('Back'),
		),
	),
	array(
		'name'			=> 'regist',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		array(
			'start_html'	=> '<span class="right-button" onclick="bframe.submit(\'F1\', \'' . $this->module . '\', \'form\', \'register\', \'\')">',
			'end_html'		=> '</span>',
			'value'			=> _('Save'),
		),
	),
);

//result
$result_control_config = array(
	'start_html'	=> '<ul class="control">',
	'end_html'		=> '</ul>',
	array(
		'name'			=> 'backToList',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		array(
			'start_html'	=> '<span class="left-button" style="width:190px" onclick="bframe.submit(\'F1\', \'' . $this->module . '\', \'form\', \'\', \'\')">',
			'end_html'		=> '</span>',
			'value'			=> _('Back to site admin form'),
		),
	),
);
