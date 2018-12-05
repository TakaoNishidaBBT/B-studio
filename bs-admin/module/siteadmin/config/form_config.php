<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$form_config = array(
	// Required message
	array(
		'class'			=> 'B_Guidance',
		'start_html'	=> '<p>',
		'end_html'		=> '</p>',
		array(
			'class'			=> 'B_Guidance',
			'start_html'	=> '<span class="require">',
			'end_html'		=> '</span>',
			'value'			=> __('*'),
		),
		array(
			'class'			=> 'B_Guidance',
			'value'			=> __(' Indicates required field'),
		),
	),

	array(
		// Table
		'start_html'	=> '<table class="form"><tbody>',
		'end_html'		=> '</tbody></table>',

		// Username
		array(
			'error_group'	=> true,
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'			=> '<th>',
				'end_html'				=> '</th>',
				'invalid_start_html'	=> '<th class="error">',
				array(
					'value'				=> __('Username'),
				),
				array(
					'class'				=> 'B_Guidance',
					'value'				=> '<span class="require">' . __('*') . '</span>',
				),				
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'			=> 'admin_user_name',
					'class'			=> 'B_InputText',
					'attr'			=> 'class="textbox" maxlength="100" ',
					'validate'		=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> __('Please enter username'),
						),
					),
				),
				array(
					'name'			=> 'error_message',
					'class'			=> 'B_ErrMsg',
					'start_html'	=> '<span class="error-message">',
					'end_html'		=> '</span>',
				),
			),
		),

		// Login ID
		array(
			'error_group'	=> true,
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'		=> '<th>',
				'end_html'			=> '</th>',
				'invalid_start_html'=> '<th class="error">',
				array(
					'value'			=> __('Login ID'),
				),
				array(
					'class'			=> 'B_Guidance',
					'value'			=> '<span class="require">' . __('*') . '</span>',
				),				
			),
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				array(
					'name'			=> 'admin_user_id',
					'class'			=> 'B_InputText',
					'attr'			=> 'class="textbox ime-off" maxlength="100" ',
					'validate'		=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> __('Please enter login ID'),
						),
						array(
							'type' 			=> 'pattern',
							'pattern'		=> '^[a-zA-Z0-9\_\-]+$',
							'error_message'	=> __('Please enter login ID using only alphanumeric, hyphen(-) and underbar(_)'),
						),
						array(
							'type'			=> 'callback',
							'obj'			=> $this,
							'method'		=> '_validate_callback',
							'error_message'	=> __('This ID is already exists'),
						),
					),
				),
				array(
					'name'			=> 'error_message',
					'class'			=> 'B_ErrMsg',
					'start_html'	=> '<span class="error-message">',
					'end_html'		=> '</span>',
				),
			),
		),

		// Language
		array(
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'	=> '<th>',
				'end_html'		=> '</th>',
				'value'			=> __('Language'),
			),
			array(
				'class'			=> 'B_SelectBox',
				'name'			=> 'language',
				'data_set'		=> 'language',
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				'value'			=> LANG,
				'attr'			=> 'class="bframe_selectbox white"',
			),
	    ),

		// Password
		array(
			'error_group'	=> true,
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'		=> '<th>',
				'end_html'			=> '</th>',
				'invalid_start_html'=> '<th class="error">',
				'value'				=> __('Password'),
			),
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				array(
					'name'				=> 'dummy_pwd',
					'class'				=> 'B_Password',
					'attr'				=> 'style="position: absolute; visibility: hidden" ',
				),
				array(
					'name'				=> 'admin_user_pwd',
					'class'				=> 'B_Password',
					'attr'				=> 'class="textbox ime-off" maxlength="100" autocomplete="off" ',
					'confirm_message'	=> __('(set password)'),
					'validate'			=>
					array(
						array(
							'type' 			=> 'pattern',
							'pattern'		=> '^[a-zA-Z0-9\_\-]+$',
							'error_message'	=> __('Please enter password using only alphanumeric, hyphen(-) and underbar(_)'),
						),
					),
				),
				array(
					'start_html'	=> '<span class="notice">',
					'end_html'		=> '</span>',
					'class'			=> 'B_Guidance',
					'value'			=> __('If you would like to change your password, please enter new password here. If not, please leave this field blank.'),
				),
				array(
					'name'			=> 'error_message',
					'class'			=> 'B_ErrMsg',
					'start_html'	=> '<p class="error-message">',
					'end_html'		=> '</p>',
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
				'start_html'		=> '<th>',
				'end_html'			=> '</th>',
				'invalid_start_html'=> '<th class="error">',
				'value'				=> __('Password (Re-entry)'),
			),
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				array(
					'name'			=> 'admin_user_pwd2',
					'class'			=> 'B_Password',
					'attr'			=> 'class="textbox ime-off" maxlength="100" autocomplete="off" ',
					'validate'		=>
					array(
						array(
							'type' 			=> 'match',
							'target'		=> 'admin_user_pwd',
							'error_message'	=> __('Password dose not match'),
						),
					),
				),
				array(
					'start_html'	=> '<span class="notice">',
					'end_html'		=> '</span>',
					'class'			=> 'B_Guidance',
					'value'			=> __('For confirmation, please re-enter password'),
				),
				array(
					'name'			=> 'error_message',
					'class'			=> 'B_ErrMsg',
					'start_html'	=> '<p class="error-message">',
					'end_html'		=> '</p>',
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
			array(
				'start_html'	=> '<span class="text">',
				'end_html'		=> '</span>',
				'value'			=> __('Confirm'),
			),
			array(
				'start_html'	=> '<span class="img-cover">',
				'end_html'		=> '</span>',
				'value'			=> '<img src="images/common/right_arrow.png" alt="right arow" />',
			),
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
			array(
				'start_html'	=> '<span class="img-cover">',
				'end_html'		=> '</span>',
				'value'			=> '<img src="images/common/left_arrow.png" alt="left arow" />',
			),
			array(
				'start_html'	=> '<span class="text">',
				'end_html'		=> '</span>',
				'value'			=> __('Back'),
			),
		),
	),
	array(
		'name'			=> 'register',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		array(
			'start_html'	=> '<span class="right-button" onclick="bframe.submit(\'F1\', \'' . $this->module . '\', \'form\', \'register\', \'\')">',
			'end_html'		=> '</span>',
			array(
				'start_html'	=> '<span class="text">',
				'end_html'		=> '</span>',
				'value'			=> __('Save'),
			),
			array(
				'start_html'	=> '<span class="img-cover">',
				'end_html'		=> '</span>',
				'value'			=> '<img src="images/common/right_arrow.png" alt="right arow" />',
			),
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
			'start_html'	=> '<span class="left-button" onclick="bframe.submit(\'F1\', \'' . $this->module . '\', \'form\', \'\', \'\')">',
			'end_html'		=> '</span>',
			array(
				'start_html'	=> '<span class="img-cover">',
				'end_html'		=> '</span>',
				'value'			=> '<img src="images/common/left_arrow.png" alt="left arow" />',
			),
			array(
				'start_html'	=> '<span class="text">',
				'end_html'		=> '</span>',
				'value'			=> __('Back to site admin settings'),
			),
		),
	),
);
