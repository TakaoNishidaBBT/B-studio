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
		'start_html'	=> '<h2 class="version">',
		'end_html'		=> '</h2>',
		'value'			=> __('Versions'),
		array(
			'name'			=> 'version_info',
			'start_html'	=> '<span>',
			'end_html'		=> '</span>',
		),
	),
	array(
		'start_html'	=> '<div class="list-header">',
		'end_html'		=> '</div>',
		array(
			'start_html'	=> '<form name="header_form" id="header_form" method="post" action="index.php" target="main">',
			'end_html'		=> '</form>',
			array(
				'start_html'	=> '<ul class="search">',
				'end_html'		=> '</ul>',
				array(
					'start_html'	=> '<li>',
					'end_html'		=> '</li>',
					array(
						'start_html'	=> '<label for="keyword">',
						'end_html'		=> '</label>',
						'value'			=> __('Keyword'),
					),
					array(
						'class'			=> 'B_InputText',
						'name'			=> 'keyword',
						'attr'			=> 'class="textbox" maxlength="100" size="20"',
					),
					array(
						// For IE
						'class'			=> 'B_InputText',
						'name'			=> 'dummy',
						'attr'			=> 'style="position:absolute;visibility:hidden;"',
					),
				),	
				array(
					'start_html'	=> '<li>',
					'end_html'		=> '</li>',
					array(
						'start_html'	=> '<label for="row_per_page">',
						'end_html'		=> '</label>',
						'value'			=> __('Display'),
					),
					array(
						'class'			=> 'B_SelectBox',
						'name'			=> 'row_per_page',
						'data_set'		=> 'row_per_page',
						'attr'			=> 'class="bframe_selectbox white"',
					),
				),
				array(
					'id'			=> 'search-button',
					'name'			=> 'search-button',
					'class'			=> 'B_Submit',
					'start_html'	=> '<li>',
					'end_html'		=> '</li>',
					'attr'			=> 'class="search-button" onclick="bframe.submit(\'header_form\', \'' . $this->module . '\', \'list\', \'select\')"',
					'value'			=> __('Search'),
				),
				array(
					'id'			=> 'clear-button',
					'name'			=> 'clear-button',
					'class'			=> 'B_Button',
					'start_html'	=> '<li>',
					'end_html'		=> '</li>',
					'attr'			=> 'class="button" onclick="bstudio.clearForm(\'header_form\')"',
					'value'			=> __('Clear'),
				),
				array(
					'id'			=> 'insert-button',
					'name'			=> 'insert-button',
					'class'			=> 'B_Button',
					'start_html'	=> '<li class="insert">',
					'end_html'		=> '</li>',
					'name'			=> 'insert',
					'attr'			=> 'class="insert-button" onclick="bframe.submit(\'header_form\', \'' . $this->module . '\', \'form\', \'select\', \'insert\')"',
					'value'			=> __('New '),
				),
			),
		),
	),
);
