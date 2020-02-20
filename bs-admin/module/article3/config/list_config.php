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

	'select_sql'	=> "select b.total_cnt, c.access_cnt, a.* from " . B_DB_PREFIX . "v_admin_article3 a
						left join (
							select article_id, count(*) total_cnt from " . B_DB_PREFIX . "access_log3
							group by article_id) b
						on a.article_id = b.article_id
						left join (
							select article_id, count(*) access_cnt from " . B_DB_PREFIX . "access_log3
							where (create_datetime + 3600 * 24 * 30) >= unix_timestamp(now())
							group by article_id) c
						on a.article_id = c.article_id
						where 1 ",

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
			'name'				=> 'article_id',
			'class'				=> 'B_Link',
			'start_html'		=> '<th class="sortable">',
			'start_html_asc'	=> '<th class="sortable asc">',
			'start_html_desc'	=> '<th class="sortable desc">',
			'end_html'			=> '</th>',
			'value'				=> 'ID',
			'link'				=> DISPATCH_URL,
			'cond_html'			=> 'class="current-key"',
			'sort_key'			=> 'article_id',
			'param'				=> '&amp;module=' . $this->module . '&amp;page=list&amp;method=sort&amp;sort_key=article_id',
		),
		array(
			'name'				=> 'article_date',
			'class'				=> 'B_Link',
			'start_html'		=> '<th class="sortable">',
			'start_html_asc'	=> '<th class="sortable asc">',
			'start_html_desc'	=> '<th class="sortable desc">',
			'end_html'			=> '</th>',
			'value'				=> __('Date'),
			'link'				=> DISPATCH_URL,
			'cond_html'			=> 'class="current-key"',
			'sort_key'			=> 'article_date',
			'param'				=> '&amp;module=' . $this->module . '&amp;page=list&amp;method=sort&amp;sort_key=article_date',
		),
		array(
			'name'				=> 'slug',
			'class'				=> 'B_Link',
			'start_html'		=> '<th class="sortable" style="width:100px">',
			'start_html_asc'	=> '<th class="sortable asc" style="width:100px">',
			'start_html_desc'	=> '<th class="sortable desc" style="width:100px">',
			'end_html'			=> '</th>',
			'value'				=> __('Slug'),
			'link'				=> DISPATCH_URL,
			'cond_html'			=> 'class="current-key"',
			'sort_key'			=> 'slug',
			'param'				=> '&amp;module=' . $this->module . '&amp;page=list&amp;method=sort&amp;sort_key=slug',
		),
		array(
			'name'				=> 'category',
			'class'				=> 'B_Link',
			'start_html'		=> '<th class="sortable" style="width:100px">',
			'start_html_asc'	=> '<th class="sortable asc" style="width:100px">',
			'start_html_desc'	=> '<th class="sortable desc" style="width:100px">',
			'end_html'			=> '</th>',
			'value'				=> __('Category'),
			'link'				=> DISPATCH_URL,
			'cond_html'			=> 'class="current-key"',
			'sort_key'			=> 'category',
			'param'				=> '&amp;module=' . $this->module . '&amp;page=list&amp;method=sort&amp;sort_key=category',
		),
		array(
			'name'				=> 'tags',
			'class'				=> 'B_Link',
			'start_html'		=> '<th class="sortable" style="width:300px">',
			'start_html_asc'	=> '<th class="sortable asc" style="width:300px">',
			'start_html_desc'	=> '<th class="sortable desc" style="width:300px">',
			'end_html'			=> '</th>',
			'value'				=> __('Tags'),
			'link'				=> DISPATCH_URL,
			'cond_html'			=> 'class="current-key"',
			'sort_key'			=> 'tags',
			'param'				=> '&amp;module=' . $this->module . '&amp;page=list&amp;method=sort&amp;sort_key=tags',
		),
		array(
			'name'				=> 'headline',
			'class'				=> 'B_Link',
			'start_html'		=> '<th class="sortable" style="width:400px">',
			'start_html_asc'	=> '<th class="sortable asc" style="width:400px">',
			'start_html_desc'	=> '<th class="sortable desc" style="width:400px">',
			'end_html'			=> '</th>',
			'value'				=> __('Title'),
			'link'				=> DISPATCH_URL,
			'cond_html'			=> 'class="current-key"',
			'sort_key'			=> 'headline',
			'param'				=> '&amp;module=' . $this->module . '&amp;page=list&amp;method=sort&amp;sort_key=headline',
		),
		array(
			'name'				=> 'total_cnt',
			'class'				=> 'B_Link',
			'start_html'		=> '<th class="sortable" style="width:80px">',
			'start_html_asc'	=> '<th class="sortable asc" style="width:80px">',
			'start_html_desc'	=> '<th class="sortable desc" style="width:80px">',
			'end_html'			=> '</th>',
			'value'				=> __('Page View(Total)'),
			'link'				=> DISPATCH_URL,
			'cond_html'			=> 'class="current-key"',
			'title'				=> __('Page View(Total)'),
			'sort_key'			=> 'total_cnt',
			'param'				=> '&amp;module=' . $this->module . '&amp;page=list&amp;method=sort&amp;sort_key=total_cnt',
		),
		array(
			'name'				=> 'access_cnt',
			'class'				=> 'B_Link',
			'start_html'		=> '<th class="sortable" style="width:80px">',
			'start_html_asc'	=> '<th class="sortable asc" style="width:80px">',
			'start_html_desc'	=> '<th class="sortable desc" style="width:80px">',
			'end_html'			=> '</th>',
			'value'				=> __('Page View(Last 30 days)'),
			'link'				=> DISPATCH_URL,
			'cond_html'			=> 'class="current-key"',
			'value'				=> __('Page View(Last 30 days)'),
			'sort_key'			=> 'access_cnt',
			'param'				=> '&amp;module=' . $this->module . '&amp;page=list&amp;method=sort&amp;sort_key=access_cnt',
		),
		array(
			'name'				=> 'publication',
			'class'				=> 'B_Link',
			'start_html'		=> '<th class="sortable" style="width:55px">',
			'start_html_asc'	=> '<th class="sortable asc" style="width:55px">',
			'start_html_desc'	=> '<th class="sortable desc" style="width:55px">',
			'end_html'			=> '</th>',
			'value'				=> __('Status'),
			'title'				=> __('Published/Preview/Closed'),
			'link'				=> DISPATCH_URL,
			'cond_html'			=> 'class="current-key"',
			'sort_key'			=> 'publication',
			'param'				=> '&amp;module=' . $this->module . '&amp;page=list&amp;method=sort&amp;sort_key=publication',
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
			'name'			=> 'slug',
			'start_html'	=> '<td class="left">',
			'end_html'		=> '</td>',
		),
		array(
			'name'			=> 'category',
			'start_html'	=> '<td class="left">',
			'end_html'		=> '</td>',
		),
		array(
			'name'			=> 'tags',
			'start_html'	=> '<td class="left tags">',
			'end_html'		=> '</td>',
		),
		array(
			'name'			=> 'headline',
			'start_html'	=> '<td class="left">',
			'end_html'		=> '</td>',
			'strip_tags'	=> true,
		),
		array(
			'name'			=> 'total_cnt',
			'start_html'	=> '<td class="right">',
			'end_html'		=> '</td>',
		),
		array(
			'name'			=> 'access_cnt',
			'start_html'	=> '<td class="right">',
			'end_html'		=> '</td>',
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
					'article_id'	=> 'article_id',
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
					'article_id'	=> 'article_id',
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
