<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	${$g_data_set}['language'] =
		array(
			'en'	=> __('English'),
			'ja'	=> __('Japanese'),
			'zh-cn'	=> __('Chinese'),
		);

	${$g_data_set}['row_per_page'] =
		array(
			'1'		=> __('1 line'),
			'10'	=> __('10 lines'),
			'20'	=> __('20 lines'),
			'50'	=> __('50 lines'),
			'100'	=> __('100 lines'),
		);

	${$g_data_set}['user_auth'] =
		array(
			'admin'		=> __('Admin'),
			'editor'	=> __('Editor'),
			'preview'	=> __('Preview'),
		);

	${$g_data_set}['user_status'] =
		array(
			'1'		=> __('Enabled'),
			'9'		=> __('Disabled'),
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
			'1'		=> __('Published'),
			'2'		=> __('Preview'),
			'3'		=> __('Closed'),
		);

	${$g_data_set}['node_status'] =
		array(
			''		=> __('Published'),
			'9'		=> __('Private'),
		);

	${$g_data_set}['description_flag'] =
		array(
			'1'		=> __('On'),
			'2'		=> __('Off'),
		);

	${$g_data_set}['external_link'] =
		array(
			''		=> __('Off'),
			'1'		=> __('On'),
		);

	${$g_data_set}['datetime_error_message'] =
		array(
			'1'		=> __(' (out of range)'),
			'2'		=> __(' (invalid time)'),
			'3'		=> __(' (invalid date)'),
			'4'		=> __(' (format error)'),
		);

	${$g_data_set}['node_error'] =
		array(
			'0'		=> __('DB error'),
			'1'		=> __('The destination folder is a subfolder of the selected folder'),
			'2'		=> __('The number of nodes are different. Please sort in the right pane.'),
			'3'		=> __('Another user has updated this record'),
		);

	${$g_data_set}['template_node_error'] =
		array(
			'0'		=> __('DB error'),
			'1'		=> __('The destination template is a subtemplate of the selecter template'),
			'2'		=> __('The number of nodes are different'),
			'3'		=> __('Another user has updated this record'),
		);

	${$g_data_set}['mail_type_settings'] =
		array(
			'contact_reply'		=> __('Contact Auto Reply'),
			'contact_notice'	=> __('Contact Notice'),
		);

	${$g_data_set}['table']['contents_node'] =
		array(							// Data Types			Length	PK		Auto-Increment
			'version_id'				=> array('char', 		'5', 	'2', 	''),
			'revision_id'				=> array('char', 		'2', 	'3', 	''),
			'node_id'					=> array('char', 		'10', 	'1', 	'1'),
			'parent_node'				=> array('char', 		'10', 	'', 	''),
			'path'						=> array('text', 		'', 	'', 	''),
			'node_type'					=> array('char', 		'10', 	'', 	''),
			'node_class'				=> array('char', 		'10', 	'', 	''),
			'node_name'					=> array('text', 		'', 	'', 	''),
			'node_status'				=> array('char', 		'1', 	'', 	''),
			'contents_id'				=> array('char', 		'10', 	'', 	''),
			'disp_seq'					=> array('text', 		'', 	'', 	''),
			'del_flag'					=> array('char', 		'1', 	'', 	''),
			'create_user'				=> array('text', 		'', 	'',		''),
			'create_datetime'			=> array('text', 		'', 	'',		''),
			'update_user'				=> array('text', 		'', 	'',		''),
			'update_datetime'			=> array('text', 		'', 	'',		''),
		);

	${$g_data_set}['table']['contents'] =
		array(							// Data Types			Length	PK		Auto-Increment
			'version_id'				=> array('char', 		'5', 	'2', 	''),
			'revision_id'				=> array('char', 		'2', 	'3', 	''),
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
			'version_id'				=> array('char', 		'5', 	'2', 	''),
			'revision_id'				=> array('char', 		'2', 	'3', 	''),
			'node_id'					=> array('char', 		'10', 	'1', 	'1'),
			'parent_node'				=> array('char', 		'10', 	'', 	''),
			'path'						=> array('text', 		'', 	'', 	''),
			'node_type'					=> array('char', 		'10', 	'', 	''),
			'node_class'				=> array('char', 		'10', 	'', 	''),
			'node_name'					=> array('text', 		'', 	'', 	''),
			'contents_id'				=> array('char', 		'10', 	'', 	''),
			'disp_seq'					=> array('text', 		'', 	'', 	''),
			'del_flag'					=> array('char', 		'1', 	'', 	''),
			'create_user'				=> array('text', 		'', 	'',		''),
			'create_datetime'			=> array('text', 		'', 	'',		''),
			'update_user'				=> array('text', 		'', 	'',		''),
			'update_datetime'			=> array('text', 		'', 	'',		''),
		);

	${$g_data_set}['table']['template'] =
		array(							// Data Types			Length	PK		Auto-Increment
			'version_id'				=> array('char',		'5', 	'2', 	''),
			'revision_id'				=> array('char', 		'2', 	'3', 	''),
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

	${$g_data_set}['table']['resource_node'] =
		array(							// Data Types			Length	PK		Auto-Increment
			'version_id'				=> array('char',	 	'5', 	'2', 	''),
			'revision_id'				=> array('char', 		'2', 	'3', 	''),
			'node_id'					=> array('char', 		'10', 	'1', 	'1'),
			'parent_node'				=> array('char', 		'10', 	'', 	''),
			'path'						=> array('text', 		'', 	'', 	''),
			'node_type'					=> array('char', 		'10', 	'', 	''),
			'node_class'				=> array('char', 		'10', 	'', 	''),
			'node_name'					=> array('text', 		'', 	'', 	''),
			'node_status'				=> array('char', 		'1', 	'', 	''),
			'file_size'					=> array('int', 		'', 	'', 	''),
			'human_file_size'			=> array('text', 		'', 	'', 	''),
			'image_size'				=> array('int', 		'', 	'', 	''),
			'human_image_size'			=> array('text', 		'', 	'', 	''),
			'contents_id'				=> array('char', 		'19', 	'', 	''),
			'disp_seq'					=> array('text', 		'', 	'', 	''),
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

	${$g_data_set}['table']['widget_node'] =
		array(							// Data Types			Length	PK		Auto-Increment
			'version_id'				=> array('char',	 	'5', 	'2', 	''),
			'revision_id'				=> array('char', 		'2', 	'3', 	''),
			'node_id'					=> array('char', 		'10', 	'1', 	'1'),
			'parent_node'				=> array('char', 		'10', 	'', 	''),
			'path'						=> array('text', 		'', 	'', 	''),
			'node_type'					=> array('char', 		'10', 	'', 	''),
			'node_class'				=> array('char', 		'10', 	'', 	''),
			'node_name'					=> array('text', 		'', 	'', 	''),
			'contents_id'				=> array('char', 		'10', 	'', 	''),
			'disp_seq'					=> array('text', 		'', 	'', 	''),
			'del_flag'					=> array('char', 		'1', 	'', 	''),
			'create_user'				=> array('text', 		'', 	'',		''),
			'create_datetime'			=> array('text', 		'', 	'',		''),
			'update_user'				=> array('text', 		'', 	'',		''),
			'update_datetime'			=> array('text', 		'', 	'',		''),
		);

	${$g_data_set}['table']['widget'] =
		array(							// Data Types			Length	PK		Auto-Increment
			'version_id'				=> array('char', 		'5', 	'2', 	''),
			'revision_id'				=> array('char', 		'2', 	'3', 	''),
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

	${$g_data_set}['table']['version'] =
		array(							// Data Types			Length	PK		Auto-Increment
			'version_id'				=> array('char', 		'5', 	'1', 	'1'),
			'private_revision_id'		=> array('char', 		'2', 	'', 	''),
			'publication_datetime_t'	=> array('text', 		'', 	'', 	''),
			'publication_datetime_u'	=> array('text', 		'', 	'', 	''),
			'publication_status'		=> array('char', 		'1', 	'', 	''),
			'version'					=> array('text', 		'', 	'', 	''),
			'notes'						=> array('text', 		'', 	'', 	''),
			'cache_w'					=> array('mediumtext', 	'', 	'', 	''),
			'cache_c'					=> array('mediumtext', 	'', 	'', 	''),
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
			'permalink'					=> array('text', 		'', 	'', 	''),
			'content1'					=> array('mediumtext', 	'', 	'', 	''),
			'content2'					=> array('mediumtext', 	'', 	'', 	''),
			'content3'					=> array('mediumtext', 	'', 	'', 	''),
			'content4'					=> array('mediumtext', 	'', 	'', 	''),
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
			'path'						=> array('text', 		'', 	'', 	''),
			'node_type'					=> array('char', 		'10', 	'', 	''),
			'node_class'				=> array('char', 		'10', 	'', 	''),
			'node_name'					=> array('text', 		'', 	'', 	''),
			'disp_seq'					=> array('text', 		'', 	'', 	''),
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
			'permalink'					=> array('text', 		'', 	'', 	''),
			'content1'					=> array('mediumtext', 	'', 	'', 	''),
			'content2'					=> array('mediumtext', 	'', 	'', 	''),
			'content3'					=> array('mediumtext', 	'', 	'', 	''),
			'content4'					=> array('mediumtext', 	'', 	'', 	''),
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
			'path'						=> array('text', 		'', 	'', 	''),
			'node_type'					=> array('char', 		'10', 	'', 	''),
			'node_class'				=> array('char', 		'10', 	'', 	''),
			'node_name'					=> array('text', 		'', 	'', 	''),
			'disp_seq'					=> array('text', 		'', 	'', 	''),
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
			'permalink'					=> array('text', 		'', 	'', 	''),
			'content1'					=> array('mediumtext', 	'', 	'', 	''),
			'content2'					=> array('mediumtext', 	'', 	'', 	''),
			'content3'					=> array('mediumtext', 	'', 	'', 	''),
			'content4'					=> array('mediumtext', 	'', 	'', 	''),
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
			'path'						=> array('text', 		'', 	'', 	''),
			'node_type'					=> array('char', 		'10', 	'', 	''),
			'node_class'				=> array('char', 		'10', 	'', 	''),
			'node_name'					=> array('text', 		'', 	'', 	''),
			'disp_seq'					=> array('text', 		'', 	'', 	''),
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
			'user_name'					=> array('text', 		'', 	'', 	''),
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

	${$g_data_set}['table']['contact'] =
		array(							// Data Types			Length	PK		Auto-Increment
			'contact_id'				=> array('char',	 	'5', 	'1', 	'1'),
			'contact_category'			=> array('text', 		'',		'',		''),
			'entry_datetime'			=> array('text', 		'',		'',		''),
			'update_datetime'			=> array('text', 		'',  	'', 	''),
			'corp'						=> array('text', 		'', 	'',		''),
			'corp_kana'					=> array('text', 		'', 	'',		''),
			'dept'						=> array('text', 		'', 	'',		''),
			'position'					=> array('text', 		'', 	'',		''),
			'f_name'					=> array('text', 		'', 	'',		''),
			'g_name'					=> array('text', 		'', 	'',		''),
			'f_name_kana'				=> array('text', 		'', 	'',		''),
			'g_name_kana'				=> array('text', 		'', 	'',		''),
			'address_zip_1'				=> array('char', 		'3', 	'', 	''),
			'address_zip_2'				=> array('char', 		'4', 	'', 	''),
			'address_pref'				=> array('char', 		'2', 	'', 	''),
			'address_1'					=> array('text', 		'',  	'', 	''),
			'address_2'					=> array('text', 		'',  	'', 	''),
			'address_3'					=> array('text', 		'',  	'', 	''),
			'tel'						=> array('text', 		'',  	'', 	''),
			'tel_1'						=> array('char', 		'5',  	'', 	''),
			'tel_2'						=> array('char', 		'4',  	'', 	''),
			'tel_3'						=> array('char', 		'5',  	'', 	''),
			'email'						=> array('text', 		'',  	'', 	''),
			'ip'						=> array('text', 		'',  	'', 	''),
			'ua'						=> array('text', 		'',  	'', 	''),
			'data1'						=> array('text', 		'',  	'', 	''),
			'data2'						=> array('text', 		'',  	'', 	''),
			'data3'						=> array('text', 		'',  	'', 	''),
			'data4'						=> array('text', 		'',  	'', 	''),
			'data5'						=> array('text', 		'',  	'', 	''),
			'reserve1'					=> array('text',		'', 	'', 	''),
			'reserve2'					=> array('text',		'', 	'', 	''),
			'reserve3'					=> array('text',		'', 	'', 	''),
			'reserve4'					=> array('text',		'', 	'', 	''),
			'reserve5'					=> array('text',		'', 	'', 	''),
		);

	${$g_data_set}['table']['mail_settings'] =
		array(							// Data Types			Length	PK		Auto-Increment
			'mail_id'					=> array('char', 		'5', 	'1', 	'1'),
			'mail_type'					=> array('text', 		'', 	'', 	''),
			'mail_title'				=> array('text', 		'', 	'', 	''),
			'mail_action'				=> array('char', 		'1', 	'', 	''),
			'mail_mark'					=> array('char', 		'1', 	'', 	''),
			'subject'					=> array('text', 		'', 	'', 	''),
			'from_name'					=> array('text', 		'', 	'', 	''),
			'from_addr'					=> array('text', 		'', 	'', 	''),
			'to_addr'					=> array('text', 		'', 	'', 	''),
			'bcc'						=> array('text', 		'', 	'', 	''),
			'body'						=> array('text', 		'', 	'', 	''),
			'html'						=> array('mediumtext', 	'', 	'', 	''),
			'body_mobile'				=> array('text', 		'', 	'', 	''),
			'control_flag'				=> array('char', 		'1', 	'', 	''),
			'del_flag'					=> array('char', 		'1', 	'', 	''),
			'reserve1'					=> array('text', 		'', 	'', 	''),
			'reserve2'					=> array('text', 		'', 	'', 	''),
			'reserve3'					=> array('text', 		'', 	'', 	''),
		);
