<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$list_config = array(
	'start_html'	=> '<table class="list bframe_elastic">',
	'end_html'		=> '</table>',

	'select_sql'	=> "select * from " . B_DB_PREFIX . "mail_settings where del_flag='0' ",

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
		'class'			=> 'B_Row',
		'start_html'	=> '<tr>',
		'end_html'		=> '</tr>',
		array(
			'name'				=> 'mail_id',
			'class'				=> 'B_Link',
			'start_html'		=> '<th class="sortable">',
			'start_html_asc'	=> '<th class="sortable asc">',
			'start_html_desc'	=> '<th class="sortable desc">',
			'end_html'			=> '</th>',
			'value'				=> 'ID',
			'link'				=> DISPATCH_URL,
			'cond_html'			=> 'class="current-key"',
			'sort_key'			=> 'mail_id',
			'param'				=> '&amp;module=' . $this->module . '&amp;page=list&amp;method=sort&amp;sort_key=mail_id',
		),
		array(
			'name'				=> 'mail_type',
			'start_html'		=> '<th class="sortable" style="width:140px">',
			'start_html_asc'	=> '<th class="sortable asc" style="width:140px">',
			'start_html_desc'	=> '<th class="sortable desc" style="width:140px">',
			'end_html'			=> '</th>',
			'value'				=> __('Mail Type'),
			'class'				=> 'B_Link',
			'link'				=> DISPATCH_URL,
			'cond_html'			=> 'class="current-key"',
			'sort_key'			=> 'mail_type',
			'param'				=> '&amp;module=' . $this->module . '&amp;page=list&amp;method=sort&amp;sort_key=mail_type',
		),
		array(
			'name'				=> 'mail_title',
			'start_html'		=> '<th class="sortable" style="width:200px">',
			'start_html_asc'	=> '<th class="sortable asc" style="width:200px">',
			'start_html_desc'	=> '<th class="sortable desc" style="width:200px">',
			'end_html'			=> '</th>',
			'value'				=> __('Mail Title'),
			'class'				=> 'B_Link',
			'link'				=> DISPATCH_URL,
			'cond_html'			=> 'class="current-key"',
			'sort_key'			=> 'mail_title',
			'param'				=> '&amp;module=' . $this->module . '&amp;page=list&amp;method=sort&amp;sort_key=mail_title',
		),
		array(
			'name'				=> 'subject',
			'start_html'		=> '<th class="sortable" style="width:300px">',
			'start_html_asc'	=> '<th class="sortable asc" style="width:300px">',
			'start_html_desc'	=> '<th class="sortable desc" style="width:300px">',
			'end_html'			=> '</th>',
			'value'				=> __('Subject'),
			'class'				=> 'B_Link',
			'link'				=> DISPATCH_URL,
			'cond_html'			=> 'class="current-key"',
			'sort_key'			=> 'subject',
			'param'				=> '&amp;module=' . $this->module . '&amp;page=list&amp;method=sort&amp;sort_key=subject',
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
		'start_html_alt'		=> '<tr class="alt">',
		'end_html'				=> '</tr>',
		'class'					=> 'B_Row',
		array(
			'name'			=> 'mail_id',
			'start_html'	=> '<td class="center">',
			'end_html'		=> '</td>',
		),
		array(
			'name'			=> 'mail_type',
			'start_html'	=> '<td class="left">',
			'end_html'		=> '</td>',
			'class'			=> 'B_SelectedText',
			'data_set'		=> 'mail_type_settings',
		),
		array(
			'name'			=> 'mail_title',
			'start_html'	=> '<td class="left mail-title">',
			'end_html'		=> '</td>',
		),
		array(
			'name'			=> 'subject',
			'start_html'	=> '<td class="left subject">',
			'end_html'		=> '</td>',
		),
		array(
			'start_html'	=> '<td class="button">',
			'end_html'		=> '</td>',
			'element'		=>
			array(
				'name'			=> 'edit',
				'class'			=> 'B_Link',
				'link'			=> 'index.php',
				'attr'	=> 'class="edit-button"',
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
					'mail_id'	=> 'mail_id',
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
				'name'			=> 'del',
				'class'			=> 'B_Link',
				'link'			=> 'index.php',
				'attr'	=> 'class="delete-button"',
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
					'mail_id'	=> 'mail_id',
				),
				array(
					'value'			=> __('Delete'),
					'start_html'	=> '<span>',
					'end_html'		=> '</span>',
				),
			),
		),
	),

	// pager
	'pager'		=> $this->pager_config,
);
