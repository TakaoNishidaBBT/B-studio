<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$calendar_config = 
array(
	'caption'	=>
	array(
		array(
			'name'			=> 'prev_month',
			'class'			=> 'B_Link',
			'value'			=> '<img src="./images/common/prev.png" alt="＜" width="23" height="46" />',
			'specialchars'	=> 'none',
		),
		array(
			'start_html'	=> '<span>',
			'end_html'		=> '</span>',
			array(
				'name'			=> 'year',
				'no_linefeed'	=> true,
			),
			array(
				'value'			=> '年',
				'no_linefeed'	=> true,
			),
			array(
				'name'			=> 'month',
				'no_linefeed'	=> true,
			),
			array(
				'value'			=> '月',
			),
		),
		array(
			'class'			=> 'B_Link',
			'name'			=> 'next_month',
			'value'			=> '<img src="./images/common/next.png" alt="＞" width="23" height="46" />',
			'specialchars'	=> 'none',
		),
	),

	'grid'		=>
	array(
		'start_html'	=> '<table class="calendar">',
		'end_html'		=> '</table>',

		'header'	=>
		array(
			'start_html'	=> '<tr>',
			'end_html'		=> '</tr>',
			array(
				'name'			=> 'sun',
				'start_html'	=> '<th class="sun">',
				'end_html'		=> '</th>',
				'value'			=> '日',
			),
			array(
				'name'			=> 'mon',
				'start_html'	=> '<th>',
				'end_html'		=> '</th>',
				'value'			=> '月',
			),
			array(
				'name'			=> 'tue',
				'start_html'	=> '<th>',
				'end_html'		=> '</th>',
				'value'			=> '火',
			),
			array(
				'name'			=> 'wed',
				'start_html'	=> '<th>',
				'end_html'		=> '</th>',
				'value'			=> '水',
			),
			array(
				'name'			=> 'thu',
				'start_html'	=> '<th>',
				'end_html'		=> '</th>',
				'value'			=> '木',
			),
			array(
				'name'			=> 'fri',
				'start_html'	=> '<th>',
				'end_html'		=> '</th>',
				'value'			=> '金',
			),
			array(
				'name'			=> 'sat',
				'start_html'	=> '<th class="sat">',
				'end_html'		=> '</th>',
				'value'			=> '土',
			),
		),

		'row'		=>
		array(
			'start_html'			=> '<tr>',
			'end_html'				=> '</tr>',
			array(
				'name'				=> '1',
				'start_html'		=> '<td class="sun day">',
				'start_html_empty'	=> '<td class="sun">',
				'end_html'			=> '</td>',
			),
			array(
				'name'				=> '2',
				'start_html'		=> '<td class="day">',
				'start_html_empty'	=> '<td>',
				'end_html'			=> '</td>',
			),
			array(
				'name'				=> '3',
				'start_html'		=> '<td class="day">',
				'start_html_empty'	=> '<td>',
				'end_html'			=> '</td>',
			),
			array(
				'name'				=> '4',
				'start_html'		=> '<td class="day">',
				'start_html_empty'	=> '<td>',
				'end_html'			=> '</td>',
			),
			array(
				'name'				=> '5',
				'start_html'		=> '<td class="day">',
				'start_html_empty'	=> '<td>',
				'end_html'			=> '</td>',
			),
			array(
				'name'				=> '6',
				'start_html'		=> '<td class="day">',
				'start_html_empty'	=> '<td>',
				'end_html'			=> '</td>',
			),
			array(
				'name'				=> '7',
				'start_html'		=> '<td class="sat day">',
				'start_html_empty'	=> '<td class="sat">',
				'end_html'			=> '</td>',
			),
		),
	),
);
