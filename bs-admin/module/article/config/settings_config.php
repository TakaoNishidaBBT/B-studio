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
		'name'			=> 'article_id_row',
		'filter'		=> '!insert',
		array(
			'name'			=> 'article_id',
			'class'			=> 'B_Text',
			'start_html'	=> '<dt class="id"><span class="title">ID : </span>',
			'end_html'		=> '</dt>',
		),
		array(
			'name'			=> 'article_id',
			'class'			=> 'B_Hidden',
		),
	),

	// permalink
	array(
		'name'			=> 'permalink_row',
		'filter'		=> '!insert',
		array(
			'start_html'		=> '<dt class="publication">',
			'end_html'			=> '</dt>',
			array(
				'start_html'		=> '<span class="title">',
				'end_html'			=> '</span>',
				'value'				=> __('Permalink'),
			),
			array(
				'name'				=> 'permalink',
				'class'				=> 'B_InputText',
				'special_html'		=> 'class="textbox permalink ime_off"',
				'validate'			=>
				array(
					array(
						'type' 			=> 'callback',
						'obj'			=> $this,
						'method'		=> '_validate_callback',
						'error_message'	=> __('This permalink is already in use'),
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

	// Publication date
	array(
		'error_group'	=> true,
		array(
			'start_html'		=> '<dt class="publication">',
			'end_html'			=> '</dt>',
			array(
				'start_html'		=> '<span class="title">',
				'end_html'			=> '</span>',
				'value'				=> __('Publication date'),
			),
			array(
				'name'				=> 'article_date_t',
				'class'				=> 'B_InputText',
				'special_html'		=> 'class="textbox date ime_off" readonly="readonly"',
				'validate'			=>
				array(
					array(
						'type' 			=> 'required',
						'error_message'	=> __('Please enter publication date'),
					),
				),
			),
			array(
				'filter'			=> 'select/insert',
				'id'				=> 'schedule_calendar1',
				'class'				=> 'B_Link',
				'special_html'		=> 'class="bframe_calendar settings-button" title="' . __('Calendar') . '"',
				'script'			=>
				array(
					'bframe_calendar'	=>
					array(
						'width'			=> '200',
						'height'		=> '230',
						'offsetLeft'	=> '4',
						'drop_shadow'	=> 'true',
						'target'		=> 'article_date_t',
						'ajax'			=>
						array(
							'module'		=> 'calendar',
							'file'			=> 'ajax',
							'method'		=> 'getCalendar',
						),
					),
				),
				'element'	=>
				array(
					'value'		=> '<img alt="Calendar" src="images/common/calendar_white.png" />',
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

	// Status
	array(
		'error_group'	=> true,
		array(
			'start_html'	=> '<dt>',
			'end_html'		=> '</dt>',
			array(
				'start_html'	=> '<span class="title">',
				'end_html'		=> '</span>',
				'value'			=> __('Status'),
			),
		),
		array(
			'start_html'	=> '<dd>',
			'end_html'		=> '</dd>',
			array(
				'name'			=> 'publication',
				'class'			=> 'B_RadioContainer',
				'data_set'		=> 'publication',
				'value'			=> '1',
				'item'			=>
				array(
					'special_html'		=> ' class=radio',
				),
			),
		),
	),

	// Category
	array(
		'error_group'	=> true,
		array(
			'start_html'	=> '<dt>',
			'end_html'		=> '</dt>',
			array(
				'start_html'	=> '<span class="title">',
				'end_html'		=> '</span>',
				'value'			=> __('Category'),
			),
		),
		array(
			'start_html'		=> '<dd>',
			'end_html'			=> '</dd>',
			array(
				'name'				=> 'category_name',
				'class'				=> 'B_InputText',
				'special_html'		=> 'class="textbox" readonly="readonly"',
			),
			array(
				'class'				=> 'B_Hidden',
				'name'				=> 'category_id',
			),
			array(
				'filter'			=> 'select/insert',
				'name'				=> 'open_category',
				'class'				=> 'B_Link',
				'link'				=> 'index.php',
				'special_html'		=> 'title="' . __('Category') . '" class="settings-button" onclick="top.bframe.modalWindow.activate(this, window, \'category_id\'); return false;" data-param="width:350,height:400"',
				'fixedparam'		=>
				array(
					'terminal_id'		=> TERMINAL_ID,
					'module'			=> 'category', 
					'page'				=> 'tree',
				),
				array(
					'value'			=> '<img alt="Category" src="images/common/gear_white.png" />',
				),
			),
			array(
				'filter'			=> 'select',
				'class'				=> 'B_Link',
				'link'				=> '#',
				'special_html'		=> 'title="' . __('Clear') . '" class="clear-button" onclick="bstudio.clearText(\'category_id\', \'category_name\'); return false;"',
				'specialchars'		=> 'none',
				'value'				=> '<img alt="Clear" src="images/common/clear_white.png" />',
			),

		),
	),

	// Title image
	array(
		'error_group'	=> true,
		array(
			'start_html'	=> '<dt>',
			'end_html'		=> '</dt>',
			array(
				'start_html'	=> '<span class="title">',
				'end_html'		=> '</span>',
				'value'					=> __('Title image'),
			),
		),
		array(
			'start_html'	=> '<dd>',
			'end_html'		=> '</dd>',
			array(
				'start_html'	=> '<table class="img-item">',
				'end_html'		=> '</table>',
				array(
					'start_html'	=> '<tr>',
					'end_html'		=> '</tr>',
					array(
						'name'			=> 'title_img',
						'start_html'	=> '<td id="title_img">',
						'end_html'		=> '</td>',
					),
					array(
						'filter'		=> 'select/insert',
						'start_html'	=> '<td class="buttons">',
						'end_html'		=> '</td>',
						array(
							'name'			=> 'open_filelist',
							'class'			=> 'B_Link',
							'link'			=> 'index.php',
							'special_html'	=> 'title="' . __('Image selection') . '" class="settings-button" onclick="bstudio.activateModalWindow(this, 1000, 500); return false;"',
							'fixedparam'	=>
							array(
								'terminal_id'	=> TERMINAL_ID,
								'module'		=> 'filemanager',
								'page'			=> 'popup',
								'method'		=> 'open',
								'target'		=> 'title_img',
								'target_id'		=> 'title_img_file',
							),
							'specialchars'	=> 'none',
							'value'			=> '<img alt="Select image" src="images/common/gear_white.png" />',
						),
						array(
							'filter'			=> 'select',
							'class'				=> 'B_Link',
							'link'				=> '#',
							'special_html'		=> 'title="' . __('Clear') . '" class="clear-button" onclick="bstudio.clearIMG(\'title_img\', \'title_img_file\'); return false;"',
							'specialchars'		=> 'none',
							'value'				=> '<img alt="Clear" src="images/common/clear_white.png" />',
						),
					),
					array(
						'name'			=> 'title_img_file',
						'class'			=> 'B_Hidden',
						'start_html'	=> '<td>',
						'end_html'		=> '</td>',
					),
				),
			),
		),
	),

	// Display detail
	array(
		'error_group'	=> true,
		array(
			'start_html'	=> '<dt class="display-detail">',
			'end_html'		=> '</dt>',
			array(
				'start_html'	=> '<span class="title">',
				'end_html'		=> '</span>',
				'value'			=> __('Display detail'),
			),
			array(
				'name'			=> 'description_flag',
				'class'			=> 'B_RadioContainer',
				'data_set'		=> 'description_flag',
				'value'			=> '1',
				'item'			=>
				array(
					'special_html'	=> ' class=radio onclick="bstudio.articleDetailControl(this, \'external\', \'external_link\', \'url\', \'external_window\')"',
				),
			),
		),
	),

	// External link
	array(
		'error_group'	=> true,
		'name'			=> 'external_link_row',
		array(
			'start_html'	=> '<dd id="external" class="external-link">',
			'end_html'		=> '</dd>',
			array(
				'start_html'	=> '<div>',
				'end_html'		=> '</div>',
				array(
					'value'			=> __('External link'),
				),
				array(
					'name'			=> 'external_link',
					'class'			=> 'B_CheckBox',
					'label'			=> __('On'),
					'value'			=> '1',
					'fixed'			=> true,
					'special_html'	=> ' class="checkbox"',
				),
				array(
					'display'		=> 'none',
					'name'			=> 'external_link_none',
					'value'			=> __('None'),
				),
			),
			array(
				'start_html'	=> '<div class="url">',
				'end_html'		=> '</div>',
				array(
					'name'			=> 'url',
					'class'			=> 'B_InputText',
					'start_html'	=> 'URL： ',
					'special_html'	=> 'class="textbox ime_off" maxlength="500"',
					'status'		=> true,
					'validate'		=>
					array(
						array(
							'type' 			=> 'status',
							'error_message'	=> __('After turninng external link on, please enter URL'),
						),
					),
				),
			),
			array(
				'start_html'	=> '<div>',
				'end_html'		=> '</div>',
				array(
					'name'			=> 'external_window',
					'class'			=> 'B_CheckBox',
					'label'			=> __('Open link in new window'),
					'value'			=> '1',
					'fixed'			=> true,
					'special_html'	=> ' class="checkbox"',
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

	// keywords
	array(
		'error_group'	=> true,
		array(
			'start_html'	=> '<dt>',
			'end_html'		=> '</dt>',
			array(
				'start_html'	=> '<span class="title">',
				'end_html'		=> '</span>',
				'value'			=> 'Keywords',
			),
		),
		array(
			'start_html'	=> '<dd>',
			'end_html'		=> '</dd>',
			array(
				'name'				=> 'keywords',
				'class'				=> 'B_TextArea',
				'special_html'		=> 'class="textarea ime_on bframe_textarea" maxlength="100"',
			),
		),
	),

	// description
	array(
		'error_group'	=> true,
		array(
			'start_html'	=> '<dt>',
			'end_html'		=> '</dt>',
			array(
				'start_html'	=> '<span class="title">',
				'end_html'		=> '</span>',
				'value'			=> 'Description',
			),
		),
		array(
			'start_html'	=> '<dd>',
			'end_html'		=> '</dd>',
			array(
				'name'				=> 'description',
				'class'				=> 'B_TextArea',
				'special_html'		=> 'class="textarea ime_on bframe_textarea" maxlength="100"',
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
