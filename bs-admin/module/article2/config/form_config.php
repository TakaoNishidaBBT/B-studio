<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$form_config = array(
	array('class' => 'B_Hidden', 'name' => 'baseHref', 'value' => B_SITE_BASE),
	array('class' => 'B_Hidden', 'name' => 'visual_editor_language', 'value' => $_SESSION['language']),
	array('class' => 'B_Hidden', 'name' => 'visual_editor_body_class', 'value' => 'contents'),
	array('class' => 'B_Hidden', 'name' => 'visual_editor_styles', 'value' => 'default:' . B_CURRENT_ROOT . 'visualeditor/article2/styles/styles.js'),
	array('class' => 'B_Hidden', 'name' => 'visual_editor_css',	'value' => B_CURRENT_ROOT . 'visualeditor/article2/css/default.css'),
	array('class' => 'B_Hidden', 'name' => 'visual_editor_templates', 'value' => B_CURRENT_ROOT . 'visualeditor/article2/templates/default.js'),
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

		// ID
		array(
			'name'			=> 'article_id_row',
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'	=> '<th>',
				'end_html'		=> '</th>',
				'value'			=> 'ID',
			),
			array(
				'name'			=> 'article_id',
				'class'			=> 'B_Text',
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
			),
		),

		// Publication date
		array(
			'error_group'	=> true,
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'			=> '<th>',
				'end_html'				=> '</th>',
				'invalid_start_html'	=> '<th class="error">',
				array(
					'value'					=> __('Publication date'),
				),
				array(
					'class'				=> 'B_Guidance',
					'value'				=> '<span class="require">' . __('*') . '</span>',
				),				
			),
			array(
				'start_html'		=> '<td>',
				'end_html'			=> '</td>',
				array(
					'name'				=> 'article_date_t',
					'class'				=> 'B_InputText',
					'special_html'		=> 'class="textbox ime_off" size="20" readonly="readonly"',
					'validate'			=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> __('Please enter publication date'),
						),
					),
				),
				array(
					'filter'			=> 'select',
					'id'				=> 'schedule_calendar1',
					'class'				=> 'B_Link',
					'special_html'		=> 'class="bframe_calendar settings-button" title="' . __('Calendar') . '"',
					'script'			=>
					array(
						'bframe_calendar'	=>
						array(
							'width'			=> '200',
							'height'		=> '230',
							'offsetLeft'	=> '174',
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
						'value'		=> '<img alt="Calendar" src="images/common/calendar.png" />',
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

		// Category
		array(
			'error_group'	=> true,
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'	=> '<th>',
				'end_html'		=> '</th>',
				'value'			=> __('Category'),
			),
			array(
				'start_html'		=> '<td>',
				'end_html'			=> '</td>',
				array(
					'name'				=> 'category_name',
					'class'				=> 'B_InputText',
					'special_html'		=> 'class="textbox" size="20" readonly="readonly"',
				),
				array(
					'class'				=> 'B_Hidden',
					'name'				=> 'category_id',
				),
				array(
					'filter'			=> 'select',
					'name'				=> 'open_category',
					'class'				=> 'B_Link',
					'link'				=> 'index.php',
					'special_html'		=> 'title="' . __('Category') . '" class="settings-button" onclick="top.bframe.modalWindow.activate(this, window, \'category_id\'); return false;" data-param="width:350,height:400"',
					'fixedparam'		=>
					array(
						'terminal_id'		=> TERMINAL_ID,
						'module'			=> 'category2', 
						'page'				=> 'tree',
					),
					array(
						'value'			=> '<img alt="Category" src="images/common/gear.png" />',
					),
				),
				array(
					'filter'			=> 'select',
					'class'				=> 'B_Link',
					'link'				=> '#',
					'special_html'		=> 'title="' . __('Clear') . '" class="clear-button" onclick="bstudio.clearText(\'category_id\', \'category_name\'); return false;"',
					'specialchars'		=> 'none',
					'value'				=> '<img alt="Clear" src="images/common/clear.png" />',
				),

			),
		),

		// Keywords
		array(
			'error_group'	=> true,
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'	=> '<th>',
				'end_html'		=> '</th>',
				'value'			=> 'Keywords',
			),
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				array(
					'name'				=> 'keywords',
					'class'				=> 'B_InputText',
					'special_html'		=> 'class="textbox" size="120" maxlength="100"',
				),
			),
		),

		// Description
		array(
			'error_group'	=> true,
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'	=> '<th>',
				'end_html'		=> '</th>',
				'value'			=> 'Description',
			),
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				array(
					'name'				=> 'description',
					'class'				=> 'B_TextArea',
					'special_html'		=> 'class="textarea title ime_on" maxlength="100"',
				),
			),
		),

		// Status
		array(
			'error_group'	=> true,
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'	=> '<th>',
				'end_html'		=> '</th>',
				'value'			=> __('Status'),
			),
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
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

		// Title
		array(
			'error_group'	=> true,
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'			=> '<th>',
				'end_html'				=> '</th>',
				'invalid_start_html'	=> '<th class="error">',
				array(
					'value'					=> __('Title'),
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
					'name'					=> 'title',
					'class'					=> 'B_TextArea',
					'special_html'			=> 'class="textarea title ime_on" maxlength="100"',
					'validate'				=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> __('Please enter title'),
						),
					),
				),
				array(
					'name'					=> 'error_message',
					'class'					=> 'B_ErrMsg',
					'start_html'			=> '<p class="error-message">',
					'end_html'				=> '</p>',
				),
			),
		),

		// Title image
		array(
			'error_group'	=> true,
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'			=> '<th>',
				'end_html'				=> '</th>',
				'value'					=> __('Title image'),
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
							'name'			=> 'title_img',
							'start_html'	=> '<td id="title_img">',
							'end_html'		=> '</td>',
						),
						array(
							'filter'		=> 'select',
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
									'target'		=> 'title_img',
									'target_id'		=> 'title_img_file',
								),
								'specialchars'	=> 'none',
								'value'			=> '<img alt="Select image" src="images/common/gear.png" />',
							),
							array(
								'filter'			=> 'select',
								'class'				=> 'B_Link',
								'link'				=> '#',
								'special_html'		=> 'title="' . __('Clear') . '" class="clear-button" onclick="bstudio.clearIMG(\'title_img\', \'title_img_file\'); return false;"',
								'specialchars'		=> 'none',
								'value'				=> '<img alt="Clear" src="images/common/clear.png" />',
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
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'	=> '<th>',
				'end_html'		=> '</th>',
				'value'			=> __('Display detail'),
			),
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				array(
					'name'			=> 'description_flag',
					'class'			=> 'B_RadioContainer',
					'data_set'		=> 'description_flag',
					'value'			=> '1',
					'item'			=>
					array(
						'special_html'	=> ' class=radio onclick="bstudio.articleDetailControl(this, \'external_link\', \'url\', \'external_window\')"',
					),
				),
			),
		),

		// External link
		array(
			'error_group'	=> true,
			'name'			=> 'external_link_row',
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="error">',
				'end_html'				=> '</th>',
				'value'					=> __('External link'),
			),
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
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
				array(
					'name'			=> 'url',
					'class'			=> 'B_InputText',
					'start_html'	=> '　URL： ',
					'special_html'	=> 'class="textbox ime_off" style="width:500px" maxlength="100"',
					'status'		=> true,
					'validate'		=>
					array(
						array(
							'type' 			=> 'status',
							'error_message'	=> __('After turninng external link on, please enter URL'),
						),
					),
				),
				array(
					'value'			=> '&nbsp;',
				),
				array(
					'name'			=> 'external_window',
					'class'			=> 'B_CheckBox',
					'label'			=> __('Open link in new window'),
					'value'			=> '1',
					'fixed'			=> true,
					'special_html'	=> ' class="checkbox"',
				),
				array(
					'name'			=> 'error_message',
					'class'			=> 'B_ErrMsg',
					'start_html'	=> '<p class="error-message">',
					'end_html'		=> '</p>',
				),
				array(
					'id'			=> 'filebrowser',
					'class'			=> 'B_Link',
					'link'			=> 'index.php',
					'value'			=> __('File manager'),
					'special_html'	=> 'style="display:none"',
					'fixedparam'	=>
					array(
						'terminal_id'	=> TERMINAL_ID,
						'module'		=> 'filemanager', 
						'page'			=> 'popup', 
					),
				),
			),
		),

		// Details
		array(
			'error_group'	=> true,
			'name'			=> 'contents_row',
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'start_html'	=> '<th>',
				'end_html'		=> '</th>',
				'value'			=> __('Details'),
			),
			array(
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
				array(
					'name'			=> 'contents',
					'class'			=> 'B_TextArea',
					'special_html'	=> 'class="textarea bframe_visualeditor" style="height:400px"',
				),
			),
		),
	),
);

// Control
$input_control_config = array(
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
	array(
		'start_html'	=> '<form name="F1" method="post" action="index.php">',
		'end_html'		=> '</form>',
		array(
			'start_html'	=> '<p>',
			'end_html'		=> '</p>',
			array(
				array(
					'value'					=> __('Date: '),
				),
				array(
					'name'					=> 'article_date_t',
					'class'					=> 'B_Text',
					'start_html'			=> '<span class="bold">',
					'end_html'				=> '</span>',
				),
				array(
					'value'					=> __('Title: '),
				),
				array(
					'name'					=> 'title',
					'class'					=> 'B_Text',
					'start_html'			=> '<span class="bold">',
					'end_html'				=> '</span>',
				),
				array(
					'name'					=> 'action_message',
				),
			),
		),
	),
);
