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
			array(
				'start_html'    => '<a href="' . B_SITE_ROOT . '" title="公開画面を開きます" onclick="window.open(this.href); return false;">',
				'end_html'	    => '</a>',
				array(
					'start_html'	=> '<span class="title">',
					'end_html'		=> '</span>',
					'value'			=> 'B-studio',
				),
			),
		),
		array(
			'auth_filter'	=> 'super_admin/admin',
			'start_html'    => '<li>',
			'end_html'	    => '</li>',
			'class'			=> 'B_Link',
			'value'			=> '<img src="images/menu/contents.png" alt="contents"/>コンテンツ',
			'specialchars'	=> 'none',
			'link'			=> DISPATCH_URL . '&amp;module=contents&amp;page=index&amp;method=init',
			'target'		=> 'main',
		),
		array(
			'auth_filter'	=> 'super_admin/admin',
			'start_html'    => '<li>',
			'end_html'	    => '</li>',
			'class'			=> 'B_Link',
			'value'			=> '<img src="images/menu/template.png" alt="テンプレート"/>テンプレート',
			'specialchars'	=> 'none',
			'link'			=> DISPATCH_URL . '&amp;module=template&amp;page=index&amp;method=init',
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
				'value'			=> '<img src="images/menu/resource.png" alt="リソース"/>リソース',
				'specialchars'	=> 'none',
				'script'		=>
				array(
					'bframe_menu'			=>
					array(
						'context_menu'		=>
						array(
							array(
								'menu'		=> 'リソース管理',
								'param'		=> DISPATCH_URL . '&module=resource&page=tree,main',
								'func'		=> 'openUrl',
							),
							array(
								'menu'		=> 'ウィジェット',
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
			'start_html'    => '<li>',
			'end_html'	    => '</li>',
			array(
				'class'			=> 'B_Link',
				'special_html'	=> 'class="bframe_menu"',
				'id'			=> 'post_menu',
				'value'			=> '<img src="images/menu/article.png" alt="投稿"/>投稿',
				'specialchars'	=> 'none',
				'script'		=>
				array(
					'bframe_menu'			=>
					array(
						'context_menu'		=>
						array(
							array(
								'menu'		=> '記事',
								'param'		=> DISPATCH_URL . '&module=article&page=list&method=init,main',
								'func'		=> 'openUrl',
							),
							array(
								'menu'		=> '記事2',
								'param'		=> DISPATCH_URL . '&module=article2&page=list&method=init,main',
								'func'		=> 'openUrl',
							),
							array(
								'menu'		=> '記事3',
								'param'		=> DISPATCH_URL . '&module=article3&page=list&method=init,main',
								'func'		=> 'openUrl',
							),
							array(
								'menu'		=> 'ファイル管理',
								'param'		=> DISPATCH_URL . '&module=filemanager&page=tree,main',
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
			'start_html'    => '<li>',
			'end_html'	    => '</li>',
			array(
				'class'			=> 'B_Link',
				'special_html'	=> 'class="bframe_menu"',
				'id'			=> 'setting_menu',
				'value'			=> '<img src="images/menu/settings.png" alt="各種設定"/>各種設定',
				'specialchars'	=> 'none',
				'script'		=>
				array(
					'bframe_menu'			=>
					array(
						'context_menu'		=>
						array(
							array(
								'menu'		=> '基本設定',
								'param'		=> DISPATCH_URL . '&module=settings&page=form&method=select,main',
								'func'		=> 'openUrl',
							),
							array(
								'menu'		=> 'バージョン管理',
								'param'		=> DISPATCH_URL . '&module=version&page=list&method=init,main',
								'func'		=> 'openUrl',
							),
							array(
								'menu'		=> 'ユーザ設定',
								'param'		=> DISPATCH_URL . '&module=user&page=list&method=init,main',
								'func'		=> 'openUrl',
							),
							array(
								'menu'		=> 'サイト管理者設定',
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
			'start_html'    => '<li>',
			'end_html'	    => '</li>',
			array(
				'class'			=> 'B_Link',
				'special_html'	=> 'class="bframe_menu"',
				'id'			=> 'setting_menu',
				'value'			=> '各種設定',
				'script'		=>
				array(
					'bframe_menu'			=>
					array(
						'context_menu'		=>
						array(
							array(
								'menu'		=> '基本設定',
								'param'		=> DISPATCH_URL . '&module=settings&page=form&method=select,main',
								'func'		=> 'openUrl',
							),
							array(
								'menu'		=> 'バージョン管理',
								'param'		=> DISPATCH_URL . '&module=version&page=list&method=init,main',
								'func'		=> 'openUrl',
							),
							array(
								'menu'		=> 'ユーザ設定',
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
			'start_html'    => '<li>',
			'end_html'	    => '</li>',
			array(
				'class'			=> 'B_Link',
				'special_html'	=> 'class="bframe_menu"',
				'id'			=> 'setting_menu',
				'value'			=> '各種設定',
				'script'		=>
				array(
					'bframe_menu'			=>
					array(
						'context_menu'		=>
						array(
							array(
								'menu'		=> '基本設定',
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
			'start_html'    => '<li class="new_tab">',
			'end_html'	    => '</li>',
			array(
				'start_html'    => '<a href="" title="管理画面をもう一枚開きます" onclick="window.open(this.href); return false;">',
				'end_html'	    => '</a>',
				array(
					'start_html'	=> '<span class="add">',
					'end_html'		=> '</span>',
					'value'			=> '+',
				),
			),
		),
	),
);
