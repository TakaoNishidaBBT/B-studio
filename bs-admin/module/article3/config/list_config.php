<?php
/*
 * B-studio : Content Management System
 * Copyright (c) BigBeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$list_config = array(
	'start_html'	=> '<table class="list">',
	'end_html'		=> '</table>',

	'select_sql'	=> "select * from " . B_DB_PREFIX . "v_admin_article3 where 1=1 ",

	'empty_message'	=> '<span class="bold">　' . __('No record was found') . '</span>',

	'header'	=>
	array(
		'class'			=> 'B_Row',
		'start_html'	=> '<tr>',
		'end_html'		=> '</tr>',
		array(
			'name'			=> 'article_id',
			'class'			=> 'B_Link',
			'start_html'	=> '<th class="sortable">',
			'end_html'		=> '</th>',
			'value'			=> 'ID',
			'link'			=> DISPATCH_URL,
			'cond_html'		=> 'class="current-key"',
			'sort_key'		=> 'article_id',
			'param'			=> '&amp;module=' . $this->module . '&amp;page=list&amp;method=sort&amp;sort_key=article_id',
		),
		array(
			'name'			=> 'article_date',
			'class'			=> 'B_Link',
			'start_html'	=> '<th class="sortable">',
			'end_html'		=> '</th>',
			'value'			=> __('Date'),
			'link'			=> DISPATCH_URL,
			'cond_html'		=> 'class="current-key"',
			'sort_key'		=> 'article_date',
			'param'			=> '&amp;module=' . $this->module . '&amp;page=list&amp;method=sort&amp;sort_key=article_date',
		),
		array(
			'name'			=> 'category',
			'class'			=> 'B_Link',
			'start_html'	=> '<th class="sortable" style="width:100px">',
			'end_html'		=> '</th>',
			'value'			=> __('Category'),
			'link'			=> DISPATCH_URL,
			'cond_html'		=> 'class="current-key"',
			'sort_key'		=> 'category',
			'param'			=> '&amp;module=' . $this->module . '&amp;page=list&amp;method=sort&amp;sort_key=category',
		),
		array(
			'name'			=> 'title',
			'class'			=> 'B_Link',
			'start_html'	=> '<th class="sortable" style="width:260px">',
			'end_html'		=> '</th>',
			'value'			=> __('Title'),
			'link'			=> DISPATCH_URL,
			'cond_html'		=> 'class="current-key"',
			'sort_key'		=> 'title',
			'param'			=> '&amp;module=' . $this->module . '&amp;page=list&amp;method=sort&amp;sort_key=title',
		),
		array(
			'name'			=> 'publication',
			'class'			=> 'B_Link',
			'start_html'	=> '<th class="sortable" style="width:55px">',
			'end_html'		=> '</th>',
			'value'			=> __('Status'),
			'title'			=> __('Published/Preview/Closed'),
			'link'			=> DISPATCH_URL,
			'cond_html'		=> 'class="current-key"',
			'sort_key'		=> 'publication',
			'param'			=> '&amp;module=' . $this->module . '&amp;page=list&amp;method=sort&amp;sort_key=publication',
		),
		array(
			'start_html'	=> '<th class="center" style="width:40px" nowrap>',
			'end_html'		=> '</th>',
			'value'			=> __('Edit'),
		),
		array(
			'start_html'	=> '<th class="center" style="width:40px" nowrap>',
			'end_html'		=> '</th>',
			'value'			=> __('Delete'),
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
			'name'			=> 'article_id',
			'start_html'	=> '<td class="center">',
			'end_html'		=> '</td>',
		),
		array(
			'name'			=> 'article_date_t',
			'start_html'	=> '<td class="left">',
			'end_html'		=> '</td>',
		),
		array(
			'name'			=> 'category',
			'start_html'	=> '<td class="left">',
			'end_html'		=> '</td>',
		),
		array(
			'name'			=> 'title',
			'start_html'	=> '<td class="left">',
			'end_html'		=> '</td>',
			'shorten_text'	=> '100',
			'trimmarker'	=> '…',
			'strip_tags'	=> true,
		),
		array(
			'name'			=> 'publication',
			'class'			=> 'B_SelectedText',
			'start_html'	=> '<td class="center">',
			'end_html'		=> '</td>',
			'data_set'		=> 'publication',
		),
		array(
			'start_html'	=> '<td class="button">',
			'end_html'		=> '</td>',
			'element'		=>
			array(
				'name'			=> 'edit',
				'class'			=> 'B_Link',
				'link'			=> 'index.php',
				'value'			=> '編集',
				'special_html'	=> 'class="edit-button"',
				'fixedparam'	=>
				array(
					'terminal_id'	=> TERMINAL_ID,
					'module'		=> $this->module, 
					'page'			=> 'form', 
					'method'		=> 'select',
				),
				'param'		=>
				array(
					'article_id'	=> 'article_id',
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
				'special_html'	=> 'class="delete-button"',
				'value'			=> '削除',
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
					'article_id'	=> 'article_id',
				),
			),
		),
	),

	// pager
	'pager'		=> $this->pager_config,
);
