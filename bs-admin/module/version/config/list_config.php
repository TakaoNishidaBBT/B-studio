<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$list_config = 
array(
	'start_html'	=> '<table class="list">',
	'end_html'		=> '</table>',

	'select_sql'	=> "select 	 a.version_id
								,a.publication_datetime_t
								,a.version
								,a.notes
								,e.reserved_version_id
								,e.working_version_id
								,if(b.current_version_id = version_id, 1, if(c.reserved_version_id = version_id, 2, 0)) publication_status
								,if(d.working_version_id = version_id, 1, 0) working_status
						from " . B_DB_PREFIX . "version a
						left join " . B_DB_PREFIX . "v_current_version b
						on a.version_id = b.current_version_id
						left join " . B_DB_PREFIX . "v_current_version c
						on a.version_id = c.reserved_version_id
						left join " . B_DB_PREFIX . "v_current_version d
						on a.version_id = d.working_version_id
						left join " . B_DB_PREFIX . "current_version e
						on 1=1
						where del_flag='0' ",

	'empty_message'	=> '<strong>　' . __('No record found') . '</strong>',

	'thead'	=>
	array(
		'start_html'	=> '<thead>',
		'end_html'		=> '</thead>',
	),

	'tbody'	=>
	array(
		'start_html'		=> '<tbody>',
		'start_html_sort'	=> '<tbody>',
		'end_html'			=> '</tbody>',
	),

	'header'	=>
	array(
		'start_html'	=> '<tr>',
		'end_html'		=> '</tr>',
		'class'			=> 'B_Row',
		array(
			'name'			=> 'reserved_version_id',
			'start_html'	=> '<th class="center" style="width:35px" >',
			'end_html'		=> '</th>',
			'value'			=> __('Publish'),
		),
		array(
			'name'			=> 'working_version_id',
			'start_html'	=> '<th class="center" style="width:35px" >',
			'end_html'		=> '</th>',
			'value'			=> __('Working'),
		),
		array(
			'name'			=> 'version_id',
			'start_html'	=> '<th class="center">',
			'end_html'		=> '</th>',
			'value'			=> __('ID'),
		),
		array(
			'name'			=> 'publication_date',
			'start_html'	=> '<th class="center">',
			'end_html'		=> '</th>',
			'value'			=> __('Publish date/time'),
		),
		array(
			'name'			=> 'version',
			'start_html'	=> '<th class="center">',
			'end_html'		=> '</th>',
			'value'			=> __('Version'),
		),
		array(
			'name'			=> 'publication_status',
			'start_html'	=> '<th class="center" style="width:50px">',
			'end_html'		=> '</th>',
			'class'			=> 'B_Link',
			'link'			=> '',
			'attr'			=> 'onclick="return false"',
			'title'			=> __('Status ■:Published  ★:Scheduled to be published'),
			'value'			=> __('Status'),
		),
		array(
			'name'			=> 'notes',
			'start_html'	=> '<th class="center">',
			'end_html'		=> '</th>',
			'value'			=> __('Notes'),
		),
		array(
			'start_html'	=> '<th class="center" style="width:40px">',
			'end_html'		=> '</th>',
			'value'			=> __('Edit'),
		),
		array(
			'start_html'	=> '<th class="center" style="width:40px">',
			'end_html'		=> '</th>',
			'value'			=> __('Compare'),
		),
		array(
			'start_html'	=> '<th class="center" style="width:40px">',
			'end_html'		=> '</th>',
			'value'			=> __('Delete'),
		),
	),

	'row'		=>
	array(
		'start_html'	=> '<tr>',
		'end_html'		=> '</tr>',
		'class'			=> 'B_Row',
		array(
			'start_html'	=> '<td class="center">',
			'end_html'		=> '</td>',
			'class'			=> 'B_Radio',
			'name'			=> 'reserved_version_id',
			'attr'			=> 'class="radio"',
			'value_index'	=> 'version_id',
		),
		array(
			'start_html'	=> '<td class="center">',
			'end_html'		=> '</td>',
			'class'			=> 'B_Radio',
			'name'			=> 'working_version_id',
			'attr'			=> 'class="radio"',
			'value_index'	=> 'version_id',
		),
		array(
			'start_html'	=> '<td class="center">',
			'end_html'		=> '</td>',
			'name'			=> 'version_id',
		),
		array(
			'start_html'	=> '<td class="left">',
			'end_html'		=> '</td>',
			'name'			=> 'publication_datetime_t',
		),
		array(
			'start_html'	=> '<td class="left">',
			'end_html'		=> '</td>',
			'name'			=> 'version',
		),
		array(
			'name'			=> 'publication_status',
			'class'			=> 'B_SelectedText',
			'start_html'	=> '<td class="status">',
			'end_html'		=> '</td>',
			'data_set'		=> 'publication_status',
		),
		array(
			'name'			=> 'notes',
			'class'			=> 'B_Text',
			'start_html'	=> '<td class="left">',
			'end_html'		=> '</td>',
			'shorten_text'	=> '50',
		),
		array(
			'start_html'	=> '<td>',
			'end_html'		=> '</td>',
			'element'		=>
			array(
				'name'			=> 'edit',
				'class'			=> 'B_Link',
				'link'			=> 'index.php',
				'attr'			=> 'class="edit-button"',
				'fixedparam'	=>
				array(
					'terminal_id'	=> TERMINAL_ID,
					'module'		=> $this->module, 
					'page'			=> 'form', 
					'method'		=> 'select',
					'mode'			=> 'update',
				),
				'param'		=>
				array(
					'version_id'	=> 'version_id',
				),
				array(
					'value'			=> __('Edit'),
					'start_html'	=> '<span>',
					'end_html'		=> '</span>',
				),
			),
		),
		array(
			'start_html'	=> '<td>',
			'end_html'		=> '</td>',
			'element'		=>
			array(
				'name'			=> 'compare_enable',
				'class'			=> 'B_Link',
				'link'			=> 'index.php',
				'attr'			=> 'class="compare-button" onclick="window.open(this.href); return false;"',
				'fixedparam'	=>
				array(
					'terminal_id'	=> __getRandomText(12),
					'module'		=> 'compare', 
					'page'			=> 'index', 
					'method'		=> 'init',
				),
				'param'		=>
				array(
					'version_id'	=> 'version_id',
				),
				array(
					'value'			=> __('Compare'),
					'start_html'	=> '<span>',
					'end_html'		=> '</span>',
				)
			),
			array(
				'name'			=> 'compare_disable',
				'class'			=> 'B_Link',
				'attr'			=> 'class="compare-button-disable" onclick="return false;"',
				'display'		=> 'none',
				array(
					'value'			=> __('Compare'),
					'start_html'	=> '<span>',
					'end_html'		=> '</span>',
				)
			),
		),
		array(
			'start_html'	=> '<td>',
			'end_html'		=> '</td>',
			'element'		=>
			array(
				'name'			=> 'del_enable',
				'class'			=> 'B_Link',
				'link'			=> 'index.php',
				'attr'			=> 'class="delete-button"',
				'display'		=> 'none',
				'fixedparam'	=>
				array(
					'terminal_id'	=> TERMINAL_ID,
					'module'		=> $this->module, 
					'page'			=> 'form', 
					'method'		=> 'select',
					'mode'			=> 'delete',
				),
				'param'		=>
				array(
					'version_id'	=> 'version_id',
				),
				array(
					'value'			=> __('Delete'),
					'start_html'	=> '<span>',
					'end_html'		=> '</span>',
				)
			),
			array(
				'name'			=> 'del_disable',
				'class'			=> 'B_Link',
				'attr'			=> 'class="delete-button-disable" onclick="return false;"',
				array(
					'value'			=> __('Delete'),
					'start_html'	=> '<span>',
					'end_html'		=> '</span>',
				)
			),
		),
	),

	// pager
	'pager'		=> $this->pager_config,
);

