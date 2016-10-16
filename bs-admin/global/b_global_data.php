<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	${$g_data_set}['language'] =
		array(
				'en'	=> _('English'),
				'ja'	=> _('Japanese'),
		);

	${$g_data_set}['row_per_page'] =
		array(
				'1'		=> _('1 line'),
				'10'	=> _('10 lines'),
				'20'	=> _('20 lines'),
				'50'	=> _('50 lines'),
				'100'	=> _('100 lines'),
		);

	${$g_data_set}['user_auth'] =
		array(
				'admin'		=> _('Admin'),
				'editor'	=> _('Posts'),
		);

	${$g_data_set}['user_status'] =
		array(
				'1'		=> _('Enabled'),
				'9'		=> _('Disabled'),
		);

	${$g_data_set}['record_status_list'] =
		array(
				''		=> '',
				'0'		=> '',
				'1'		=> '<img src="images/common/square.png" alt="publicationed" />',
		);

	${$g_data_set}['publication_status'] =
		array(
				''		=> '',
				'0'		=> '',
				'1'		=> '<img src="images/common/square.png" alt="publicationed" />',
				'2'		=> '<img src="images/common/star.png" alt="reserved" />',
		);

	${$g_data_set}['publication'] =
		array(
				'1'		=> _('Published'),
				'2'		=> _('Preview'),
				'3'		=> _('Closed'),
		);

	${$g_data_set}['description_flag'] =
		array(
				'1'		=> _('On'),
				'2'		=> _('Off'),
		);

	${$g_data_set}['external_link'] =
		array(
				''		=> _('Off'),
				'1'		=> _('On'),
		);

	${$g_data_set}['datetime_error_message'] =
		array(
				'1'		=> _(' (out of range)'),
				'2'		=> _(' (invalid time)'),
				'3'		=> _(' (invalid date)'),
				'4'		=> _(' (format error)'),
		);

	${$g_data_set}['node_error'] =
		array(
				'0'		=> _('DB error'),
				'1'		=> _('The folder you copy or move to is the subfloder'),
				'2'		=> _('The number of nodes are differnt. Please sort in right pane.'),
				'3'		=> _('Other user updated this record'),
		);

	${$g_data_set}['template_node_error'] =
		array(
				'0'		=> _('DB error'),
				'1'		=> _('The template you copy to or move to is subtemplate'),
				'2'		=> _('The number of nodes are different'),
				'3'		=> _('Other user updated this record'),
		);

	${$g_data_set}['encoding'] =
		array(
				'UTF-8'		=> 'UTF-8',
				'EUC-JP'	=> 'EUC-JP',
				'SJIS'		=> 'SJIS',
		);

	${$g_data_set}['table']['contents_node'] =
		array(							// Data Types			Length	PK		Auto-Increment
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
		array(							// Data Types			Length	PK		Auto-Increment
			'version_id'				=> array('char', 		'5', 	'1', 	''),
			'revision_id'				=> array('char', 		'2', 	'1', 	''),
			'contents_id'				=> array('char', 		'10', 	'1', 	'1'),
			'contents_date'				=> array('char', 		'10', 	'', 	''),
			'template_id'				=> array('char', 		'10', 	'', 	''),
			'title'						=> array('text', 		'', 	'', 	''),
			'breadcrumbs'				=> array('text', 		'', 	'', 	''),
			'html1'						=> array('mediumtext', 	'', 	'', 	''),
			'html2'						=> array('mediumtext', 	'', 	'', 	''),
			'html3'						=> array('mediumtext', 	'', 	'', 	''),
			'html4'						=> array('mediumtext', 	'', 	'', 	''),
			'css'						=> array('mediumtext', 	'', 	'', 	''),
			'php'						=> array('mediumtext', 	'', 	'', 	''),
			'keywords'					=> array('text', 		'', 	'', 	''),
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
		array(							// Data Types			Length	PK		Auto-Increment
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
		array(							// Data Types			Length	PK		Auto-Increment
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
		array(							// Data Types			Length	PK		Auto-Increment
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
		array(							// Data Types			Length	PK		Auto-Increment
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
		array(							// Data Types			Length	PK		Auto-Increment
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
		array(							// Data Types			Length	PK		Auto-Increment
			'id'						=> array('char',	 	'5', 	'1', 	'1'),
			'site_title'				=> array('text', 		'', 	'', 	''),
			'admin_site_title'			=> array('text', 		'', 	'', 	''),
			'notes'						=> array('text', 		'', 	'', 	''),
			'reserve1'					=> array('text', 		'', 	'', 	''),
			'reserve2'					=> array('text', 		'', 	'', 	''),
			'reserve3'					=> array('text', 		'', 	'', 	''),
			'create_user'				=> array('text', 		'', 	'',		''),
			'create_datetime'			=> array('text', 		'', 	'',		''),
			'update_user'				=> array('text', 		'', 	'',		''),
			'update_datetime'			=> array('text', 		'', 	'',		''),
		);

	${$g_data_set}['table']['version'] =
		array(							// Data Types			Length	PK		Auto-Increment
			'version_id'				=> array('char', 		'5', 	'1', 	'1'),
			'private_revision_id'		=> array('char', 		'2', 	'', 	''),
			'publication_datetime_t'	=> array('text', 		'', 	'', 	''),
			'publication_datetime_u'	=> array('text', 		'', 	'', 	''),
			'publication_status'		=> array('char', 		'1', 	'', 	''),
			'version'					=> array('text', 		'', 	'', 	''),
			'notes'						=> array('text', 		'', 	'', 	''),
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
		array(							// Data Types			Length	PK		Auto-Increment
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
		array(							// Data Types			Length	PK		Auto-Increment
			'compare_version_id'		=> array('char', 		'5', 	'1', 	''),
		);

	${$g_data_set}['table']['article'] =
		array(							// Data Types			Length	PK		Auto-Increment
			'article_id'				=> array('char', 		'10', 	'1', 	'1'),
			'article_date_t'			=> array('text', 		'', 	'', 	''),
			'article_date_u'			=> array('text', 		'', 	'', 	''),
			'category_id'				=> array('char', 		'10', 	'', 	''),
			'keywords'					=> array('text', 		'', 	'', 	''),
			'description'				=> array('text', 		'', 	'', 	''),
			'publication'				=> array('char', 		'1', 	'', 	''),
			'title'						=> array('text', 		'', 	'', 	''),
			'title_img_file'			=> array('text', 		'', 	'', 	''),
			'description_flag'			=> array('char', 		'1', 	'', 	''),
			'external_link'				=> array('char', 		'1', 	'', 	''),
			'external_window'			=> array('char', 		'1', 	'', 	''),
			'url'						=> array('text', 		'', 	'', 	''),
			'contents'					=> array('text', 		'', 	'', 	''),
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
		array(							// Data Types			Length	PK		Auto-Increment
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
		array(							// Data Types			Length	PK		Auto-Increment
			'article_id'				=> array('char', 		'10', 	'1', 	'1'),
			'article_date_t'			=> array('text', 		'', 	'', 	''),
			'article_date_u'			=> array('text', 		'', 	'', 	''),
			'category_id'				=> array('char', 		'10', 	'', 	''),
			'keywords'					=> array('text', 		'', 	'', 	''),
			'description'				=> array('text', 		'', 	'', 	''),
			'publication'				=> array('char', 		'1', 	'', 	''),
			'title'						=> array('text', 		'', 	'', 	''),
			'title_img_file'			=> array('text', 		'', 	'', 	''),
			'description_flag'			=> array('char', 		'1', 	'', 	''),
			'external_link'				=> array('char', 		'1', 	'', 	''),
			'external_window'			=> array('char', 		'1', 	'', 	''),
			'url'						=> array('text', 		'', 	'', 	''),
			'contents'					=> array('text', 		'', 	'', 	''),
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
		array(							// Data Types			Length	PK		Auto-Increment
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
		array(							// Data Types			Length	PK		Auto-Increment
			'article_id'				=> array('char', 		'10', 	'1', 	'1'),
			'article_date_t'			=> array('text', 		'', 	'', 	''),
			'article_date_u'			=> array('text', 		'', 	'', 	''),
			'category_id'				=> array('char', 		'10', 	'', 	''),
			'keywords'					=> array('text', 		'', 	'', 	''),
			'description'				=> array('text', 		'', 	'', 	''),
			'publication'				=> array('char', 		'1', 	'', 	''),
			'title'						=> array('text', 		'', 	'', 	''),
			'title_img_file'			=> array('text', 		'', 	'', 	''),
			'description_flag'			=> array('char', 		'1', 	'', 	''),
			'external_link'				=> array('char', 		'1', 	'', 	''),
			'external_window'			=> array('char', 		'1', 	'', 	''),
			'url'						=> array('text', 		'', 	'', 	''),
			'contents'					=> array('text', 		'', 	'', 	''),
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
		array(							// Data Types			Length	PK		Auto-Increment
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
		array(							// Data Types			Length	PK		Auto-Increment
			'id'						=> array('char', 		'10', 	'1', 	'1'),
			'user_id'					=> array('char', 		'10', 	'', 	''),
			'pwd'						=> array('char', 		'20', 	'', 	''),
			'user_status'				=> array('text', 		'', 	'', 	''),
			'user_auth'					=> array('text', 		'', 	'', 	''),
			'language'					=> array('text', 		'', 	'', 	''),
			'name'						=> array('text', 		'', 	'', 	''),
			'email'						=> array('text', 		'', 	'', 	''),
			'notes'						=> array('text', 		'', 	'', 	''),
			'del_flag'					=> array('char', 		'1', 	'', 	''),
			'reserve1'					=> array('text', 		'', 	'', 	''),
			'reserve2'					=> array('text', 		'', 	'', 	''),
			'reserve3'					=> array('text', 		'', 	'', 	''),
			'create_user'				=> array('text', 		'', 	'',		''),
			'create_datetime'			=> array('text', 		'', 	'',		''),
			'update_user'				=> array('text', 		'', 	'',		''),
			'update_datetime'			=> array('text', 		'', 	'',		''),
		);
