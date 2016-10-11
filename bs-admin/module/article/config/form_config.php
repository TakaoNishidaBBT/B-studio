<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$form_config = array(
	array('class' => 'B_Hidden', 'name' => 'baseHref', 'value' => B_SITE_BASE),
	array('class' => 'B_Hidden', 'name' => 'visual_editor_language', 'value' => $_SESSION['language']),
	array('class' => 'B_Hidden', 'name' => 'visual_editor_body_class', 'value' => 'contents'),
	array('class' => 'B_Hidden', 'name' => 'visual_editor_styles', 'value' => 'default:' . B_CURRENT_ROOT . 'visualeditor/article1/styles/styles.js'),
	array('class' => 'B_Hidden', 'name' => 'visual_editor_css',	'value' => B_CURRENT_ROOT . 'visualeditor/article1/css/default.css'),
	array('class' => 'B_Hidden', 'name' => 'visual_editor_templates', 'value' => B_CURRENT_ROOT . 'visualeditor/article1/templates/default.js'),
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

		// ID
		array(
			'name'			=> 'article_id_row',
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
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

		// 掲載日
		array(
			'error_group'	=> true,
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'end_html'				=> '</th>',
				'invalid_start_html'	=> '<th class="error">',
				array(
					'value'					=> _('Publication date'),
				),
				array(
					'class'				=> 'B_Guidance',
					'value'				=> '<span class="require">' . _('*') . '</span>',
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
							'error_message'	=> _('Please enter publication date'),
						),
					),
				),
				array(
					'filter'			=> 'select',
					'id'				=> 'schedule_calendar1',
					'class'				=> 'B_Link',
					'special_html'		=> 'class="bframe_calendar settings-button" title="カレンダー"',
					'script'			=>
					array(
						'bframe_calendar'	=>
						array(
							'width'			=> '170',
							'height'		=> '195',
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
						'value'		=> '<img alt="カレンダー" src="images/common/calendar.png" />',
					),
				),
				array(
					'name'			=> 'error_message',
					'class'			=> 'B_ErrMsg',
					'start_html'	=> '<span class="error-message">',
					'end_html'		=> '</spab>',
				),
			),
		),

		// Category
		array(
			'error_group'	=> true,
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'	=> '<th>',
				'end_html'		=> '</th>',
				'value'			=> _('Category'),
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
					'special_html'		=> 'title="' . _('Category') . '" class="settings-button" onclick="top.bframe.modalWindow.activate(this, window, \'category_id\'); return false;" params="width:350,height:400"',
					'fixedparam'		=>
					array(
						'terminal_id'		=> TERMINAL_ID,
						'module'			=> 'category', 
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
					'special_html'		=> 'title="' . _('Clear') . '" class="clear-button" onclick="bstudio.clearText(\'category_id\', \'category_name\'); return false;"',
					'specialchars'		=> 'none',
					'value'				=> '<img alt="Clear" src="images/common/clear.png" />',
				),

			),
	    ),

		// keywords
		array(
			'error_group'	=> true,
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'	=> '<th>',
				'end_html'		=> '</th>',
				'value'			=> 'Keywords',
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'				=> 'keywords',
					'class'				=> 'B_InputText',
					'special_html'		=> 'class="textbox" size="120" maxlength="100"',
				),
			),
		),

		// description
		array(
			'error_group'	=> true,
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'	=> '<th>',
				'end_html'		=> '</th>',
				'value'			=> 'Description',
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'				=> 'description',
					'class'				=> 'B_TextArea',
					'special_html'		=> 'class="textarea title ime_on" size="120" maxlength="100"',
				),
			),
		),

		// Status
		array(
			'error_group'	=> true,
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'	=> '<th>',
				'end_html'		=> '</th>',
				'value'			=> _('Status'),
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
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
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'end_html'				=> '</th>',
				'invalid_start_html'	=> '<th class="error">',
				array(
					'value'					=> _('Title'),
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
					'name'					=> 'title',
					'class'					=> 'B_TextArea',
					'special_html'			=> 'class="textarea title ime_on" size="120" maxlength="100"',
					'validate'				=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> _('Please enter title'),
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
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'end_html'				=> '</th>',
				'value'					=> _('Title image'),
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'start_html'    => '<table class="img-item">',
					'end_html'	    => '</table>',
					array(
						'start_html'    => '<tr>',
						'end_html'	    => '</tr>',
						array(
							'name'			=> 'title_img',
							'start_html'	=> '<td id="title_img">',
							'end_html'		=> '</td>',
						),
						array(
							'filter'		=> 'select',
							'start_html'    => '<td>',
							'end_html'	    => '</td>',
							array(
								'name'			=> 'open_filelist',
								'class'			=> 'B_Link',
								'link'			=> 'index.php',
								'special_html'	=> 'title="画像選択" class="settings-button" onclick="bstudio.activateModalWindow(this, 850, 500); return false;"',
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
								'special_html'		=> 'title="' . _('Clear') . '" class="clear-button" onclick="bstudio.clearIMG(\'title_img\', \'title_img_file\'); return false;"',
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
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'	=> '<th>',
				'end_html'		=> '</th>',
				'value'			=> _('Display detail'),
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
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
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="error">',
				'end_html'				=> '</th>',
				'value'					=> _('External link'),
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'			=> 'external_link',
					'class'			=> 'B_CheckBox',
					'label'			=> _('On'),
					'value'			=> '1',
					'fixed'			=> true,
					'special_html'	=> ' class="checkbox"',
				),
				array(
					'display'		=> 'none',
					'name'			=> 'external_link_none',
					'value'			=> 'なし',
				),
				array(
					'name'			=> 'url',
					'class'			=> 'B_InputText',
					'start_html'    => '　URL： ',
					'special_html'	=> 'class="textbox ime_off" style="width:500px" maxlength="100"',
					'status'		=> true,
					'validate'		=>
					array(
						array(
							'type' 			=> 'status',
							'error_message'	=> _('If you choose external link on, please enter URL'),
						),
					),
				),
				array(
					'value'			=> '&nbsp;',
				),
				array(
					'name'			=> 'external_window',
					'class'			=> 'B_CheckBox',
					'label'			=> _('Another window'),
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
					'value'			=> 'ファイル管理',
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

		// Detail
		array(
			'error_group'	=> true,
			'name'			=> 'contents_row',
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'	=> '<th>',
				'end_html'		=> '</th>',
				'value'			=> _('Detail'),
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'			=> 'contents',
					'class'			=> 'B_TextArea',
					'special_html'	=> 'class="textarea bframe_visualeditor" style="height:400px"',
				),
			),
	    ),
	),
);

//control
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
			'value'			=> _('Back'),
		),
	),
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
	'start_html'	=> '<ul>',
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

//delete control
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
			'value'			=> _('Back'),
		),
	),
	array(
		'name'			=> 'regist',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		array(
			'start_html'	=> '<span class="right-button" onclick="bframe.confirmSubmit(\'' . _('Are you sure to delete?') . '\', \'F1\', \'' . $this->module . '\', \'form\', \'delete\', \'\');">',
			'end_html'		=> '</span>',
			'value'			=> _('Delete'),
		),
	),
);

//control
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
			'value'			=> _('Back to list'),
		),
	),
);

//result
$result_config = array(
	array(
		'start_html'	=> '<form name="F1" method="post" action="index.php">',
		'end_html'		=> '</form>',
		array(
			'start_html'    => '<p>',
			'end_html'	    => '</p>',
			array(
				array(
					'value'					=> _('Date: '),
				),
				array(
					'name'					=> 'article_date_t',
					'class'					=> 'B_Text',
					'start_html'			=> '<span class="bold">',
					'end_html'				=> '</span>',
				),
				array(
					'value'					=> _('Title: '),
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
