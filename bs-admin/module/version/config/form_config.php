<?php
/*
 * B-studio : Content Management System
 * Copyright (c) BigBeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$form_config = array(
	array('class' => 'B_Hidden', 'name' => 'mode'),

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
				'value'			=> __('*'),
			),
			array(
				'class'			=> 'B_Guidance',
				'value'			=> __(' is required field'),
			),
		),

		// Table
		'start_html'	=> '<table class="form" border="0" cellspacing="0" cellpadding="0"><tbody>',
		'end_html'		=> '</tbody></table>',

		// ID
		array(
			'name'			=> 'version_id_row',
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'	=> '<th class="top">',
				'end_html'		=> '</th>',
				array(
					'value'			=> __('ID'),
				),
				array(
					'class'			=> 'B_Guidance',
					'value'			=> '<span class="require">' . __('*') . '</span>',
				),				
			),
			array(
				'name'			=> 'version_id',
				'class'			=> 'B_Text',
				'start_html'	=> '<td class="top">',
				'end_html'		=> '</td>',
			),
		),

		// Publish date/time
		array(
			'error_group'	=> true,
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'			=> '<th>',
				'end_html'				=> '</th>',
				'invalid_start_html'	=> '<th class="error">',
				array(
					'value'				=> __('Publish date and time'),
				),
				array(
					'class'				=> 'B_Guidance',
					'value'				=> '<span class="require">' . __('*') . '</span>',
				),				
			),
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				array(
					'name'				=> 'publication_datetime_t',
					'class'				=> 'B_InputText',
					'special_html'		=> 'class="textbox ime_off"',
					'format'			=> 'Y/m/d H:i',
					'data_set'			=> 'datetime_error_message',
					'validate'			=>
					array(
						array(
							'type' 			=> 'text_datetime',
							'error_message'	=> __('Please enter correct date and time'),
						),
						array(
							'type' 			=> 'required',
							'delimiter'		=> '/',
							'error_message'	=> __('Please enter publish date and time'),
						),
					),
				),
				array(
					'class'		=> 'B_Guidance',
					'value'		=> __('Format: YYYY/MM/DD hh:mm'),
					array(
						'class'			=> 'B_Guidance',
						'start_html'	=> '<span class="example">',
						'end_html'		=> '</span>',
						'value'			=> __('ex) 2020/01/01 12:00'),
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

		// Version（Name）
		array(
			'error_group'	=> true,
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'			=> '<th>',
				'end_html'				=> '</th>',
				'invalid_start_html'	=> '<th class="error">',
				array(
					'value'				=> __('Version name'),
				),
				array(
					'class'				=> 'B_Guidance',
					'value'				=> '<span class="require">' . __('*') . '</span>',
				),				
			),
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				array(
					'name'				=> 'version',
					'class'				=> 'B_InputText',
					'special_html'		=> 'class="textbox ime_on" size="80" maxlength="100"',
					'validate'			=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> __('Please enter version name'),
						),
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

		// Notes
		array(
			'error_group'	=> true,
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'	=> '<th>',
				'end_html'		=> '</th>',
				'value'			=> __('Notes'),
			),
			array(
				'name'			=> 'notes',
				'class'			=> 'B_TextArea',
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				'special_html'	=> 'class="textarea" rows="5"',
			),
		),
	),
);

//control
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

//delete control
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
			'start_html'	=> '<span class="right-button" onclick="bframe.confirmSubmit(\'' . __('All the contents in this version will be completely deleted.\nThis operation cannot be undone.\n\nAre your sure you want to delete?') . '\', \'F1\', \'' . $this->module . '\', \'form\', \'delete\', \'\');">',
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

//Result
$result_config = array(
	array(
		'start_html'	=> '<form name="F1" method="post" action="index.php">',
		'end_html'		=> '</form>',
		array(
			'start_html'	=> '<p>',
			'end_html'		=> '</p>',
			array(
				array(
					'start_html'	=> '<span class="version">',
					'end_html'		=> '</span>',
					array(
						'value'			=> __('Version: '),
					),
					array(
						'name'			=> 'version',
						'class'			=> 'B_Text',
						'start_html'	=> '<span class="bold">',
						'end_html'		=> '</span>',
					),
				),
				array(
					'start_html'	=> '<span class="date-time">',
					'end_html'		=> '</span>',
					array(
						'value'			=> __('Publish date and time: '),
					),
					array(
						'name'			=> 'publication_datetime_t',
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
