<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$list_config = 
array(
	'start_html'	=> '<table class="list" id="entry_list">',
	'end_html'		=> '</table>',

	'select_sql'	=> "select  id
							  , user_id
							  , concat(f_name, ' ', g_name) name
							  , user_auth
							  , user_status
						from " . B_DB_PREFIX . "user
						where 1=1 ",

	'empty_message'	=> '該当レコードはありません',
	'header'	=>
	array(
		'start_html'	=> '<tr>',
		'end_html'		=> '</tr>',
		'class'			=> 'B_Row',
		array(
			'name'			=> 'user_id',
			'start_html'	=> '<th>',
			'end_html'		=> '</th>',
			'value'			=> 'ユーザID',
			'class'			=> 'B_Link',
			'link'			=> DISPATCH_URL,
			'cond_html'		=> 'class="current-key"',
			'sort_key'		=> 'user_id',
			'param'			=> '&amp;module=' . $this->module . '&amp;page=list&amp;method=sort&amp;sort_key=user_id',
		),
		array(
			'name'			=> 'name',
			'start_html'	=> '<th>',
			'end_html'		=> '</th>',
			'value'			=> '氏名',
			'class'			=> 'B_Link',
			'link'			=> DISPATCH_URL,
			'cond_html'		=> 'class="current-key"',
			'sort_key'		=> 'name_kana',
			'param'			=> '&amp;module=' . $this->module . '&amp;page=list&amp;method=sort&amp;sort_key=name_kana',
		),
		array(
			'name'			=> 'user_auth',
			'start_html'	=> '<th>',
			'end_html'		=> '</th>',
			'value'			=> '権限',
			'class'			=> 'B_Link',
			'link'			=> DISPATCH_URL,
			'cond_html'		=> 'class="current-key"',
			'sort_key'		=> 'user_auth',
			'param'			=> '&amp;module=' . $this->module . '&amp;page=list&amp;method=sort&amp;sort_key=user_auth',
		),
		array(
			'name'			=> 'user_status',
			'start_html'	=> '<th>',
			'end_html'		=> '</th>',
			'value'			=> '状態',
			'class'			=> 'B_Link',
			'link'			=> DISPATCH_URL,
			'cond_html'		=> 'class="current-key"',
			'sort_key'		=> 'user_status',
			'param'			=> '&amp;module=' . $this->module . '&amp;page=list&amp;method=sort&amp;sort_key=user_status',
		),
		array(
			'start_html'	=> '<th nowrap>',
			'end_html'		=> '</th>',
			'value'			=> '詳細',
		),
		array(
			'start_html'	=> '<th nowrap>',
			'end_html'		=> '</th>',
			'value'			=> '削除',
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
			'start_html'	=> '<td>',
			'end_html'		=> '</td>',
			'element'		=>
			array(
				'name'			=> 'detail',
				'class'			=> 'B_Link',
				'link'			=> 'index.php',
				'special_html'	=> 'class="button"',
				'value'			=> '詳細',
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
				'special_html'	=> 'class="button"',
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
					'id'	=> 'id',
				),
			),
		),
	),

	// pager
	'pager'		=> $this->pager_config,
);
