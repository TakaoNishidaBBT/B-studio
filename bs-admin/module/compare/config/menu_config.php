<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$menu_config = array(
	'start_html'	=> '<div class="pull_down_menu">',
	'end_html'		=> '</div>',
	array(
		'start_html'	=> '<ul>',
		'end_html'		=> '</ul>',
		array(
			'start_html'	=> '<li class="title">',
			'end_html'		=> '</li>',
			array(
				'start_html'	=> '<a href="' . B_SITE_ROOT . '" title="' . __('Open published page') . '" onclick="window.open(this.href); return false;">',
				'end_html'		=> '</a>',
				array(
					'start_html'	=> '<span class="title">',
					'end_html'		=> '</span>',
					'value'			=> 'B-studio',
				),
			),
		),
		array(
			'auth_filter'	=> 'super_admin/admin',
			'start_html'	=> '<li>',
			'end_html'		=> '</li>',
			'class'			=> 'B_Link',
			'value'			=> '<img src="images/menu/contents.png" alt="Contents"/>' . __('Contents'),
			'specialchars'	=> 'none',
			'link'			=> DISPATCH_URL . '&module=contents&page=compare&method=init',
			'target'		=> 'main',
		),
		array(
			'auth_filter'	=> 'super_admin/admin',
			'start_html'	=> '<li>',
			'end_html'		=> '</li>',
			'class'			=> 'B_Link',
			'value'			=> '<img src="images/menu/template.png" alt="Templates"/>' . __('Templates'),
			'specialchars'	=> 'none',
			'link'			=> DISPATCH_URL . '&module=template&page=compare&method=init',
			'target'		=> 'main',
		),
		array(
			'auth_filter'	=> 'super_admin/admin',
			'start_html'	=> '<li>',
			'end_html'		=> '</li>',
			array(
				'class'			=> 'B_Link',
				'attr'			=> 'class="bframe_menu"',
				'id'			=> 'resource',
				'value'			=> '<img src="images/menu/resource.png" alt="Resources"/>' . __('Resources'),
				'specialchars'	=> 'none',
				'script'		=>
				array(
					'bframe_menu'			=>
					array(
						'context_menu'		=>
						array(
							array(
								'menu'		=> __('Resource Manager'),
								'param'		=> DISPATCH_URL . '&module=resource&page=compare,main',
								'func'		=> 'openUrl',
							),
							array(
								'menu'		=> __('Widgets'),
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
	),
);
