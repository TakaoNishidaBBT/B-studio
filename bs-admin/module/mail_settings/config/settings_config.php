<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$settings_config = array(
	// Table
	'start_html'	=> '<dl class="settings">',
	'end_html'		=> '</dl>',

	// ID
	array(
		'name'			=> 'mail_id_row',
		'filter'		=> '!insert',
		array(
			'name'			=> 'mail_id',
			'class'			=> 'B_Text',
			'start_html'	=> '<dt class="id"><span class="title">ID : </span>',
			'end_html'		=> '</dt>',
		),
		array(
			'name'			=> 'mail_id',
			'class'			=> 'B_Hidden',
		),
	),

	// Mail Type
	array(
		'name'			=> 'permalink_row',
		'filter'		=> '!insert',
		array(
			'start_html'		=> '<dt class="publication">',
			'end_html'			=> '</dt>',
			array(
				'start_html'		=> '<span class="title">',
				'end_html'			=> '</span>',
				'value'				=> __('Mail Type'),
			),
			array(
				'name'				=> 'mail_type',
				'class'				=> 'B_SelectBox',
				'data_set'			=> array('mail_type_settings_default', 'mail_type_settings'),
				'confirm_data_set'	=> 'mail_type_settings',
				'special_html'		=> 'class="bframe_selectbox"',
				'validate'			=>
				array(
					array(
						'type' 			=> 'required',
						'error_message'	=> __('Please select Mail Type'),
					),
				),
			),
			array(
				'name'			=> 'error_message',
				'class'			=> 'B_ErrMsg',
				'start_html'	=> '<p class="error-message">',
				'end_html'		=> '</p>',
			),
		),
	),

	// Mail Title
	array(
		'error_group'	=> true,
		array(
			'start_html'		=> '<dt class="title">',
			'end_html'			=> '</dt>',
			array(
				'start_html'		=> '<span class="title">',
				'end_html'			=> '</span>',
				'value'				=> __('Mail Title'),
			),
		),
		array(
			'start_html'	=> '<dd>',
			'end_html'		=> '</dd>',
			array(
				'name'				=> 'mail_title',
				'class'				=> 'B_InputText',
				'special_html'		=> 'class="textbox mail-name ime_off"',
				'validate'			=>
				array(
					array(
						'type' 			=> 'required',
						'error_message'	=> __('Please enter Mail Title'),
					),
				),
			),
			array(
				'name'			=> 'error_message',
				'class'			=> 'B_ErrMsg',
				'start_html'	=> '<p class="error-message">',
				'end_html'		=> '</p>',
			),
		),
	),

	// From Address
	array(
		'error_group'	=> true,
		array(
			'start_html'	=> '<dt>',
			'end_html'		=> '</dt>',
			array(
				'start_html'	=> '<span class="title">',
				'end_html'		=> '</span>',
				'value'			=> __('From Address'),
			),
		),
		array(
			'start_html'	=> '<dd>',
			'end_html'		=> '</dd>',
			array(
				'name'			=> 'from_addr',
				'class'			=> 'B_InputText',
				'special_html'	=> 'class="textbox mail-name ime_off"',
				'validate'		=>
				array(
					array(
						'type' 			=> 'required',
						'error_message'	=> __('Please enter From Adress'),
					),
					array(
						'type' 			=> 'pattern',
						'pattern'		=> '^[0-9a-zA-Z_\.-]+@[0-9A-Za-z][0-9a-zA-Z_\.-]+\.[A-Za-z]+$',
						'error_message'	=> __('There is an error in the entry format'),
					),
					array(
						'type' 			=> 'denaial_pattern',
						'pattern'		=> '\.\.',
						'error_message'	=> __('There is an error in the entry format'),
					),
					array(
						'type' 			=> 'denaial_pattern',
						'pattern'		=> '^\.',
						'error_message'	=> __('There is an error in the entry format'),
					),
					array(
						'type' 			=> 'denaial_pattern',
						'pattern'		=> '\.@',
						'error_message'	=> __('There is an error in the entry format'),
					),
					array(
						'type' 			=> 'emailMX',
						'error_message'	=> __('Please enter a valid email address'),
					),
					array(
						'type' 			=> 'callback',
						'obj'			=> $this,
						'method'		=> '_formAddressCheckCallBack',
						'error_message'	=> __('Please enter From Address'),
					),
				),
			),
			array(
				'name'			=> 'error_message',
				'class'			=> 'B_ErrMsg',
				'start_html'	=> '<p class="error-message">',
				'end_html'		=> '</p>',
			),
		),
	),

	// From Name
	array(
		'error_group'	=> true,
		array(
			'start_html'	=> '<dt>',
			'end_html'		=> '</dt>',
			array(
				'start_html'	=> '<span class="title">',
				'end_html'		=> '</span>',
				'value'			=> __('From Name'),
			),
		),
		array(
			'start_html'		=> '<dd>',
			'end_html'			=> '</dd>',
			array(
				'name'				=> 'from_name',
				'class'				=> 'B_InputText',
				'special_html'		=> 'class="textbox"',
				'validate'			=>
				array(
					array(
						'type' 			=> 'required',
						'error_message'	=> __('Please enter From Name'),
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

	// BCC
	array(
		'error_group'	=> true,
		array(
			'start_html'	=> '<dt>',
			'end_html'		=> '</dt>',
			array(
				'start_html'	=> '<span class="title">',
				'end_html'		=> '</span>',
				'value'			=> 'BCC',
			),
		),
		array(
			'start_html'		=> '<dd>',
			'end_html'			=> '</dd>',
			array(
				'name'				=> 'bcc',
				'class'				=> 'B_InputText',
				'special_html'		=> 'class="textbox"',
			),
			array(
				'name'					=> 'error_message',
				'class'					=> 'B_ErrMsg',
				'start_html'			=> '<span class="error-message">',
				'end_html'				=> '</span>',
			),
		),
	),
);

// Control
$input_control_config = array(
	array(
		'start_html'	=> '<ul>',
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
				'start_html'	=> '<span class="right-button" onclick="bframe.ajaxSubmit.submit(\'F1\', \'' . $this->module . '\', \'form\', \'register\', \'confirm\', true)">',
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
	),
	array(
		'start_html'	=> '<div class="message-container">',
		'end_html'		=> '</div>',
		array(
			'start_html'	=> '<span id="message">',
			'end_html'		=> '</span>',
		),
	),
);

// Confirm control
$confirm_control_config = array(
	'start_html'	=> '<ul>',
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

// Delete control
$delete_control_config = array(
	'start_html'	=> '<ul>',
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
			'start_html'	=> '<span class="right-button" onclick="bframe.confirmSubmit(\'' . __('Are you sure you want to delete?') . '\', \'F1\', \'' . $this->module . '\', \'form\', \'delete\', \'\');">',
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

// Result control
$result_control_config = array(
	'start_html'	=> '<ul>',
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
				'value'			=> __('Back to list'),
			),
		),
	),
);

// Result
$result_config = array(
	'start_html'	=> '<p>',
	'end_html'		=> '</p>',
	array(
		'start_html'	=> '<span class="date-result">',
		'end_html'		=> '</span>',
		'value'			=> __('Date: '),
	),
	array(
		'name'			=> 'article_date_t',
		'class'			=> 'B_Text',
		'start_html'	=> '<span class="bold">',
		'end_html'		=> '</span>',
	),
	array(
		'start_html'	=> '<span class="title-result">',
		'end_html'		=> '</span>',
		'value'			=> __('Title: '),
	),
	array(
		'name'			=> 'title',
		'class'			=> 'B_Text',
		'start_html'	=> '<span class="bold">',
		'end_html'		=> '</span>',
	),
	array(
		'name'			=> 'action_message',
	),
);
