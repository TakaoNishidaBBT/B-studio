<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$list_config = 
array(
	'start_html'	=> '<table class="list bframe_elastic" id="entry_list">',
	'end_html'		=> '</table>',

	'select_sql'	=> "select   id
								,user_id
								,user_name
								,user_auth
								,user_status
								,language
								,notes
						from " . B_DB_PREFIX . "user
						where 1=1 ",

	'empty_message'	=> '<span class="bold">　' . __('No record found') . '</span>',

	'thead'	=>
	array(
		'start_html'	=> '<thead>',
		'end_html'		=> '</thead>',
	),

	'tbody'	=>
	array(
		'start_html'	=> '<tbody>',
		'end_html'		=> '</tbody>',
	),

	'header'	=>
	array(
		'start_html'	=> '<tr>',
		'end_html'		=> '</tr>',
		'class'			=> 'B_Row',
		array(
			'name'				=> 'user_id',
			'start_html'		=> '<th class="sortable" style="width:80px">',
			'start_html_asc'	=> '<th class="sortable asc" style="width:80px">',
			'start_html_desc'	=> '<th class="sortable desc" style="width:80px">',
			'end_html'			=> '</th>',
			'value'				=> __('User ID'),
			'class'				=> 'B_Link',
			'link'				=> DISPATCH_URL,
			'cond_html'			=> 'class="current-key"',
			'sort_key'			=> 'user_id',
			'param'				=> '&amp;module=' . $this->module . '&amp;page=list&amp;method=sort&amp;sort_key=user_id',
		),
		array(
			'name'				=> 'user_name',
			'start_html'		=> '<th class="sortable" style="width:80px">',
			'start_html_asc'	=> '<th class="sortable asc" style="width:80px">',
			'start_html_desc'	=> '<th class="sortable desc" style="width:80px">',
			'end_html'			=> '</th>',
			'value'				=> __('Name'),
			'class'				=> 'B_Link',
			'link'				=> DISPATCH_URL,
			'cond_html'			=> 'class="current-key"',
			'sort_key'			=> 'user_name',
			'param'				=> '&amp;module=' . $this->module . '&amp;page=list&amp;method=sort&amp;sort_key=user_name',
		),
		array(
			'name'				=> 'user_auth',
			'start_html'		=> '<th class="sortable" style="width:90px">',
			'start_html_asc'	=> '<th class="sortable asc" style="width:90px">',
			'start_html_desc'	=> '<th class="sortable desc" style="width:90px">',
			'end_html'			=> '</th>',
			'value'				=> __('User type'),
			'class'				=> 'B_Link',
			'link'				=> DISPATCH_URL,
			'cond_html'			=> 'class="current-key"',
			'sort_key'			=> 'user_auth',
			'param'				=> '&amp;module=' . $this->module . '&amp;page=list&amp;method=sort&amp;sort_key=user_auth',
		),
		array(
			'name'				=> 'user_status',
			'start_html'		=> '<th class="sortable" style="width:80px">',
			'start_html_asc'	=> '<th class="sortable asc" style="width:80px">',
			'start_html_desc'	=> '<th class="sortable desc" style="width:80px">',
			'end_html'			=> '</th>',
			'value'				=> __('Status'),
			'class'				=> 'B_Link',
			'link'				=> DISPATCH_URL,
			'cond_html'			=> 'class="current-key"',
			'sort_key'			=> 'user_status',
			'param'				=> '&amp;module=' . $this->module . '&amp;page=list&amp;method=sort&amp;sort_key=user_status',
		),
		array(
			'name'				=> 'language',
			'start_html'		=> '<th class="sortable" style="width:100px">',
			'start_html_asc'	=> '<th class="sortable asc" style="width:100px">',
			'start_html_desc'	=> '<th class="sortable desc" style="width:100px">',
			'end_html'			=> '</th>',
			'value'				=> __('Language'),
			'class'				=> 'B_Link',
			'link'				=> DISPATCH_URL,
			'cond_html'			=> 'class="current-key"',
			'sort_key'			=> 'language',
			'param'				=> '&amp;module=' . $this->module . '&amp;page=list&amp;method=sort&amp;sort_key=language',
		),
		array(
			'name'				=> 'notes',
			'start_html'		=> '<th class="sortable" style="width:250px">',
			'start_html_asc'	=> '<th class="sortable asc" style="width:250px">',
			'start_html_desc'	=> '<th class="sortable desc" style="width:250px">',
			'end_html'			=> '</th>',
			'value'				=> __('Notes'),
			'class'				=> 'B_Link',
			'link'				=> DISPATCH_URL,
			'cond_html'			=> 'class="current-key"',
			'sort_key'			=> 'notes',
			'param'				=> '&amp;module=' . $this->module . '&amp;page=list&amp;method=sort&amp;sort_key=notes',
		),
		array(
			'start_html'		=> '<th class="center" style="width:60px"><span>',
			'end_html'			=> '</span></th>',
			'value'				=> __('Edit'),
		),
		array(
			'start_html'		=> '<th class="center" style="width:60px"><span>',
			'end_html'			=> '</span></th>',
			'value'				=> __('Delete'),
		),
	),

	'row'		=>
	array(
		'start_html'			=> '<tr>',
		'start_html_invalid'	=> '<tr class="invalid">',
		'end_html'				=> '</tr>',
		'class'					=> 'B_Row',
		array(
			'start_html'	=> '<td class="left">',
			'end_html'		=> '</td>',
			'class'			=> 'B_Text',
			'name'			=> 'user_id',
		),
		array(
			'start_html'	=> '<td class="left">',
			'end_html'		=> '</td>',
			'class'			=> 'B_Text',
			'name'			=> 'user_name',
		),
		array(
			'start_html'	=> '<td class="left">',
			'end_html'		=> '</td>',
			'class'			=> 'B_SelectedText',
			'data_set'		=> 'user_auth',
			'name'			=> 'user_auth',
		),
		array(
			'start_html'	=> '<td class="left">',
			'end_html'		=> '</td>',
			'class'			=> 'B_SelectedText',
			'data_set'		=> 'user_status',
			'name'			=> 'user_status',
		),
		array(
			'start_html'	=> '<td class="left">',
			'end_html'		=> '</td>',
			'class'			=> 'B_SelectedText',
			'data_set'		=> 'language',
			'name'			=> 'language',
		),
		array(
			'name'			=> 'notes',
			'class'			=> 'B_Text',
			'start_html'	=> '<td class="left">',
			'end_html'		=> '</td>',
			'shorten_text'	=> '50',
		),
		array(
			'start_html'	=> '<td class="button">',
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
					'id'	=> 'id',
				),
				array(
					'value'			=> __('Edit'),
					'start_html'	=> '<span>',
					'end_html'		=> '</span>',
				),
			),
		),
		array(
			'start_html'	=> '<td class="button">',
			'end_html'		=> '</td>',
			'element'		=>
			array(
				'name'			=> 'delete',
				'class'			=> 'B_Link',
				'link'			=> 'index.php',
				'attr'			=> 'class="delete-button"',
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
					'id'	=> 'id',
				),
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
