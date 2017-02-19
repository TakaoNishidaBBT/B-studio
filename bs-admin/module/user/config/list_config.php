<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$list_config = 
array(
	'start_html'	=> '<table class="list" id="entry_list">',
	'end_html'		=> '</table>',

	'select_sql'	=> "select   id
								,user_id
								,name
								,user_auth
								,user_status
								,language
								,notes
						from " . B_DB_PREFIX . "user
						where 1=1 ",

	'empty_message'	=> '<span class="bold">　' . __('No record found') . '</span>',

	'header'	=>
	array(
		'start_html'	=> '<tr>',
		'end_html'		=> '</tr>',
		'class'			=> 'B_Row',
		array(
			'name'			=> 'user_id',
			'start_html'	=> '<th class="sortable" style="width:80px">',
			'end_html'		=> '</th>',
			'value'			=> __('User ID'),
			'class'			=> 'B_Link',
			'link'			=> DISPATCH_URL,
			'cond_html'		=> 'class="current-key"',
			'sort_key'		=> 'user_id',
			'param'			=> '&amp;module=' . $this->module . '&amp;page=list&amp;method=sort&amp;sort_key=user_id',
		),
		array(
			'name'			=> 'name',
			'start_html'	=> '<th class="sortable" style="width:80px">',
			'end_html'		=> '</th>',
			'value'			=> __('Name'),
			'class'			=> 'B_Link',
			'link'			=> DISPATCH_URL,
			'cond_html'		=> 'class="current-key"',
			'sort_key'		=> 'name',
			'param'			=> '&amp;module=' . $this->module . '&amp;page=list&amp;method=sort&amp;sort_key=name',
		),
		array(
			'name'			=> 'user_auth',
			'start_html'	=> '<th class="sortable" style="width:60px">',
			'end_html'		=> '</th>',
			'value'			=> __('User type'),
			'class'			=> 'B_Link',
			'link'			=> DISPATCH_URL,
			'cond_html'		=> 'class="current-key"',
			'sort_key'		=> 'user_auth',
			'param'			=> '&amp;module=' . $this->module . '&amp;page=list&amp;method=sort&amp;sort_key=user_auth',
		),
		array(
			'name'			=> 'user_status',
			'start_html'	=> '<th class="sortable" style="width:60px">',
			'end_html'		=> '</th>',
			'value'			=> __('Status'),
			'class'			=> 'B_Link',
			'link'			=> DISPATCH_URL,
			'cond_html'		=> 'class="current-key"',
			'sort_key'		=> 'user_status',
			'param'			=> '&amp;module=' . $this->module . '&amp;page=list&amp;method=sort&amp;sort_key=user_status',
		),
		array(
			'name'			=> 'language',
			'start_html'	=> '<th class="sortable" style="width:60px">',
			'end_html'		=> '</th>',
			'value'			=> __('Language'),
			'class'			=> 'B_Link',
			'link'			=> DISPATCH_URL,
			'cond_html'		=> 'class="current-key"',
			'sort_key'		=> 'language',
			'param'			=> '&amp;module=' . $this->module . '&amp;page=list&amp;method=sort&amp;sort_key=language',
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
			'value'			=> __('Delete'),
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
			'name'			=> 'name',
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
			'start_html'	=> '<td>',
			'end_html'		=> '</td>',
			'element'		=>
			array(
				'name'			=> 'detail',
				'class'			=> 'B_Link',
				'link'			=> 'index.php',
				'special_html'	=> 'class="edit-button"',
				'value'			=> __('Edit'),
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
			),
		),
		array(
			'start_html'	=> '<td>',
			'end_html'		=> '</td>',
			'element'		=>
			array(
				'name'			=> 'delete',
				'class'			=> 'B_Link',
				'link'			=> 'index.php',
				'special_html'	=> 'class="delete-button"',
				'value'			=> __('Delete'),
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
			),
		),
	),

	// pager
	'pager'		=> $this->pager_config,
);
