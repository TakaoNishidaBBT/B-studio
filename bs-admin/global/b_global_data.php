<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	${$g_data_set}['row_per_page'] =
		array(
				'1' => '1件',
				'10' => '10件',
				'20' => '20件',
				'50' => '50件',
				'100' => '100件',
		);

	${$g_data_set}['user_auth'] =
		array(
				'admin'		=> '管理者',
				'editor'	=> '投稿者',
		);

	${$g_data_set}['user_status'] =
		array(
				'1'	=> '有効',
				'9'	=> '無効',
		);

	${$g_data_set}['del_flag'] =
		array(
				''	=> '',
				'0'	=> '',
				'1'	=> '論理削除',
		);

	${$g_data_set}['disp_mode'] =
		array(
				'thumbs'	=> 'サムネイル',
				'detail'	=> '詳細',
		);

	${$g_data_set}['record_status_list'] =
		array(
				''	=> '',
				'0'	=> '',
				'1'	=> '■',
		);

	${$g_data_set}['publication_status'] =
		array(
				''	=> '',
				'0'	=> '',
				'1'	=> '■',
				'2'	=> '★',
		);

	${$g_data_set}['publication'] =
		array(
				'1'	=> '公開',
				'2'	=> 'プレビュー',
				'3'	=> '非公開',
		);

	${$g_data_set}['description_flag'] =
		array(
				'1'		=> 'あり',
				'2'		=> 'なし',
		);

	${$g_data_set}['external_link'] =
		array(
				''		=> 'なし',
				'1'		=> 'あり',
		);

	${$g_data_set}['datetime_error_message'] =
		array(
				'1' => '(範囲外)',
				'2' => '(時刻が不正)',
				'3' => '(日付が不正)',
				'4' => '(フォーマットが不正)'
		);

	${$g_data_set}['article_publication'] =
		array(
				'1'	=> '公開',
				'2'	=> '非公開',
		);

	${$g_data_set}['external_window'] =
		array(
				''		=> '',
				'1'		=> '別ウィンドウ',
		);

	${$g_data_set}['node_error'] =
		array(
				'0'		=> 'DBエラー',
				'1'		=> '受け側のフォルダは送り側のフォルダのサブフォルダです',
				'2'		=> 'ノードの数が違っています(右側のフォルダペインでソートしてください)',
				'3'		=> '他のユーザによって更新されています',
		);

	${$g_data_set}['template_node_error'] =
		array(
				'0'		=> 'DBエラー',
				'1'		=> '受け側のテンプレートは送り側のサブテンプレートです',
				'2'		=> 'ノードの数が違っています',
				'3'		=> '他のユーザによって更新されています',
		);

	${$g_data_set}['encoding'] =
		array(
				'UTF-8'		=> 'UTF-8',
				'EUC-JP'	=> 'EUC-JP',
				'SJIS'		=> 'SJIS',
				'sjis-win'	=> 'sjis-win',
		);

	${$g_data_set}['table']['contents_node'] =
		array(								// データ型			桁数	PK		AUTO-INCREMENT
			'version_id'				=> array('char', 		'5', 	'1', 	''),
			'revision_id'				=> array('char', 		'2', 	'1', 	''),
			'node_id'					=> array('char', 		'10', 	'1', 	'1'),
			'parent_node'				=> array('char', 		'10', 	'', 	''),
			'node_type'					=> array('char', 		'10', 	'', 	''),
			'node_class'				=> array('char', 		'10', 	'', 	''),
			'node_name'					=> array('text', 		'', 	'', 	''),
			'contents_id'				=> array('char', 		'10', 	'', 	''),
			'disp_seq'					=> array('int', 		'', 	'', 	''),
			'del_flag'					=> array('char', 		'1', 	'', 	''),
			'create_user'				=> array('text', 		'', 	'',		''),
			'create_datetime'			=> array('text', 		'', 	'',		''),
			'update_user'				=> array('text', 		'', 	'',		''),
			'update_datetime'			=> array('text', 		'', 	'',		''),
		);

	${$g_data_set}['table']['contents'] =
		array(								// データ型			桁数	PK		AUTO-INCREMENT
			'version_id'				=> array('char', 		'5', 	'1', 	''),
			'revision_id'				=> array('char', 		'2', 	'1', 	''),
			'contents_id'				=> array('char', 		'10', 	'1', 	'1'),
			'contents_date'				=> array('char', 		'10', 	'', 	''),
			'template_id'				=> array('char', 		'10', 	'', 	''),
			'title'						=> array('text', 		'', 	'', 	''),
			'bread_crumb_name'			=> array('text', 		'', 	'', 	''),
			'html1'						=> array('mediumtext', 	'', 	'', 	''),
			'html2'						=> array('mediumtext', 	'', 	'', 	''),
			'html3'						=> array('mediumtext', 	'', 	'', 	''),
			'html4'						=> array('mediumtext', 	'', 	'', 	''),
			'css'						=> array('mediumtext', 	'', 	'', 	''),
			'php'						=> array('mediumtext', 	'', 	'', 	''),
			'keyword'					=> array('text', 		'', 	'', 	''),
			'description'				=> array('text', 		'', 	'', 	''),
			'external_css'				=> array('text', 		'', 	'', 	''),
			'external_js'				=> array('text', 		'', 	'', 	''),
			'header_element'			=> array('text', 		'', 	'', 	''),
			'del_flag'					=> array('char', 		'1', 	'', 	''),
			'reserve1'					=> array('text', 		'', 	'', 	''),
			'reserve2'					=> array('text', 		'', 	'', 	''),
			'reserve3'					=> array('text', 		'', 	'', 	''),
			'create_user'				=> array('text', 		'', 	'',		''),
			'create_datetime'			=> array('text', 		'', 	'',		''),
			'update_user'				=> array('text', 		'', 	'',		''),
			'update_datetime'			=> array('text', 		'', 	'',		''),
		);

	${$g_data_set}['table']['template_node'] =
		array(								// データ型			桁数	PK		AUTO-INCREMENT
			'version_id'				=> array('char', 		'5', 	'1', 	''),
			'revision_id'				=> array('char', 		'2', 	'1', 	''),
			'node_id'					=> array('char', 		'10', 	'1', 	'1'),
			'parent_node'				=> array('char', 		'10', 	'', 	''),
			'node_type'					=> array('char', 		'10', 	'', 	''),
			'node_class'				=> array('char', 		'10', 	'', 	''),
			'node_name'					=> array('text', 		'', 	'', 	''),
			'contents_id'				=> array('char', 		'10', 	'', 	''),
			'disp_seq'					=> array('int', 		'', 	'', 	''),
			'del_flag'					=> array('char', 		'1', 	'', 	''),
			'create_user'				=> array('text', 		'', 	'',		''),
			'create_datetime'			=> array('text', 		'', 	'',		''),
			'update_user'				=> array('text', 		'', 	'',		''),
			'update_datetime'			=> array('text', 		'', 	'',		''),
		);

	${$g_data_set}['table']['template'] =
		array(								// データ型			桁数	PK		AUTO-INCREMENT
			'version_id'				=> array('char',		'5', 	'1', 	''),
			'revision_id'				=> array('char', 		'2', 	'1', 	''),
			'contents_id'				=> array('char', 		'10', 	'1', 	'1'),
			'contents_date'				=> array('char', 		'10', 	'', 	''),
			'template_id'				=> array('char', 		'10', 	'', 	''),
			'start_html'				=> array('mediumtext', 	'', 	'', 	''),
			'end_html'					=> array('mediumtext', 	'', 	'', 	''),
			'css'						=> array('mediumtext', 	'', 	'', 	''),
			'php'						=> array('mediumtext', 	'', 	'', 	''),
			'external_css'				=> array('text', 		'', 	'', 	''),
			'external_js'				=> array('text', 		'', 	'', 	''),
			'header_element'			=> array('text', 		'', 	'', 	''),
			'del_flag'					=> array('char', 		'1', 	'', 	''),
			'reserve1'					=> array('text', 		'', 	'', 	''),
			'reserve2'					=> array('text', 		'', 	'', 	''),
			'reserve3'					=> array('text', 		'', 	'', 	''),
			'create_user'				=> array('text', 		'', 	'',		''),
			'create_datetime'			=> array('text', 		'', 	'',		''),
			'update_user'				=> array('text', 		'', 	'',		''),
			'update_datetime'			=> array('text', 		'', 	'',		''),
		);

	${$g_data_set}['table']['widget_node'] =
		array(								// データ型			桁数	PK		AUTO-INCREMENT
			'version_id'				=> array('char',	 	'5', 	'1', 	''),
			'revision_id'				=> array('char', 		'2', 	'1', 	''),
			'node_id'					=> array('char', 		'10', 	'1', 	'1'),
			'parent_node'				=> array('char', 		'10', 	'', 	''),
			'node_type'					=> array('char', 		'10', 	'', 	''),
			'node_class'				=> array('char', 		'10', 	'', 	''),
			'node_name'					=> array('text', 		'', 	'', 	''),
			'contents_id'				=> array('char', 		'10', 	'', 	''),
			'disp_seq'					=> array('int', 		'', 	'', 	''),
			'del_flag'					=> array('char', 		'1', 	'', 	''),
			'create_user'				=> array('text', 		'', 	'',		''),
			'create_datetime'			=> array('text', 		'', 	'',		''),
			'update_user'				=> array('text', 		'', 	'',		''),
			'update_datetime'			=> array('text', 		'', 	'',		''),
		);

	${$g_data_set}['table']['widget'] =
		array(								// データ型			桁数	PK		AUTO-INCREMENT
			'version_id'				=> array('char', 		'5', 	'1', 	''),
			'revision_id'				=> array('char', 		'2', 	'1', 	''),
			'contents_id'				=> array('char', 		'10', 	'1', 	'1'),
			'contents_date'				=> array('char', 		'10', 	'', 	''),
			'widget_id'					=> array('char', 		'10', 	'', 	''),
			'html'						=> array('mediumtext', 	'', 	'', 	''),
			'css'						=> array('mediumtext', 	'', 	'', 	''),
			'php'						=> array('mediumtext', 	'', 	'', 	''),
			'del_flag'					=> array('char', 		'1', 	'', 	''),
			'reserve1'					=> array('text', 		'', 	'', 	''),
			'reserve2'					=> array('text', 		'', 	'', 	''),
			'reserve3'					=> array('text', 		'', 	'', 	''),
			'create_user'				=> array('text', 		'', 	'',		''),
			'create_datetime'			=> array('text', 		'', 	'',		''),
			'update_user'				=> array('text', 		'', 	'',		''),
			'update_datetime'			=> array('text', 		'', 	'',		''),
		);

	${$g_data_set}['table']['resource_node'] =
		array(								// データ型			桁数	PK		AUTO-INCREMENT
			'version_id'				=> array('char',	 	'5', 	'1', 	''),
			'revision_id'				=> array('char', 		'2', 	'1', 	''),
			'node_id'					=> array('char', 		'10', 	'1', 	'1'),
			'parent_node'				=> array('char', 		'10', 	'', 	''),
			'node_type'					=> array('char', 		'10', 	'', 	''),
			'node_class'				=> array('char', 		'10', 	'', 	''),
			'node_name'					=> array('text', 		'', 	'', 	''),
			'file_size'					=> array('int', 		'', 	'', 	''),
			'human_file_size'			=> array('text', 		'', 	'', 	''),
			'image_size'				=> array('int', 		'', 	'', 	''),
			'human_image_size'			=> array('text', 		'', 	'', 	''),
			'contents_id'				=> array('char', 		'19', 	'', 	''),
			'disp_seq'					=> array('int', 		'', 	'', 	''),
			'del_flag'					=> array('char', 		'1', 	'', 	''),
			'create_user'				=> array('text', 		'', 	'',		''),
			'create_datetime'			=> array('text', 		'', 	'',		''),
			'update_user'				=> array('text', 		'', 	'',		''),
			'update_datetime'			=> array('text', 		'', 	'',		''),
		);

	${$g_data_set}['table']['settings'] =
		array(								// データ型			桁数	PK		AUTO-INCREMENT
			'id'						=> array('char',	 	'5', 	'1', 	'1'),
			'site_title'				=> array('text', 		'', 	'', 	''),
			'admin_site_title'			=> array('text', 		'', 	'', 	''),
			'memo'						=> array('text', 		'', 	'', 	''),
			'reserve1'					=> array('text', 		'', 	'', 	''),
			'reserve2'					=> array('text', 		'', 	'', 	''),
			'reserve3'					=> array('text', 		'', 	'', 	''),
			'create_user'				=> array('text', 		'', 	'',		''),
			'create_datetime'			=> array('text', 		'', 	'',		''),
			'update_user'				=> array('text', 		'', 	'',		''),
			'update_datetime'			=> array('text', 		'', 	'',		''),
		);

	${$g_data_set}['table']['version'] =
		array(								// データ型			桁数	PK		AUTO-INCREMENT
			'version_id'				=> array('char', 		'5', 	'1', 	'1'),
			'private_revision_id'		=> array('char', 		'2', 	'', 	''),
			'publication_datetime_t'	=> array('text', 		'', 	'', 	''),
			'publication_datetime_u'	=> array('text', 		'', 	'', 	''),
			'publication_status'		=> array('char', 		'1', 	'', 	''),
			'version'					=> array('text', 		'', 	'', 	''),
			'memo'						=> array('text', 		'', 	'', 	''),
			'del_flag'					=> array('char', 		'1', 	'', 	''),
			'reserve1'					=> array('text', 		'', 	'', 	''),
			'reserve2'					=> array('text', 		'', 	'', 	''),
			'reserve3'					=> array('text', 		'', 	'', 	''),
			'create_user'				=> array('text', 		'', 	'',		''),
			'create_datetime'			=> array('text', 		'', 	'',		''),
			'update_user'				=> array('text', 		'', 	'',		''),
			'update_datetime'			=> array('text', 		'', 	'',		''),
		);

	${$g_data_set}['table']['current_version'] =
		array(								// データ型			桁数	PK		AUTO-INCREMENT
			'id'						=> array('char', 		'10', 	'1', 	'1'),
			'current_version_id'		=> array('char', 		'5', 	'', 	''),
			'reserved_version_id'		=> array('char', 		'5', 	'', 	''),
			'working_version_id'		=> array('char', 		'5', 	'', 	''),
			'create_user'				=> array('text', 		'', 	'',		''),
			'create_datetime'			=> array('text', 		'', 	'',		''),
			'update_user'				=> array('text', 		'', 	'',		''),
			'update_datetime'			=> array('text', 		'', 	'',		''),
		);

	${$g_data_set}['table']['compare_version'] =
		array(								// データ型			桁数	PK		AUTO-INCREMENT
			'compare_version_id'		=> array('char', 		'5', 	'1', 	''),
		);

	${$g_data_set}['table']['article'] =
		array(								// データ型			桁数	PK		AUTO-INCREMENT
			'article_id'				=> array('char', 		'10', 	'1', 	'1'),
			'article_date_t'			=> array('text', 		'', 	'', 	''),
			'article_date_u'			=> array('text', 		'', 	'', 	''),
			'category_id'				=> array('char', 		'10', 	'', 	''),
			'tag'						=> array('text', 		'', 	'', 	''),
			'publication'				=> array('char', 		'1', 	'', 	''),
			'title'						=> array('text', 		'', 	'', 	''),
			'title_img_file'			=> array('text', 		'', 	'', 	''),
			'description_flag'			=> array('char', 		'1', 	'', 	''),
			'external_link'				=> array('char', 		'1', 	'', 	''),
			'external_window'			=> array('char', 		'1', 	'', 	''),
			'url'						=> array('text', 		'', 	'', 	''),
			'description'				=> array('text', 		'', 	'', 	''),
			'del_flag'					=> array('char', 		'1', 	'', 	''),
			'folder_id'					=> array('text', 		'', 	'', 	''),
			'reserve1'					=> array('text', 		'', 	'', 	''),
			'reserve2'					=> array('text', 		'', 	'', 	''),
			'reserve3'					=> array('text', 		'', 	'', 	''),
			'create_user'				=> array('text', 		'', 	'',		''),
			'create_datetime'			=> array('text', 		'', 	'',		''),
			'update_user'				=> array('text', 		'', 	'',		''),
			'update_datetime'			=> array('text', 		'', 	'',		''),
		);

	${$g_data_set}['table']['category'] =
		array(								// データ型			桁数	PK		AUTO-INCREMENT
			'node_id'					=> array('char', 		'10', 	'1', 	'1'),
			'parent_node'				=> array('char', 		'10', 	'', 	''),
			'node_type'					=> array('char', 		'10', 	'', 	''),
			'node_class'				=> array('char', 		'10', 	'', 	''),
			'node_name'					=> array('text', 		'', 	'', 	''),
			'disp_seq'					=> array('int', 		'', 	'', 	''),
			'color'						=> array('text', 		'', 	'', 	''),
			'background_color'			=> array('text', 		'', 	'', 	''),
			'icon_file'					=> array('text', 		'', 	'', 	''),
			'del_flag'					=> array('char', 		'1', 	'', 	''),
			'create_user'				=> array('text', 		'', 	'',		''),
			'create_datetime'			=> array('text', 		'', 	'',		''),
			'update_user'				=> array('text', 		'', 	'',		''),
			'update_datetime'			=> array('text', 		'', 	'',		''),
		);

	${$g_data_set}['table']['article2'] =
		array(								// データ型			桁数	PK		AUTO-INCREMENT
			'article_id'				=> array('char', 		'10', 	'1', 	'1'),
			'article_date_t'			=> array('text', 		'', 	'', 	''),
			'article_date_u'			=> array('text', 		'', 	'', 	''),
			'category_id'				=> array('char', 		'10', 	'', 	''),
			'tag'						=> array('text', 		'', 	'', 	''),
			'publication'				=> array('char', 		'1', 	'', 	''),
			'title'						=> array('text', 		'', 	'', 	''),
			'title_img_file'			=> array('text', 		'', 	'', 	''),
			'description_flag'			=> array('char', 		'1', 	'', 	''),
			'external_link'				=> array('char', 		'1', 	'', 	''),
			'external_window'			=> array('char', 		'1', 	'', 	''),
			'url'						=> array('text', 		'', 	'', 	''),
			'description'				=> array('text', 		'', 	'', 	''),
			'del_flag'					=> array('char', 		'1', 	'', 	''),
			'folder_id'					=> array('text', 		'', 	'', 	''),
			'reserve1'					=> array('text', 		'', 	'', 	''),
			'reserve2'					=> array('text', 		'', 	'', 	''),
			'reserve3'					=> array('text', 		'', 	'', 	''),
			'create_user'				=> array('text', 		'', 	'',		''),
			'create_datetime'			=> array('text', 		'', 	'',		''),
			'update_user'				=> array('text', 		'', 	'',		''),
			'update_datetime'			=> array('text', 		'', 	'',		''),
		);

	${$g_data_set}['table']['category2'] =
		array(								// データ型			桁数	PK		AUTO-INCREMENT
			'node_id'					=> array('char',	 	'10', 	'1', 	'1'),
			'parent_node'				=> array('char', 		'10', 	'', 	''),
			'node_type'					=> array('char', 		'10', 	'', 	''),
			'node_class'				=> array('char', 		'10', 	'', 	''),
			'node_name'					=> array('text', 		'', 	'', 	''),
			'disp_seq'					=> array('int', 		'', 	'', 	''),
			'color'						=> array('text', 		'', 	'', 	''),
			'background_color'			=> array('text', 		'', 	'', 	''),
			'icon_file'					=> array('text', 		'', 	'', 	''),
			'del_flag'					=> array('char', 		'1', 	'', 	''),
			'create_user'				=> array('text', 		'', 	'',		''),
			'create_datetime'			=> array('text', 		'', 	'',		''),
			'update_user'				=> array('text', 		'', 	'',		''),
			'update_datetime'			=> array('text', 		'', 	'',		''),
		);

	${$g_data_set}['table']['article3'] =
		array(								// データ型			桁数	PK		AUTO-INCREMENT
			'article_id'				=> array('char', 		'10', 	'1', 	'1'),
			'article_date_t'			=> array('text', 		'', 	'', 	''),
			'article_date_u'			=> array('text', 		'', 	'', 	''),
			'category_id'				=> array('char', 		'10', 	'', 	''),
			'tag'						=> array('text', 		'', 	'', 	''),
			'publication'				=> array('char', 		'1', 	'', 	''),
			'title'						=> array('text', 		'', 	'', 	''),
			'title_img_file'			=> array('text', 		'', 	'', 	''),
			'description_flag'			=> array('char', 		'1', 	'', 	''),
			'external_link'				=> array('char', 		'1', 	'', 	''),
			'external_window'			=> array('char', 		'1', 	'', 	''),
			'url'						=> array('text', 		'', 	'', 	''),
			'description'				=> array('text', 		'', 	'', 	''),
			'del_flag'					=> array('char', 		'1', 	'', 	''),
			'folder_id'					=> array('text', 		'', 	'', 	''),
			'reserve1'					=> array('text', 		'', 	'', 	''),
			'reserve2'					=> array('text', 		'', 	'', 	''),
			'reserve3'					=> array('text', 		'', 	'', 	''),
			'create_user'				=> array('text', 		'', 	'',		''),
			'create_datetime'			=> array('text', 		'', 	'',		''),
			'update_user'				=> array('text', 		'', 	'',		''),
			'update_datetime'			=> array('text', 		'', 	'',		''),
		);

	${$g_data_set}['table']['category3'] =
		array(								// データ型			桁数	PK		AUTO-INCREMENT
			'node_id'					=> array('char',	 	'10', 	'1', 	'1'),
			'parent_node'				=> array('char', 		'10', 	'', 	''),
			'node_type'					=> array('char', 		'10', 	'', 	''),
			'node_class'				=> array('char', 		'10', 	'', 	''),
			'node_name'					=> array('text', 		'', 	'', 	''),
			'disp_seq'					=> array('int', 		'', 	'', 	''),
			'color'						=> array('text', 		'', 	'', 	''),
			'background_color'			=> array('text', 		'', 	'', 	''),
			'icon_file'					=> array('text', 		'', 	'', 	''),
			'del_flag'					=> array('char', 		'1', 	'', 	''),
			'create_user'				=> array('text', 		'', 	'',		''),
			'create_datetime'			=> array('text', 		'', 	'',		''),
			'update_user'				=> array('text', 		'', 	'',		''),
			'update_datetime'			=> array('text', 		'', 	'',		''),
		);

	${$g_data_set}['table']['user'] =
		array(								// データ型			桁数	PK		AUTO-INCREMENT
			'id'						=> array('char', 		'10', 	'1', 	'1'),
			'user_id'					=> array('char', 		'10', 	'', 	''),
			'pwd'						=> array('char', 		'20', 	'', 	''),
			'user_status'				=> array('text', 		'', 	'', 	''),
			'user_auth'					=> array('text', 		'', 	'', 	''),
			'f_name'					=> array('text', 		'', 	'', 	''),
			'g_name'					=> array('text', 		'', 	'', 	''),
			'email'						=> array('text', 		'', 	'', 	''),
			'memo'						=> array('text', 		'', 	'', 	''),
			'del_flag'					=> array('char', 		'1', 	'', 	''),
			'reserve1'					=> array('text', 		'', 	'', 	''),
			'reserve2'					=> array('text', 		'', 	'', 	''),
			'reserve3'					=> array('text', 		'', 	'', 	''),
			'create_user'				=> array('text', 		'', 	'',		''),
			'create_datetime'			=> array('text', 		'', 	'',		''),
			'update_user'				=> array('text', 		'', 	'',		''),
			'update_datetime'			=> array('text', 		'', 	'',		''),
		);
