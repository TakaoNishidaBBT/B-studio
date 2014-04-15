<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$menu_config = array(
	'start_html'    => '<div class="pull_down_menu">',
	'end_html'	    => '</div>',
	array(
		'start_html'    => '<ul>',
		'end_html'	    => '</ul>',
		array(
			'start_html'    => '<li class="title">',
			'end_html'	    => '</li>',
			'value'			=> '<img src="images/common/title.png" alt="B-studio" />',
		),
		array(
			'auth_filter'	=> 'super_admin/admin',
			'start_html'    => '<li>',
			'end_html'	    => '</li>',
			'class'			=> 'B_Link',
			'value'			=> 'コンテンツ',
			'link'			=> DISPATCH_URL . '&module=contents&page=compare&method=init',
			'target'		=> 'main',
		),
		array(
			'auth_filter'	=> 'super_admin/admin',
			'start_html'    => '<li>',
			'end_html'	    => '</li>',
			'class'			=> 'B_Link',
			'value'			=> 'テンプレート',
			'link'			=> DISPATCH_URL . '&module=template&page=compare&method=init',
			'target'		=> 'main',
		),
		array(
			'auth_filter'	=> 'super_admin/admin',
			'start_html'    => '<li>',
			'end_html'	    => '</li>',
			array(
				'class'			=> 'B_Link',
				'special_html'	=> 'class="bframe_menu"',
				'id'			=> 'resource',
				'value'			=> 'リソース',
				'script'		=>
				array(
					'bframe_menu'			=>
					array(
						'context_menu'		=>
						array(
							array(
								'menu'		=> 'リソース管理',
								'param'		=> DISPATCH_URL . '&module=resource&page=compare,main',
								'func'		=> 'openUrl',
							),
							array(
								'menu'		=> 'ウィジェット',
								'param'		=> DISPATCH_URL . '&module=widget&page=compare&method=init,main',
								'func'		=> 'openUrl',
							),
						),
						'context_menu_mark'		=> '　▼',
						'context_menu_frame'	=> 'top',
						'context_menu_width'	=> '120',
					),
				),
			),
		),
		array(
			'start_html'    => '<li class="terminator">',
			'end_html'	    => '</li>',
		),
	),
);
