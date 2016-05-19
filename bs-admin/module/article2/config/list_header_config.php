<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$list_header_config = array(
	array('class' => 'B_Hidden', 'name' => 'default_row_per_page'),
	array(
		'start_html'    => '<div class="list-header">',
		'end_html'	    => '</div>',
		array(
			'start_html'    => '<form name="F1" id="F1" method="post" action="index.php" target="main">',
			'end_html'	    => '</form>',
			array(
				'start_html'			=> '<ul class="search">',
				'end_html'				=> '</ul>',
				array(
					'start_html'			=> '<li>',
					'end_html'				=> '</li>',
					array(
						'start_html'			=> '<label for="keyword">',
						'end_html'				=> '</label>',
						'value'					=> 'キーワード',
					),
					array(
						'name'					=> 'keyword',
						'class'					=> 'B_InputText',
						'special_html'			=> 'class="textbox" maxlength="100" size="20"',
					),
					array(
						// Enterキーによるサブミット対策
						'name'					=> 'dummy',
						'class'					=> 'B_InputText',
						'special_html'			=> 'style="position:absolute;visibility:hidden;"',
					),
				),
				array(
					'start_html'			=> '<li>',
					'end_html'				=> '</li>',
					array(
						'start_html'			=> '<label for="category_id">',
						'end_html'				=> '</label>',
						'value'					=> 'カテゴリ',
					),
					array(
						'name'					=> 'category_id',
						'class'					=> 'B_SelectBox',
						'special_html'			=> 'class="bframe_selectbox"',
					),
				),
				array(
					'start_html'			=> '<li>',
					'end_html'				=> '</li>',
					array(
						'start_html'			=> '<label for="row_per_page">',
						'end_html'				=> '</label>',
						'value'					=> '表示',
					),
					array(
						'name'					=> 'row_per_page',
						'class'					=> 'B_SelectBox',
						'data_set'				=> 'row_per_page',
						'special_html'			=> 'class="bframe_selectbox"',
					),
				),
				array(
					'class'			=> 'B_Button',
					'start_html'	=> '<li>',
					'end_html'		=> '</li>',
					'special_html'	=> 'class="search-button" onclick="bframe.submit(\'F1\', \'' . $this->module . '\', \'list\', \'select\')"',
					'value'			=> '検索',
				),
				array(
					'class'			=> 'B_Button',
					'start_html'	=> '<li>',
					'end_html'		=> '</li>',
					'special_html'	=> 'class="button" onclick="bstudio.clearForm(\'F1\')"',
					'value'			=> 'クリア',
				),
				array(
					'class'			=> 'B_Button',
					'start_html'	=> '<li class="insert">',
					'end_html'		=> '</li>',
					'name'			=> 'insert',
					'special_html'	=> 'class="insert-button" onclick="bframe.submit(\'F1\', \'' . $this->module . '\', \'form\', \'select\', \'insert\')"',
					'value'			=> '新規作成',
				),
			),
		),
	),
);
