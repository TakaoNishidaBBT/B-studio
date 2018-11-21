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
				'start_html'	=> '<a href="' . B_SITE_BASE . '" title="' . __('Open published page') . '" onclick="window.open(this.href); return false;">',
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
			'value'			=> '<img src="images/menu/contents.png" alt="contents"/>' . __('Contents'),
			'specialchars'	=> 'none',
			'link'			=> DISPATCH_URL . '&amp;module=contents&amp;page=index&amp;method=init',
			'target'		=> 'main',
		),
		array(
			'auth_filter'	=> 'super_admin/admin',
			'start_html'	=> '<li>',
			'end_html'		=> '</li>',
			'class'			=> 'B_Link',
			'value'			=> '<img src="images/menu/template.png" alt="templates"/>' . __('Templates'),
			'specialchars'	=> 'none',
			'link'			=> DISPATCH_URL . '&amp;module=template&amp;page=index&amp;method=init',
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
				'value'			=> '<img src="images/menu/resource.png" alt="resources"/>' . __('Resources'),
				'specialchars'	=> 'none',
				'script'		=>
				array(
					'bframe_menu'	=>
					array(
						'context_menu'	=>
						array(
							array(
								'menu'		=> __('Resource Manager'),
								'param'		=> DISPATCH_URL . '&module=resource&page=tree&mode=open,main',
								'func'		=> 'openUrl',
							),
							array(
								'menu'		=> __('Widgets'),
								'param'		=> DISPATCH_URL . '&module=widget&page=index&method=init,main',
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
			'auth_filter'	=> 'super_admin/admin/editor',
			'start_html'	=> '<li>',
			'end_html'		=> '</li>',
			array(
				'class'			=> 'B_Link',
				'attr'			=> 'class="bframe_menu"',
				'id'			=> 'post_menu',
				'value'			=> '<img src="images/menu/article.png" alt="posts"/>' . __('Posts'),
				'specialchars'	=> 'none',
				'script'		=>
				array(
					'bframe_menu'	=>
					array(
						'context_menu'	=>
						array(
							array(
								'menu'		=> __('Article'),
								'param'		=> DISPATCH_URL . '&module=article&page=list&method=init,main',
								'func'		=> 'openUrl',
							),
							array(
								'menu'		=> __('Article2'),
								'param'		=> DISPATCH_URL . '&module=article2&page=list&method=init,main',
								'func'		=> 'openUrl',
							),
							array(
								'menu'		=> __('Article3'),
								'param'		=> DISPATCH_URL . '&module=article3&page=list&method=init,main',
								'func'		=> 'openUrl',
							),
							array(
								'menu'		=> __('File Manager'),
								'param'		=> DISPATCH_URL . '&module=filemanager&page=tree&mode=open,main',
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
			'auth_filter'	=> 'super_admin',
			'start_html'	=> '<li>',
			'end_html'		=> '</li>',
			array(
				'class'			=> 'B_Link',
				'attr'			=> 'class="bframe_menu"',
				'id'			=> 'setting_menu',
				'value'			=> '<img src="images/menu/settings.png" alt="settings"/>' . __('Settings'),
				'specialchars'	=> 'none',
				'script'		=>
				array(
					'bframe_menu'	=>
					array(
						'context_menu'	=>
						array(
							array(
								'menu'		=> __('Basic Settings'),
								'param'		=> DISPATCH_URL . '&module=settings&page=form&method=select,main',
								'func'		=> 'openUrl',
							),
							array(
								'menu'		=> __('Versions'),
								'param'		=> DISPATCH_URL . '&module=version&page=list&method=init,main',
								'func'		=> 'openUrl',
							),
							array(
								'menu'		=> __('Mail Settings'),
								'param'		=> DISPATCH_URL . '&module=mail_settings&page=list&method=init,main',
								'func'		=> 'openUrl',
							),
							array(
								'menu'		=> __('Users'),
								'param'		=> DISPATCH_URL . '&module=user&page=list&method=init,main',
								'func'		=> 'openUrl',
							),
							array(
								'menu'		=> __('Site Admin'),
								'param'		=> DISPATCH_URL . '&module=siteadmin&page=form&method=select,main',
								'func'		=> 'openUrl',
							),
						),
						'context_menu_mark'		=> '　▼',
						'context_menu_frame'	=> 'top',
						'context_menu_width'	=> '140',
					),
				),
			),
		),
		array(
			'auth_filter'	=> 'admin',
			'start_html'	=> '<li>',
			'end_html'		=> '</li>',
			array(
				'class'			=> 'B_Link',
				'attr'			=> 'class="bframe_menu"',
				'id'			=> 'setting_menu',
				'value'			=> '<img src="images/menu/settings.png" alt="settings"/>' . __('Settings'),
				'specialchars'	=> 'none',
				'script'		=>
				array(
					'bframe_menu'	=>
					array(
						'context_menu'	=>
						array(
							array(
								'menu'		=> __('Basic Settings'),
								'param'		=> DISPATCH_URL . '&module=settings&page=form&method=select,main',
								'func'		=> 'openUrl',
							),
							array(
								'menu'		=> __('Versions'),
								'param'		=> DISPATCH_URL . '&module=version&page=list&method=init,main',
								'func'		=> 'openUrl',
							),
							array(
								'menu'		=> __('Users'),
								'param'		=> DISPATCH_URL . '&module=user&page=list&method=init,main',
								'func'		=> 'openUrl',
							),
						),
						'context_menu_mark'		=> '　▼',
						'context_menu_frame'	=> 'top',
						'context_menu_width'	=> '140',
					),
				),
			),
		),
		array(
			'auth_filter'	=> 'editor',
			'start_html'	=> '<li>',
			'end_html'		=> '</li>',
			array(
				'class'			=> 'B_Link',
				'attr'			=> 'class="bframe_menu"',
				'id'			=> 'setting_menu',
				'value'			=> '<img src="images/menu/settings.png" alt="settings"/>' . __('Settings'),
				'specialchars'	=> 'none',
				'script'		=>
				array(
					'bframe_menu'	=>
					array(
						'context_menu'	=>
						array(
							array(
								'menu'		=> __('Basic Settings'),
								'param'		=> DISPATCH_URL . '&module=settings&page=form&method=select,main',
								'func'		=> 'openUrl',
							),
						),
						'context_menu_mark'		=> '　▼',
						'context_menu_frame'	=> 'top',
					),
				),
			),
		),
		array(
			'start_html'	=> '<li class="new_tab">',
			'end_html'		=> '</li>',
			array(
				'start_html'	=> '<a href="" title="' . __('Open another admin page') . '" onclick="window.open(this.href); return false;">',
				'end_html'		=> '</a>',
				array(
					'start_html'	=> '<span class="add">',
					'end_html'		=> '</span>',
					'value'			=> '+',
				),
			),
		),
	),
);
