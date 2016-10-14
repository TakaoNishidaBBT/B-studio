<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$form_config = array(
	array('class' => 'B_Hidden', 'name' => 'mode'),
	array('class' => 'B_Hidden', 'name' => 'id'),
	array('class' => 'B_Hidden', 'name' => 'user_id'),
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

		// User ID
		array(
			'error_group'	=> true,
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'			=> '<th>',
				'end_html'				=> '</th>',
				'invalid_start_html'	=> '<th class="error">',
				array(
					'value'					=> _('User ID'),
				),
				array(
					'class'				=> 'B_Guidance',
					'value'				=> '<span class="require">' . _('*') . '</span>',
				),
			),
			array(
				'start_html'			=> '<td>',
				'end_html'				=> '</td>',
				array(
					'config_filter'			=> 'insert',
					'name'					=> 'user_id',
					'class'					=> 'B_InputText',
					'special_html'			=> 'class="textbox ime_off" maxlength="10" size="20"',
					'validate'				=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> _('Please enter User ID'),
						),
						array(
							'type' 			=> 'pattern',
							'pattern'		=> '^[a-zA-Z0-9\_\-]+$',
							'error_message'	=> _('Please enter User ID using alphanumeric, hyphen(-) and underbar(_)'),
						),
						array(
							'type'			=> 'callback',
							'obj'			=> $this,
							'method'		=> '_validate_callback',
							'error_message'	=> _('This ID is already exists'),
						),
						array(
							'type'			=> 'callback',
							'obj'			=> $this,
							'method'		=> '_validate_callback2',
							'error_message'	=> _('This ID can not be used'),
						),
					),
				),
				array(
					'config_filter'			=> 'update/delete',
					'class'					=> 'B_Text',
					'name'					=> 'user_id',
				),
				array(
					'name'					=> 'error_message',
					'class'					=> 'B_ErrMsg',
					'start_html'			=> '<span class="error-message">',
					'end_html'				=> '</span>',
				),
			),
		),

		// Password
		array(
			'error_group'	=> true,
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="error">',
				'end_html'				=> '</th>',
				array(
					'value'					=> _('Password'),
				),
				array(
					'class'				=> 'B_Guidance',
					'value'				=> '<span class="require">' . _('*') . '</span>',
				),
			),
			array(
				'start_html'			=> '<td>',
				'end_html'				=> '</td>',
				array(
					'name'					=> 'pwd',
					'class'					=> 'B_InputText',
					'special_html'			=> 'class="textbox ime_off" size="20" maxlength="100" ',
					'validate'				=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> _('Please enter password'),
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

		// Name
		array(
			'error_group'	=> true,
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="error">',
				'end_html'				=> '</th>',
				array(
					'value'				=> _('Name'),
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
					'name'					=> 'name',
					'class'					=> 'B_InputText',
					'special_html'			=> 'class="textbox ime-on" size="20" maxlength="100" ',
					'validate'				=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> 'Please enter a name',
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

		// Authority
		array(
			'error_group'	=> true,
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="error">',
				'end_html'				=> '</th>',
				array(
					'value'					=> _('Authority'),
				),
				array(
					'class'				=> 'B_Guidance',
					'value'				=> '<span class="require">' . _('*') . '</span>',
				),
			),
			array(
				'name'					=> 'user_auth',
				'class'					=> 'B_SelectBox',
				'start_html'			=> '<td>',
				'end_html'				=> '</td>',
				'data_set'				=> 'user_auth',
				'special_html'			=> 'class="bframe_selectbox"',
				'validate'				=>
				array(
					array(
						'type' 			=> 'required',
						'error_message'	=> 'Please set user privilege',
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

		// Status
		array(
			'error_group'	=> true,
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="error">',
				'end_html'				=> '</th>',
				array(
					'value'					=> _('Status'),
				),
				array(
					'class'				=> 'B_Guidance',
					'value'				=> '<span class="require">' . _('*') . '</span>',
				),
			),
			array(
				'name'					=> 'user_status',
				'class'					=> 'B_SelectBox',
				'start_html'			=> '<td>',
				'end_html'				=> '</td>',
				'data_set'				=> 'user_status',
				'special_html'			=> 'class="bframe_selectbox"',
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

		// Notes
		array(
			'error_group'	=> true,
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="error">',
				'end_html'				=> '</th>',
				'value'					=> _('Notes'),
			),
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				array(
					'name'				=> 'notes',
					'class'				=> 'B_TextArea',
					'special_html'		=> 'class="textarea" cols="78" rows="5"',
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
				'value'			=> _('Back'),
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
				'value'			=> _('Confirm'),
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
				'value'			=> _('Back'),
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
				'value'			=> _('Save'),
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
				'value'			=> _('Back'),
			),
		),
	),
	array(
		'name'			=> 'register',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		array(
			'start_html'	=> '<span class="right-button" onclick="return bframe.confirmSubmit(\'このレコードを削除します。\n\nよろしいですか？\', \'F1\', \'' . $this->module . '\', \'form\', \'register\', \'delete\')">',
			'end_html'		=> '</span>',
			array(
				'start_html'	=> '<span class="text">',
				'end_html'		=> '</span>',
				'value'			=> _('Delete'),
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
			'start_html'	=> '<span class="left-button" style="width:150px" onclick="bframe.submit(\'F1\', \'' . $this->module . '\', \'list\', \'back\', \'\')">',
			'end_html'		=> '</span>',
			array(
				'start_html'	=> '<span class="img-cover">',
				'end_html'		=> '</span>',
				'value'			=> '<img src="images/common/left_arrow.png" alt="left arow" />',
			),
			array(
				'start_html'	=> '<span class="text">',
				'end_html'		=> '</span>',
				'value'			=> _('Back to list'),
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
						'value'			=> _('User ID: '),
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
