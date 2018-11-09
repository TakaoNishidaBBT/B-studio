<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$form_config = array(
	array('class' => 'B_Hidden', 'name' => 'mode'),
	array('class' => 'B_Hidden', 'name' => 'id'),
//	array('class' => 'B_Hidden', 'name' => 'user_id'),

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

		// User ID
		array(
			'error_group'	=> true,
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'		=> '<th>',
				'end_html'			=> '</th>',
				'invalid_start_html'=> '<th class="error">',
				array(
					'value'			=> __('User ID'),
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
					'config_filter'		=> 'insert',
					'name'				=> 'user_id',
					'class'				=> 'B_InputText',
					'attr'				=> 'class="textbox ime_off" maxlength="10" ',
					'validate'			=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> __('Please enter user ID'),
						),
						array(
							'type' 			=> 'pattern',
							'pattern'		=> '^[a-zA-Z0-9\_\-]+$',
							'error_message'	=> __('Please enter user ID using only alphanumeric, hyphen(-) and underbar(_)'),
						),
						array(
							'type'			=> 'callback',
							'obj'			=> $this,
							'method'		=> '_validate_callback',
							'error_message'	=> __('This ID is already in use'),
						),
						array(
							'type'			=> 'callback',
							'obj'			=> $this,
							'method'		=> '_validate_callback2',
							'error_message'	=> __('This ID cannot be used'),
						),
					),
				),
				array(
					'config_filter'		=> 'update/delete',
					'class'				=> 'B_Text',
					'name'				=> 'user_id',
				),
				array(
					'name'				=> 'error_message',
					'class'				=> 'B_ErrMsg',
					'start_html'		=> '<span class="error-message">',
					'end_html'			=> '</span>',
				),
			),
		),

		// Password
		array(
			'error_group'	=> true,
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'		=> '<th>',
				'invalid_start_html'=> '<th class="error">',
				'end_html'			=> '</th>',
				array(
					'value'			=> __('Password'),
				),
				array(
					'class'		=> 'B_Guidance',
					'value'		=> '<span class="require">' . __('*') . '</span>',
				),
			),
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				array(
					'name'			=> 'pwd',
					'class'			=> 'B_InputText',
					'attr'			=> 'class="textbox ime_off" maxlength="100" ',
					'validate'		=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> __('Please enter password'),
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

		// Name
		array(
			'error_group'	=> true,
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'		=> '<th>',
				'invalid_start_html'=> '<th class="error">',
				'end_html'			=> '</th>',
				array(
					'value'			=> __('Name'),
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
					'name'			=> 'user_name',
					'class'			=> 'B_InputText',
					'attr'			=> 'class="textbox" maxlength="100" ',
					'validate'		=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> __('Please enter a name'),
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

		// User type
		array(
			'error_group'	=> true,
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'		=> '<th>',
				'invalid_start_html'=> '<th class="error">',
				'end_html'			=> '</th>',
				array(
					'value'			=> __('User type'),
				),
				array(
					'class'			=> 'B_Guidance',
					'value'			=> '<span class="require">' . __('*') . '</span>',
				),
			),
			array(
				'name'				=> 'user_auth',
				'class'				=> 'B_SelectBox',
				'start_html'		=> '<td>',
				'end_html'			=> '</td>',
				'data_set'			=> 'user_auth',
				'attr'				=> 'class="bframe_selectbox white"',
				'validate'			=>
				array(
					array(
						'type' 			=> 'required',
						'error_message'	=> __('Please select user type'),
					),
				),
				array(
					'name'				=> 'error_message',
					'class'				=> 'B_ErrMsg',
					'start_html'		=> '<span class="error-message">',
					'end_html'			=> '</span>',
				),
			),
		),

		// Status
		array(
			'error_group'	=> true,
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'		=> '<th>',
				'invalid_start_html'=> '<th class="error">',
				'end_html'			=> '</th>',
				array(
					'value'			=> __('Status'),
				),
				array(
					'class'			=> 'B_Guidance',
					'value'			=> '<span class="require">' . __('*') . '</span>',
				),
			),
			array(
				'name'				=> 'user_status',
				'class'				=> 'B_SelectBox',
				'start_html'		=> '<td>',
				'end_html'			=> '</td>',
				'data_set'			=> 'user_status',
				'attr'				=> 'class="bframe_selectbox white"',
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

		// Notes
		array(
			'error_group'	=> true,
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'		=> '<th>',
				'invalid_start_html'=> '<th class="error">',
				'end_html'			=> '</th>',
				'value'				=> __('Notes'),
			),
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				array(
					'name'		=> 'notes',
					'class'		=> 'B_TextArea',
					'attr'		=> 'class="textarea bframe_textarea" cols="78" rows="5"',
				),
			),
		),
	),
);

// control
$input_control_config = array(
	'start_html'	=> '<ul class="control">',
	'end_html'		=> '</ul>',
	array(
		'name'			=> 'back',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		array(
			'start_html'	=> '<span class="left-button" onclick="bframe.submit(\'F1\', \'' . $this->module . '\', \'list\', \'back\', \'\')">',
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

// control
$delete_control_config = array(
	'start_html'	=> '<ul class="control">',
	'end_html'		=> '</ul>',
	array(
		'name'			=> 'back',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		array(
			'start_html'	=> '<span class="left-button" onclick="bframe.submit(\'F1\', \'' . $this->module . '\', \'list\', \'back\', \'\')">',
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
			'start_html'	=> '<span class="right-button" onclick="return bframe.confirmSubmit(\'' . __('Are you sure you want to delete?') . '\', \'F1\', \'' . $this->module . '\', \'form\', \'register\', \'delete\')">',
			'end_html'		=> '</span>',
			array(
				'start_html'	=> '<span class="text">',
				'end_html'		=> '</span>',
				'value'			=> __('Delete'),
			),
			array(
				'start_html'	=> '<span class="img-cover">',
				'end_html'		=> '</span>',
				'value'			=> '<img src="images/common/right_arrow.png" alt="right arow" />',
			),
		),
	),
);

//control
$result_control_config = array(
	'start_html'	=> '<ul class="control">',
	'end_html'		=> '</ul>',
	array(
		'name'			=> 'backToList',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		array(
			'start_html'	=> '<span class="left-button" onclick="bframe.submit(\'F1\', \'' . $this->module . '\', \'list\', \'back\', \'\')">',
			'end_html'		=> '</span>',
			array(
				'start_html'	=> '<span class="img-cover">',
				'end_html'		=> '</span>',
				'value'			=> '<img src="images/common/left_arrow.png" alt="left arow" />',
			),
			array(
				'start_html'	=> '<span class="text">',
				'end_html'		=> '</span>',
				'value'			=> __('Back to list'),
			),
		),
	),
);

//result
$result_config = array(
	array(
		'start_html'	=> '<form name="F1" method="post" action="index.php">',
		'end_html'		=> '</form>',
		array(
			'start_html'	=> '<p>',
			'end_html'		=> '</p>',
			array(
				array(
					'start_html'	=> '<span class="user-id">',
					'end_html'		=> '</span>',
					array(
						'value'			=> __('User ID: '),
					),
					array(
						'name'			=> 'user_id',
						'class'			=> 'B_Text',
						'start_html'	=> '<span class="bold">',
						'end_html'		=> '</span>',
					),
				),
				array(
					'name'		=> 'action_message',
				),
			),
		),
	),
);
