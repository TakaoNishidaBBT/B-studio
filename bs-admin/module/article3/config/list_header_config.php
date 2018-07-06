<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$list_header_config = array(
	array(
		'start_html'	=> '<div class="list-header">',
		'end_html'		=> '</div>',
		array(
			'start_html'	=> '<form name="header_form" id="header_form" method="post" action="index.php" target="main">',
			'end_html'		=> '</form>',
			array(
				'start_html'			=> '<ul class="search">',
				'end_html'				=> '</ul>',
				array(
					'start_html'			=> '<li>',
					'end_html'				=> '</li>',
					array(
						'start_html'			=> '<label for="keyword">',
						'end_html'				=> '</label>',
						'value'					=> __('Keyword'),
					),
					array(
						'name'					=> 'keyword',
						'class'					=> 'B_InputText',
						'special_html'			=> 'class="textbox" maxlength="100" size="20"',
					),
					array(
						// for IE
						'name'					=> 'dummy',
						'class'					=> 'B_InputText',
						'special_html'			=> 'style="position:absolute;visibility:hidden;"',
					),
				),
				array(
					'start_html'			=> '<li>',
					'end_html'				=> '</li>',
					array(
						'start_html'			=> '<label for="category">',
						'end_html'				=> '</label>',
						'value'					=> __('Category'),
					),
					array(
						'name'					=> 'category',
						'class'					=> 'B_SelectBox',
						'special_html'			=> 'class="bframe_selectbox white"',
					),
				),
				array(
					'start_html'			=> '<li>',
					'end_html'				=> '</li>',
					array(
						'start_html'			=> '<label for="row_per_page">',
						'end_html'				=> '</label>',
						'value'					=> __('Display'),
					),
					array(
						'name'					=> 'row_per_page',
						'class'					=> 'B_SelectBox',
						'data_set'				=> 'row_per_page',
						'special_html'			=> 'class="bframe_selectbox white"',
					),
				),
				array(
					'id'			=> 'search-button',
					'name'			=> 'search-button',
					'class'			=> 'B_Submit',
					'start_html'	=> '<li>',
					'end_html'		=> '</li>',
					'special_html'	=> 'class="search-button" onclick="bframe.submit(\'header_form\', \'' . $this->module . '\', \'list\', \'select\')"',
					'value'			=> __('Search'),
				),
				array(
					'id'			=> 'clear-button',
					'name'			=> 'clear-button',
					'class'			=> 'B_Button',
					'start_html'	=> '<li>',
					'end_html'		=> '</li>',
					'special_html'	=> 'class="button" onclick="bstudio.clearForm(\'header_form\')"',
					'value'			=> __('Clear'),
				),
				array(
					'id'			=> 'insert-button',
					'name'			=> 'insert-button',
					'class'			=> 'B_Button',
					'start_html'	=> '<li class="insert">',
					'end_html'		=> '</li>',
					'name'			=> 'insert',
					'special_html'	=> 'class="insert-button" onclick="bframe.submit(\'header_form\', \'' . $this->module . '\', \'form\', \'select\', \'insert\')"',
					'value'			=> __('New '),
				),
				array(
					'auth_filter'	=> 'super_admin',
					'id'			=> 'default-button',
					'name'			=> 'default-button',
					'class'			=> 'B_Button',
					'start_html'	=> '<li class="insert">',
					'end_html'		=> '</li>',
					'name'			=> 'default',
					'special_html'	=> 'class="default-button" onclick="bframe.submit(\'header_form\', \'' . $this->module . '\', \'form\', \'select\', \'default\')"',
					'value'			=> __('Default'),
				),
			),
		),
	),
);