// Control
$version_control_config = array(
	'start_html'	=> '<div id="version-control">',
	'end_html'		=> '</div>',
	array(
		'name'			=> 'confirm',
		'start_html'	=> '<span class="version-button" onclick="bframe.submit(\'F1\', \'' . $this->module . '\', \'list\', \'confirm\', \'\', true)">',
		'end_html'		=> '</span>',
		'value'			=> '<img src="images/common/version.png" alt="Change versions" />' . __('Change versions'),
	),
);
$version_control_confirm_config = array(
	'start_html'	=> '<div id="version-control">',
	'end_html'		=> '</div>',
	array(
		'start_html'	=> '<ul>',
		'end_html'		=> '</ul>',
		array(
			'start_html'	=> '<li>',
			'end_html'		=> '</li>',
			'class'			=> 'B_Button',
			'name'			=> 'back',
			'attr'			=> 'class="back-button" onclick="bframe.submit(\'F1\', \'' . $this->module . '\', \'list\', \'back\', \'\', true)"',
			'value'			=> __('Back'),
		),
		array(
			'start_html'	=> '<li>',
			'end_html'		=> '</li>',
			'class'			=> 'B_Button',
			'name'			=> 'attendance',
			'attr'			=> 'class="register-button" onclick="bframe.submit(\'F1\', \'' . $this->module . '\', \'list\', \'register\', \'\', true)"',
			'value'			=> __('Submit'),
		),
	),
);
$version_control_result_config = array(
	'start_html'	=> '<div id="version-control">',
	'end_html'		=> '</div>',
	array(
		'start_html'	=> '<ul>',
		'end_html'		=> '</ul>',
		array(
			'start_html'	=> '<li>',
			'end_html'		=> '</li>',
			'class'			=> 'B_Button',
			'name'			=> 'backToList',
			'attr'			=> 'class="back-button" onclick="bframe.submit(\'F1\', \'' . $this->module . '\', \'list\', \'back\', \'\')" ',
			'value'			=> __('Back to list'),
		),
	),
);

// Version information
$version_info_config = array(
	array(
		'start_html'	=> '<div class="publish">',
		'end_html'		=> '</div>',
		array(
			'start_html'	=> '<p class="title">',
			'end_html'		=> '</p>',
			array(
				'start_html'	=> '<span>',
				'end_html'		=> '</span>',
				'value'			=> __('Published version'),
			),
		),
		array(
			'name'			=> 'reserved_version_name',
			'start_html'	=> '<p class="version-name">',
			'end_html'		=> '</p>',
		),
		array(
			'name'			=> 'reserved_datetime',
			'start_html'	=> '<p class="date-time">',
			'end_html'		=> '</p>',
			'empty'			=> 'none',
		),
		array(
			'name'			=> 'publish_caution',
			'start_html'	=> '<p class="caution">',
			'end_html'		=> '</p>',
			'empty'			=> 'none',
		),
		array(
			'name'			=> 'publish_message',
			'start_html'	=> '<p class="message">',
			'end_html'		=> '</p>',
			'empty'			=> 'none',
		),
	),
	array(
		'start_html'	=> '<div class="working">',
		'end_html'		=> '</div>',
		array(
			'start_html'	=> '<p class="title">',
			'end_html'		=> '</p>',
			array(
				'start_html'	=> '<span>',
				'end_html'		=> '</span>',
				'value'			=> __('Working version'),
			),
		),
		array(
			'name'			=> 'working_version_name',
			'start_html'	=> '<p class="version-name">',
			'end_html'		=> '</p>',
		),
	),
);
