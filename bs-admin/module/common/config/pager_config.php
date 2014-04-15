<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$pager_config =
array(
	'row_per_page'	=> '10',
	'page_link_max'	=> '5',
	'start_html'	=> '<ul class="page-locator"><li class="title">ページ ：</li>',
	'end_html'		=> '</ul>',
	'location'		=>
	array(
		'top'		=> 'true',
		'bottom'	=> 'true',
	),
	'link'			=> 'index.php',
	'param'			=>
	array(
		'terminal_id'	=> TERMINAL_ID,
		'module'		=> $this->module,
		'page'			=> 'list',
		'method'		=> 'jump',
	),
	'disp_image'	=>
	array(
		'top_image'	=>
		array(
			'start_html'	=> '<li>',
			'end_html'		=> '</li>',
			'value'			=> '<img src="images/common/top.png" alt="top" width="23" height="46" />',
		),
		'prev_image'	=>
		array(
			'start_html'	=> '<li>',
			'end_html'		=> '</li>',
			'value'			=> '<img src="images/common/prev.png" alt="prev" width="23" height="46" />',
		),
		'current_page'	=>
		array(
			'start_html'	=> '<li class="page current">',
			'end_html'		=> '</li>',
		),
		'other_page'	=>
		array(
			'start_html'	=> '<li class="page other">',
			'end_html'		=> '</li>',
		),
		'next_image'	=>
		array(
			'start_html'	=> '<li>',
			'end_html'		=> '</li>',
			'value'			=> '<img src="images/common/next.png" alt="next" width="23" height="46" />',
		),
		'last_image'	=>
		array(
			'start_html'	=> '<li>',
			'end_html'		=> '</li>',
			'value'			=> '<img src="images/common/last.png" alt="next" width="23" height="46" />',
		),
		'information'	=>
		array(
			'start_html'	=> '<li class="page_info">',
			'end_html'		=> '</li>',
			'record_cnt'	=>
			array(
				'start_html'	=> ' 全',
				'end_html'		=> '件中',
			),
			'record_from'	=>
			array(
				'start_html'	=> '&nbsp;&nbsp;',
				'end_html'		=> '～',
			),
			'record_to'	=>
			array(
				'end_html'		=> '件目',
			),
		),
	),
);
