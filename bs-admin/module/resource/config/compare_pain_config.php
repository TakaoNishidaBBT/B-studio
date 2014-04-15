<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$compare_pain_config = array(
	'start_html'	=> '<ul>',
	'end_html'		=> '</ul>',

	'row'		=>
	array(
		'start_html'	=> '<li class="tree-list">',
		'end_html'		=> '</li>',
		array(
			'start_html'	=> '<div class="tree">',
			'end_html'		=> '</div>',
			array(
				'start_html'	=> '<a style="cursor: pointer">',
				'end_html'		=> '</a>',
				array(
					'name'					=> 'img_border',
					'start_html'			=> '<span class="img-border">',
					'start_html_diff_left'	=> '<span class="img-border diff-left">',
					'start_html_diff_right'	=> '<span class="img-border diff-right">',
					'end_html'				=> '</span>',
					array(
						'name'						=> 'icon',
						'value'						=> '<img src="./images/folders/folder_big.png" />',
						'value_folder_diff_left'	=> '<img src="./images/folders/folder_big_red.png" />',
						'value_folder_diff_right'	=> '<img src="./images/folders/folder_big_blue.png" />',
						'value_folder_diff_child'	=> '<img src="./images/folders/folder_big_purple.png" />',
						'value_file'				=> '<img src="./images/folders/file_icon_big.png" />',
						'value_file_diff_left'		=> '<img src="./images/folders/file_icon_big_red.png" />',
						'value_file_diff_right'		=> '<img src="./images/folders/file_icon_big_blue.png" />',
					),
				),
				array(
					'name'			=> 'node_name',
					'start_html'	=> '<span class="node-name" style="text-decoration: none">',
					'end_html'		=> '</span>',
				),
				array(
					'class'			=> 'B_Data',
					'name'			=> 'contents_id',
				),
				array(
					'class'			=> 'B_Data',
					'name'			=> 'update_datetime',
				),
				array(
					'class'			=> 'B_Data',
					'name'			=> 'path',
				),
				array(
					'class'			=> 'B_Data',
					'name'			=> 'node_class',
				),
				array(
					'class'			=> 'B_Data',
					'name'			=> 'node_status',
				),
			),
		),
	),
);
$compare_pain_detail_config = array(
	'start_html'	=> '<table class="file-list">',
	'end_html'		=> '</table>',

	'select_sql'	=> "select * from " . B_DB_PREFIX . "v_article where 1=1 ",

	'empty_message'	=> '<strong>　該当レコードはありません</strong>',

	'header'	=>
	array(
		'class'			=> 'B_Row',
		'start_html'	=> '<tr>',
		'end_html'		=> '</tr>',
		array(
			'start_html'	=> '<th class="file-name">',
			'end_html'		=> '</th>',
			array(
				'start_html'	=> '<span>',
				'end_html'		=> '</span>',
				'value'			=> '名前',
			),
		),
		array(
			'start_html'	=> '<th class="update-time">',
			'end_html'		=> '</th>',
			array(
				'start_html'	=> '<span>',
				'end_html'		=> '</span>',
				'value'			=> '更新日時',
			),
		),
		array(
			'start_html'	=> '<th class="file-size">',
			'end_html'		=> '</th>',
			array(
				'start_html'	=> '<span>',
				'end_html'		=> '</span>',
				'value'			=> 'ファイルサイズ',
			),
		),
		array(
			'start_html'	=> '<th class="image-size">',
			'end_html'		=> '</th>',
			array(
				'start_html'	=> '<span>',
				'end_html'		=> '</span>',
				'value'			=> 'イメージサイズ',
			),
		),
	),

	'row'		=>
	array(
		'start_html'			=> '<tr>',
		'end_html'				=> '</tr>',
		'class'					=> 'B_Row',
		array(
			'start_html'	=> '<td class="file-name">',
			'end_html'		=> '</td>',
			array(
				'start_html'	=> '<div class="tree">',
				'end_html'		=> '</div>',
				array(
					'start_html'	=> '<a>',
					'end_html'		=> '</a>',
					array(
						'name'						=> 'icon',
						'value'						=> '<img src="./images/folders/folder.png" />',
						'value_folder_diff_left'	=> '<img src="./images/folders/folder_red.png" />',
						'value_folder_diff_right'	=> '<img src="./images/folders/folder_blue.png" />',
						'value_folder_diff_child'	=> '<img src="./images/folders/folder_purple.png" />',
						'value_file'				=> '<img src="./images/folders/file_icon.png" />',
						'value_file_diff_left'		=> '<img src="./images/folders/file_icon_red.png" />',
						'value_file_diff_right'		=> '<img src="./images/folders/file_icon_blue.png" />',
						'value_file_diff_child'		=> '<img src="./images/folders/file_icon_purple.png" />',
					),
					array(
						'name'			=> 'node_class',
						'class'			=> 'B_Data',
					),
					array(
						'name'			=> 'node_status',
						'class'			=> 'B_Data',
					),
					array(
						'name'			=> 'node_name',
						'start_html'	=> '<span>',
						'end_html'		=> '</span>',
					),
				),
			),
		),
		array(
			'start_html'	=> '<td class="update-datetime">',
			'end_html'		=> '</td>',
			array(
				'name'			=> 'create_datetime',
				'start_html'	=> '<span class="update-datetime">',
				'end_html'		=> '</span>',
			),
		),
		array(
			'start_html'	=> '<td class="file-size">',
			'end_html'		=> '</td>',
			array(
				'name'			=> 'file_size',
				'start_html'	=> '<span class="file-size">',
				'end_html'		=> '</span>',
			),
		),
		array(
			'start_html'	=> '<td class="image-size">',
			'end_html'		=> '</td>',
			array(
				'name'			=> 'image_size',
				'start_html'	=> '<span class="image-size">',
				'end_html'		=> '</span>',
			),
		),
	),
);

$compare_pain_disp_change_config = array(
	'start_html'	=> '<li class="select">',
	'end_html'		=> '</li>',
	array(
		'start_html'	=> '<span id="bframe_pain_disp_change">',
		'end_html'		=> '</span>',
		'value'			=> '表示 ： ',
		array(
			'class'			=> 'B_SelectBox',
			'name'			=> 'disp_mode',
			'data_set'		=> 'disp_mode',
		),
	),
);