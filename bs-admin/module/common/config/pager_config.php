<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$pager_config =
array(
	'row_per_page'	=> '10',
	'page_link_max'	=> '5',
	'start_html'	=> '<ul class="page-locator"><li class="title">' . __('Page:') . '</li>',
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
			'value'			=> '<img src="images/pagenation/top.png" alt="top" />',
		),
		'prev_image'	=>
		array(
			'start_html'	=> '<li>',
			'end_html'		=> '</li>',
			'value'			=> '<img src="images/pagenation/prev.png" alt="prev" />',
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
			'value'			=> '<img src="images/pagenation/next.png" alt="next" />',
		),
		'last_image'	=>
		array(
			'start_html'	=> '<li>',
			'end_html'		=> '</li>',
			'value'			=> '<img src="images/pagenation/last.png" alt="next" />',
		),
		'information'	=> '<li class="page_info">' . __('Displaying %RECORD_FROM% to %RECORD_TO% of %TOTAL% items') . '</li>'
	),
);
