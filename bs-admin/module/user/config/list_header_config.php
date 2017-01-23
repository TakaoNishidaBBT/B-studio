<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$list_header_config = array(
	array(
		'name'			=> 'title',
		'start_html'	=> '<h2 class="user">',
		'end_html'		=> '</h2>',
		'value'			=> __('Users'),
	),
	array(
		'start_html'	=> '<div class="list-header">',
		'end_html'		=> '</div>',
		array(
			'start_html'	=> '<form name="header_form" id="header_form" method="post" action="index.php" target="main">',
			'end_html'		=> '</form>',
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
						'value'					=> __('Keyword'),
					),
					array(
						'class'					=> 'B_InputText',
						'name'					=> 'keyword',
						'special_html'			=> 'class="textbox" maxlength="100" size="20"',
					),
					array(
						// for IE
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
						'value'					=> __('Display'),
					),
					array(
						'name'					=> 'row_per_page',
						'class'					=> 'B_SelectBox',
						'special_html'			=> 'class="bframe_selectbox"',
						'data_set'				=> 'row_per_page',
					),
				),
				array(
					'class'			=> 'B_Button',
					'start_html'	=> '<li>',
					'end_html'		=> '</li>',
					'special_html'	=> 'class="search-button" onclick="bframe.submit(\'header_form\', \'' . $this->module . '\', \'list\', \'select\', \'\')"',
					'value'			=> __('Search'),
				),
				array(
					'class'			=> 'B_Button',
					'start_html'	=> '<li>',
					'end_html'		=> '</li>',
					'special_html'	=> 'class="button" onclick="bstudio.clearForm(\'header_form\')"',
					'value'			=> __('Clear'),
				),
				array(
					'auth_filter'	=> 'super_admin/admin',
					'class'			=> 'B_Button',
					'start_html'	=> '<li>',
					'end_html'		=> '</li>',
					'name'			=> 'insert',
					'special_html'	=> 'class="insert-button" onclick="bframe.submit(\'header_form\', \'' . $this->module . '\', \'form\', \'select\', \'insert\')"',
					'value'			=> __('New '),
				),
			),
		),
	),
);
