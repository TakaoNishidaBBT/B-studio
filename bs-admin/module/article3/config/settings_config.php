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

	// Slug
	array(
		'name'			=> 'slug_row',
		'filter'		=> '!insert',
		array(
			'start_html'	=> '<dt class="slug">',
			'end_html'		=> '</dt>',
			array(
				'start_html'	=> '<span class="title">',
				'end_html'		=> '</span>',
				'value'			=> __('Slug'),
			),
			array(
				'name'			=> 'slug',
				'class'			=> 'B_InputText',
				'attr'			=> 'class="textbox slug ime_off"',
				'validate'		=>
				array(
					array(
						'type' 			=> 'callback',
						'obj'			=> $this,
						'method'		=> '_validate_callback',
						'error_message'	=> __('This Slug is already in use'),
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
			'start_html'	=> '<dt class="publication">',
			'end_html'		=> '</dt>',
			array(
				'start_html'	=> '<div class="flex-row">',
				'end_html'		=> '</div>',
				array(
					'start_html'	=> '<span class="title">',
					'end_html'		=> '</span>',
					'value'			=> __('Publication date'),
				),
				array(
					'name'			=> 'article_date_t',
					'class'			=> 'B_InputText',
					'attr'			=> 'class="textbox date ime_off" readonly="readonly"',
					'validate'		=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> __('Please enter publication date'),
						),
					),
				),
				array(
					'filter'		=> 'update/insert/default',
					'id'			=> 'schedule_calendar1',
					'class'			=> 'B_Link',
					'attr'			=> 'class="bframe_calendar settings-button" title="' . __('Calendar') . '" onclick="bstudio.activateCalendar(\'schedule_calendar1\'); return false;" ',
					'script'		=>
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
					'filter'			=> 'update/insert/default',
					'class'				=> 'B_Link',
					'link'				=> '#',
					'attr'				=> 'title="' . __('Clear') . '" class="clear-button" onclick="bstudio.clearText(\'article_date_t\'); return false;"',
					'specialchars'		=> 'none',
					'value'				=> '<img alt="Clear" src="images/common/clear_white.png" />',
				),
				array(
					'name'				=> 'error_message',
					'class'				=> 'B_ErrMsg',
					'start_html'		=> '<p class="error-message">',
					'end_html'			=> '</p>',
				),
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
					'attr'			=> ' class=radio',
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
			'start_html'	=> '<dd>',
			'end_html'		=> '</dd>',
			array(
				'start_html'	=> '<div class="flex-row">',
				'end_html'		=> '</div>',
				array(
					'name'			=> 'category',
					'class'			=> 'B_InputText',
					'attr'			=> 'class="textbox category" readonly="readonly"',
				),
				array(
					'class'			=> 'B_Hidden',
					'name'			=> 'category_id',
				),
				array(
					'filter'		=> 'update/insert/default',
					'name'			=> 'open_category',
					'class'			=> 'B_Link',
					'link'			=> 'index.php',
					'attr'			=> 'title="' . __('Category') . '" class="settings-button" onclick="top.bframe.modalWindow.activate(this, window, \'category_id\'); return false;" data-param="width:350,height:400"',
					'fixedparam'	=>
					array(
						'terminal_id'	=> TERMINAL_ID,
						'module'		=> 'category3', 
						'page'			=> 'tree',
					),
					array(
						'value'			=> '<img alt="Category" src="images/common/gear_white.png" />',
					),
				),
				array(
					'filter'		=> 'update/insert/default',
					'class'			=> 'B_Link',
					'link'			=> '#',
					'attr'			=> 'title="' . __('Clear') . '" class="clear-button" onclick="bstudio.clearText(\'category_id\', \'category\'); return false;"',
					'specialchars'	=> 'none',
					'value'			=> '<img alt="Clear" src="images/common/clear_white.png" />',
				),
			),
		),
	),

	// Tags
	array(
		'error_group'	=> true,
		array(
			'start_html'	=> '<dt>',
			'end_html'		=> '</dt>',
			array(
				'start_html'	=> '<span class="title">',
				'end_html'		=> '</span>',
				'value'			=> __('Tags'),
			),
		),
		array(
			'start_html'	=> '<dd>',
			'end_html'		=> '</dd>',
			array(
				'start_html'	=> '<div class="flex-row">',
				'end_html'		=> '</div>',
				array(
					'start_html'	=> '<div class="tag-container">',
					'end_html'		=> '</div>',
					array(
						'name'			=> 'tag_list',
						'start_html'	=> '<div id="tag_list" class="tag-list">',
						'end_html'		=> '</div>',
					),
					array(
						'name'			=> 'tags',
						'class'			=> 'B_Hidden',
					),
					array(
						'class'			=> 'B_Hidden',
						'name'			=> 'tag_id',
					),
					array(
						'filter'		=> 'update/insert/default',
						'name'			=> 'open_tags',
						'class'			=> 'B_Link',
						'link'			=> 'index.php',
						'attr'			=> 'title="' . __('Tags') . '" class="settings-button" onclick="top.bframe.modalWindow.activate(this, window, \'tag_id\'); return false;" data-param="width:350,height:400"',
						'fixedparam'	=>
						array(
							'terminal_id'	=> TERMINAL_ID,
							'module'		=> 'tag3',
							'page'			=> 'tree',
						),
						array(
							'value'			=> '<img alt="Tags" src="images/common/gear_white.png" />',
						),
					),
					array(
						'filter'		=> 'update/insert/default',
						'class'			=> 'B_Link',
						'link'			=> '#',
						'attr'			=> 'title="' . __('Clear') . '" class="clear-button" onclick="bstudio.clearTag(); return false;"',
						'specialchars'	=> 'none',
						'value'			=> '<img alt="Clear" src="images/common/clear_white.png" />',
					),
				),
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
				'start_html'	=> '<div class="flex-row">',
				'end_html'		=> '</div>',
				array(
					'name'			=> 'title_img',
					'start_html'	=> '<div id="title_img">',
					'end_html'		=> '</div>',
				),
				array(
					'filter'		=> 'update/insert/default',
					array(
						'name'			=> 'open_filelist',
						'class'			=> 'B_Link',
						'link'			=> 'index.php',
						'attr'			=> 'title="' . __('Image selection') . '" class="settings-button" onclick="bstudio.activateModalWindow(this, 1100, 500); return false;"',
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
						'filter'		=> 'update/insert/default',
						'class'			=> 'B_Link',
						'link'			=> '#',
						'attr'			=> 'title="' . __('Clear') . '" class="clear-button" onclick="bstudio.clearIMG(\'title_img\', \'title_img_file\'); return false;"',
						'specialchars'	=> 'none',
						'value'			=> '<img alt="Clear" src="images/common/clear_white.png" />',
					),
				),
				array(
					'name'			=> 'title_img_file',
					'class'			=> 'B_Hidden',
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
					'attr'			=> ' class=radio onclick="bstudio.articleDetailControl(this, \'external\', \'external_link\', \'url\', \'external_window\')"',
				),
			),
		),
	),

	// External link
	array(
		'error_group'	=> true,
		array(
			'name'			=> 'external_link_container',
			'start_html'	=> '<dd id="external" class="external-link">',
			'start_html_d'	=> '<dd id="external" class="external-link disabled">',
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
					'attr'			=> ' class="checkbox"',
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
					'attr'			=> 'class="textbox ime_off" maxlength="500"',
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
					'attr'			=> ' class="checkbox"',
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

	// Hash Tag
	array(
		'error_group'	=> true,
		array(
			'start_html'	=> '<dt>',
			'end_html'		=> '</dt>',
			array(
				'start_html'	=> '<span class="title">',
				'end_html'		=> '</span>',
				'value'			=> __('Hash Tag'),
			),
		),
		array(
			'start_html'	=> '<dd>',
			'end_html'		=> '</dd>',
			array(
				'name'			=> 'hash_tag',
				'class'			=> 'B_TextArea',
				'attr'			=> 'class="textarea ime_on bframe_textarea" maxlength="100"',
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
				'name'			=> 'description',
				'class'			=> 'B_TextArea',
				'attr'			=> 'class="textarea ime_on bframe_textarea" maxlength="100"',
			),
		),
	),
);

//Copy Control
$copy_control_config = array(
	'start_html'	=> '<div id="copy-control">',
	'end_html'		=> '</div>',
	array(
		'start_html'	=> '<form name="copy_form" method="post" action="index.php">',
		'end_html'		=> '</form>',
		array(
			'start_html'	=> '<ul class="copy">',
			'end_html'		=> '</ul>',
			array(
				'start_html'	=> '<li>',
				'end_html'		=> '</li>',
				array(
					'start_html'	=> '<span>',
					'end_html'		=> '</span>',
					'value'			=> 'No.',
				),
				array(
					'class'			=> 'B_InputText',
					'name'			=> 'source_id',
					'attr'			=> 'class="textbox number ime_off" maxlength="10"',
				),
				array(
					'name'			=> 'dummy',
					'class'			=> 'B_InputText',
					'attr'			=> 'class="dummy"',
				),
			),
			array(
				'name'			=> 'copy',
				'class'			=> 'B_Submit',
				'start_html'	=> '<li>',
				'end_html'		=> '</li>',
				'attr'			=> 'class="copy-button" onClick="bframe.submit(\'copy_form\', \'' . $this->module . '\', \'form\', \'copy\', \'insert\', true)"',
				'value'			=> __('Copy'),
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
		'name'			=> 'headline',
		'class'			=> 'B_Text',
		'start_html'	=> '<span class="bold">',
		'end_html'		=> '</span>',
	),
	array(
		'name'			=> 'action_message',
	),
);
