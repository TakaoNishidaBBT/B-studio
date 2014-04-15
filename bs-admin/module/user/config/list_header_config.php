<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$list_header_config = array(
	array(
		'name'			=> 'title',
		'start_html'    => '<h2 class="user">',
		'end_html'	    => '</h2>',
		'value'			=> 'ユーザ設定',
	),
	array(
		'start_html'    => '<div class="list-header">',
		'end_html'	    => '</div>',
		array(
			'start_html'    => '<form name="header_form" id="header_form" method="post" action="index.php" target="main">',
			'end_html'	    => '</form>',
			array('class'	=> 'B_Hidden', 'name'=> 'default_row_per_page'),
			array(
				'start_html'			=> '<ul class="search">',
				'end_html'				=> '</ul>',
				array(
					'start_html'			=> '<li>',
					'end_html'				=> '</li>',
					array(
						'class'					=> 'B_Text',
						'start_html'			=> '<label for="item_name">',
						'end_html'				=> '</label>',
						'value'					=> 'キーワード',
					),
					array(
						'class'					=> 'B_InputText',
						'name'					=> 'keyword',
						'special_html'			=> 'class="textbox" maxlength="100" size="20"',
					),
					array(
						// Enterキーによるサブミット対策
						'class'					=> 'B_InputText',
						'name'					=> 'dummy',
						'special_html'			=> 'style="position:absolute;visibility:hidden;"',
					),
				),	
				array(
					'start_html'			=> '<li>',
					'end_html'				=> '</li>',
					array(
						'class'					=> 'B_Text',
						'start_html'			=> '<label for="rows">',
						'end_html'				=> '</label>',
						'value'					=> '表示',
					),
					array(
						'class'					=> 'B_SelectBox',
						'name'					=> 'row_per_page',
						'data_set'				=> 'row_per_page',
						'value'					=> '10',
					),
				),
				array(
					'class'			=> 'B_Button',
					'start_html'	=> '<li>',
					'end_html'		=> '</li>',
					'special_html'	=> 'class="search-button" onclick="bframe.submit(\'header_form\', \'' . $this->module . '\', \'list\', \'select\', \'\')"',
					'value'			=> '検索',
				),
				array(
					'class'			=> 'B_Button',
					'start_html'	=> '<li>',
					'end_html'		=> '</li>',
					'special_html'	=> 'class="button" onclick="clearForm(\'header_form\', 20);"',
					'value'			=> 'クリア',
				),
				array(
					'auth_filter'	=> 'super_admin/admin',
					'class'			=> 'B_Button',
					'start_html'	=> '<li>',
					'end_html'		=> '</li>',
					'name'			=> 'insert',
					'special_html'	=> 'class="insert-button" onclick="bframe.submit(\'header_form\', \'' . $this->module . '\', \'form\', \'select\', \'insert\')"',
					'value'			=> '新規作成',
				),
			),
		),
	),
);
