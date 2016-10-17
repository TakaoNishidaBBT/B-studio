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
			'value'			=> '<img src="./images/pagenation/prev.png" alt="＜" width="23" height="46" />',
			'specialchars'	=> 'none',
		),
		array(
			'name'			=> 'year_month',
			'start_html'	=> '<span>',
			'end_html'		=> '</span>',
			'value'			=> __('%MONTH% %YEAR%'),
		),
		array(
			'class'			=> 'B_Link',
			'name'			=> 'next_month',
			'value'			=> '<img src="./images/pagenation/next.png" alt="＞" width="23" height="46" />',
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
				'value'			=> __('Sun'),
			),
			array(
				'name'			=> 'mon',
				'start_html'	=> '<th>',
				'end_html'		=> '</th>',
				'value'			=> __('Mon'),
			),
			array(
				'name'			=> 'tue',
				'start_html'	=> '<th>',
				'end_html'		=> '</th>',
				'value'			=> __('Tue'),
			),
			array(
				'name'			=> 'wed',
				'start_html'	=> '<th>',
				'end_html'		=> '</th>',
				'value'			=> __('Wed'),
			),
			array(
				'name'			=> 'thu',
				'start_html'	=> '<th>',
				'end_html'		=> '</th>',
				'value'			=> __('Thu'),
			),
			array(
				'name'			=> 'fri',
				'start_html'	=> '<th>',
				'end_html'		=> '</th>',
				'value'			=> __('Fri'),
			),
			array(
				'name'			=> 'sat',
				'start_html'	=> '<th class="sat">',
				'end_html'		=> '</th>',
				'value'			=> __('Sat'),
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
