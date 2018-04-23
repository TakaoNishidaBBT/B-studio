<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/

	$texts = array(
		// Common
		'None'							=> '无数据',
		'(Test)'						=> '【测试】',
		'Yes'							=> '是',
		'Extract all'					=> '打开所有',
		'Overwrite all'					=> '覆盖所有',
		'No'							=> '不是',
		'Cancel'						=> '取消',

		'1 line'						=> '1条',
		'10 lines'						=> '10条',
		'20 lines'						=> '20条',
		'50 lines'						=> '50条',
		'100 lines'						=> '100条',

		'Page:'							=> '页 ：',
		'User:'							=> '用户：',
		'Log out'						=> '登出',

		'Published version'				=> '公开版本',
		'Working version'				=> '编辑中版本',
		'Compare versions'				=> '版本比较',

		'Cut'							=> '剪切',
		'Copy'							=> '复制',
		'Paste'							=> '粘贴',
		'Delete'						=> '删除',
		'New'							=> '新建',
		'Rename'						=> '重命名',
		'Empty the trash'				=> '清空回收站',
		'Folder'						=> '文件夹',
		'folder'						=> '文件夹',
		'File'							=> '文件',
		'file'							=> '文件',
		'Page'							=> '页',

		'Are you sure you want to move %NODE_NAME% to the trash?'
										=> '确实要把“%NODE_NAME%”放入回收站吗？',
		'Are you sure you want to move these %NODE_COUNT% objects to the trash?'
										=> '确实要把这 %NODE_COUNT% 项移动到回收站吗？',
		'Are you sure you want to permanently remove the items in the trash?'
										=> '确实要删除所有文件吗？',
		"Another user has updated this file\nAre you sure you want to overwrite?"
										=> "文件已被其他用户更改。\n确定要覆盖更改吗？",
		'Saved'							=> '文件已保存',
		'Failed to save'				=> '文件保存失败',
		'Already exists'				=> '已存在',
		'Please enter name'				=> '请输入名称',
		'Please enter a name for the %ITEM%'
										=> '请输入%ITEM%名',
		'Multi-byte characters cannot be used'
										=> '不能使用汉字字符',
		'A %ITEM% with this name already exists. Please enter a different name.'
										=> '无法更改文件名。指定文件名已存在。请使用其他文件名。',
		'Please enter the file extension'
										=> '需要文件扩展名。',
		'The following extensions cannot be used (css swf jpg jpeg gif png)'
										=> '无法使用下列扩展名 css swf jpg jpeg gif png',
		'The following characters cannot be used in file or folder names (\ / : * ? " \' < > | space)'
										=> '文件名或文件夹名中无法使用以下字符 \ / : * ? " \' < > | 空格',
		'Displaying %RECORD_FROM% to %RECORD_TO% of %TOTAL% items'
										=> ' 全部%TOTAL%条相关结果中　第%RECORD_FROM%～%RECORD_TO%条',

		// Message
		'Your session has timed out. Please log in again'
										=> 'SESSION 有效期已过。请重新登陆。',

		// Browser check
		'Your browser is not supported. <br />Please use one of the browsers listed below.'
										=> '不支持当前浏览器<br />请使用以下浏览器',
		// Login Error
		'Error'							=> '错误',
		'The Login ID or password you entered is invalid.'
										=> '请输入正确用户ID和密码',

		// Error message for date and time
		' (out of range)'				=> '（超出范围）',
		' (invalid time)'				=> '（无效时间）',
		' (invalid date)'				=> '（无效日期）',
		' (format error)'				=> '（格式错误）',

		// Error message for Node
		'DB error'						=> '数据库错误',
		'The destination folder is a subfolder of the selected folder'
										=> '目标文件夹为当前文件夹的子文件夹',
		'The destination template is a subtemplate of the selecter template'
										=> '目标模板为当前模板的子模版',
		'The number of nodes are different. Please sort in the right pane.'
										=> '节点数不同。 (请在右窗格中排序)',
		'The number of nodes are different'
										=> '节点数不同。',

		// Form
		'*'								=> '※',
		' Indicates required field'		=> '：必须填写',
		'was saved.'					=> '已保存。',
		'was failed to saved.'			=> '保存失败。',
		'was registered.'				=> '已注册。',
		'was failed to register.'		=> '注册失败。',
		'was updated.'					=> '已更新。',
		'was failed to update.'			=> '更新失败。',
		'was set.'						=> '已设置。',
		'was failed to set.'			=> '设置失败。',
		'were set.'						=> '已设置。',
		'were failed to set.'			=> '设置失败。',
		'was deleted.'					=> '已删除。',
		'was failed to delete.'			=> '删除失败。',

		'Another user has updated this record'
										=> "数据已被其他用户更改。",
		// Menu
		'Open published page'			=> '打开首页',
		'Contents'						=> '内容页',
		'Templates'						=> '模板',
		'Resources'						=> '资源',
		'Resource Manager'				=> '资源管理',
		'Widgets'						=> '自定义小工具',
		'widget'						=> '自定义小工具',
		'Posts'							=> '写文章',
		'Article'						=> '文章',
		'Article2'						=> '文章2',
		'Article3'						=> '文章3',
		'File Manager'					=> '多媒体',
		'Basic Settings'				=> '常规选项',
		'Versions'						=> '版本管理',
		'Users'							=> '用户管理',
		'Site Admin'					=> '管理员设置',
		'Open another admin page'		=> '新建',

		// Buttons
		'Confirm'						=> '确认',
		'Back'							=> '返回',
		'Save'							=> '保存',

		// Contents
		'HTML'							=> 'HTML',
		'Visual'						=> '外观',
		'CSS'							=> 'CSS',
		'PHP'							=> 'PHP',
		'Settings'						=> '设置',
		'Preview'						=> '预览',
		'Title'							=> '标题',
		'Template'						=> '模板',
		'template'						=> '模板',
		'Breadcrumbs'					=> '导航',
		'Keywords'						=> '关键字',
		'Description'					=> '简介',
		'External css'					=> '嵌入css',
		'External javascript'			=> '嵌入javascript',
		'Header elements'				=> '标签',

		// Resource
		'Upload'						=> '上传',
		'File name'						=> '文件名',
		'Modified'						=> '更新日期',
		'File size'						=> '文件大小',
		'Image size'					=> '图片尺寸',
		'No folder selected'			=> '未选择文件夹',
		'The file size is too large. The maximun file upload size is %LIMIT%'
										=> '所选文件过大，上传文件不能超过 %LIMIT%',
		'Extract %FILE_NAME% ?'			=> '确定展开“%FILE_NAME%”文件？',
		'%FILE_NAME% already exists.<br />Are you sure you want to overwrite?'
										=> '文件“%FILE_NAME%”已存在。<br />确定要覆盖吗？',
		'Failed to create directory'	=> '无法生成目录',
		'Multi-byte characters cannot be used in file names. Please check contents of the zip file.'
										=> '文件名不能使用汉字字符。（zip压缩文件内）',
		'The uploaded file exceeds the upload_max_filesize directive in php.ini.'
										=> '上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值。',
		'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.'
										=> '上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值。',
		'The uploaded file was only partially uploaded.'
										=> '文件只有部分被上传。',
		'No file was uploaded.'			=> '没有文件被上传。',
		'Missing a temporary folder. Introduced in PHP 5.0.3.'
										=> '找不到临时文件夹。PHP 4.3.10 和 PHP 5.0.3 引进。',
		'Failed to write file to disk. Introduced in PHP 5.1.0.'
										=> '文件写入失败。PHP 5.1.0 引进',
		'A PHP extension stopped the file upload.'
										=> 'PHP 扩展功能已停止文件上传。',

		// Article
		'Category: '					=> '分类：',
		'Category'						=> '分类',
		'category'						=> '分类',
		'Date'							=> '日期',
		'Date: '						=> '日期：',
		'Permalink'						=> 'Permalink',
		'Title: '						=> '标题：',
		'Published/Preview/Closed'		=> '发布／预览／关闭',
		'Published'						=> '发布',
		'Private'						=> 'Private',
		'Closed'						=> '关闭',
		'Publication date'				=> '发布日期',
		'Please enter publication date'	=> '请输入发布日期',
		'Please enter title'			=> '请输入标题',
		'Title image'					=> '标题图片',
		'Display detail'				=> '内容详细',
		'On'							=> '有',
		'Off'							=> '没有',
		'External link'					=> '外部链接',
		'After turninng external link on, please enter URL'
										=> '外部链接如选择「有」请输入URL链接',
		'Open link in new window'		=> '在新窗口中打开',
		'Content1'						=> '详细1',
		'Content2'						=> '详细2',
		'Content3'						=> '详细3',
		'Content4'						=> '详细4',
		'Are you sure you want to delete?'
										=> '确实删除吗？',
		'Properties'					=> '属性',
		'newCategory'					=> '新建分类',
		'newFolder'						=> '新建文件夹',
		'This is an error in your entry'=> '输入内容有误。',
		'This permalink is already in use'
										=> '该用户permalink已登录',

		// Category
		'Double-click to set'			=> '双击分类名设置',
		'Text color'					=> '字体颜色',
		'Backgroud color'				=> '背景颜色',
		'Icon'							=> '图标',
		'Image selection'				=> '选择图片',

		// Calendar
		'Calendar'						=> '日历',					
		'%MONTH% %YEAR%'				=> '%YEAR%年 %MONTH%',
		'Jan'							=> '1月',
		'Feb'							=> '2月',
		'Mar'							=> '3月',
		'Apr'							=> '4月',
		'May'							=> '5月',
		'Jun'							=> '6月',
		'Jul'							=> '7月',
		'Aug'							=> '8月',
		'Sep'							=> '9月',
		'Oct'							=> '10月',
		'Nov'							=> '11月',
		'Dec'							=> '12月',

		'Sun'							=> '日',
		'Mon'							=> '一',
		'Tue'							=> '二',
		'Wed'							=> '三',
		'Thu'							=> '四',
		'Fri'							=> '五',
		'Sat'							=> '六',

		// File manager
		'Are you sure you want to delete %NODE_NAME%?'
										=> '确实要删除“%NODE_NAME%”吗？',
		'Are you sure you want to delete these %NODE_COUNT% objects?'
										=> '确实要删除这“%NODE_COUNT%”個の項目吗？',
		'Please use server browser'		=> '请使用服务器浏览器',
		'An error has occurred'			=> '发生错误',
		'The name could not be changed'	=> '无法更改文件名',
		'Folder is not allowed'			=> 'Folder is not allowed',

		// Version
		'Keyword'						=> '关键字',
		'Keyword: '						=> '关键字：',
		'Search conditions'				=> '检索条件',
		'No record found'				=> '没有找到相关内容',
		'Display'						=> '表示',
		'Search'						=> '查询',
		'Clear'							=> '清空',
		'New '							=> '新建',
		'Publish'						=> '发布',
		'Working'						=> '编辑中',
		'ID'							=> 'ID',
		'Publish date/time'				=> '发布日期',
		'Status'						=> '状态',
		'Status ■:Published  ★:Scheduled to be published'
										=> '状态　■：发布　★：发布预定',
		'Notes'							=> '备忘录',
		'Change versions'				=> '更换版本',
		'Edit'							=> '编辑',
		'Compare'						=> '比较',
		'Delete'						=> '删除',
		'Submit'						=> '设置',
		'Back to list'					=> '返回列表',
		'Publish date and time'			=> '发布日期',
		'Please enter correct date and time'
										=> '请输入正确日期',
		'Please enter publish date and time'
										=> '请输入发布日期',
		'Format: YYYY/MM/DD hh:mm'		=> 'YYYY/MM/DD hh:mm 格式',
		'ex) 2020/01/01 12:00'			=> '例）2020/01/01 12:00',
		'Version name'					=> '版本名称',
		'Please enter version name'		=> '请输入版本名称',
		'All the contents in this version will be completely deleted.\nThis operation cannot be undone.\n\nAre your sure you want to delete?'
										=> '将删除所选版本的所有内容。\n该版本将无法恢复\n\n确实删除该版本吗？',
		'Version : '					=> '版本：',
		'Publish date and time : '		=> '　发布日期：',
		'This version can not be deleted because it is not ths most recent version.'
										=> '无法删除旧版本',
		'The working version cannot be deleted.'
										=> '无法删除编辑中的版本',
		'The published version cannot be deleted.'
										=> '无法删除发布中的版本',
		'Please select version.'		=> '请选择版本',
		'<img src="images/common/caution.png" alt="#" />If you schedule this version to be published at a later date, you must set the current published version'
										=> '<img src="images/common/caution.png" alt="#" />预约发布此版本前需设定当前发布版本。',
		'Published version:%PUBLISH_VERSION% &nbsp;Working version:%WROKING_VERSION%'
										=> '发布版本：%PUBLISH_VERSION% &nbsp;编辑中版本：%WROKING_VERSION%',

		'will be set.'					=> '将被设置。',
		'Changes will be reflected immediately'	
										=> '立即公布',
		'Scheduled to be published'		=> '预约公布',
		'Failed to delete version records (%TABLE_NAME%)'
										=> 'Failed to delete version records (%TABLE_NAME%)',

		// Compare
		'Compare Versions Left: %LEFT_VERSION% &nbsp;Right: %RIGHT_VERSION%'
										=> '版本比较 Left: %LEFT_VERSION% Right: %RIGHT_VERSION%',

		// Users
		'Name'							=> '用户名',
		'User ID'						=> '用户ID',
		'User ID: '						=> '用户ID',
		'Password'						=> '密码',
		'User type'						=> '授权',
		'Enabled'						=> '有效',
		'Disabled'						=> '无效',
		'Admin'							=> '网站管理员',
		'Editor'						=> '投稿者',
		'Please enter user ID'			=> '请输入用户ID',
		'Please enter user ID using only alphanumeric, hyphen(-) and underbar(_)'
										=> '用户ID请使用英文、数字、横线(-)、下划线(_)输入',
		'This ID is already in use'		=> '该用户ID已登录',
		'This ID cannot be used'		=> '该用户ID无法使用',
		'Please enter password'			=> '请输入密码',
		'Please select user type'		=> '请选择用户授权',
		'Please enter a name'			=> '请输入用户名',
		'English'						=> '英文',
		'Japanese'						=> '日文',
		'Chinese'						=> '简体中文',

		// Site admin
		'Username'						=> '用户名',
		'Login ID'						=> '用户ID',
		'Password (Re-entry)'			=> '密码（重新输入）',
		'(set password)'				=> '（密码设置）',
		'If you would like to change your password, please enter new password here. If not, please leave this field blank.'
										=> '如需更改密码，请输入新密码。否则无需填写。',
		'For confirmation, please re-enter password'
										=> '为确认密码，请再次输入',
		'Please enter username'			=> '请输入用户名',
		'Please enter login ID'			=> '请输入用户ID',
		'Please enter login ID using only alphanumeric, hyphen(-) and underbar(_)'
										=> '用户ID请使用英文、数字、横线(-)、下划线(_)输入',
		'Please enter password using only alphanumeric, hyphen(-) and underbar(_)'
										=> '密码请使用英文、数字、横线(-)、下划线(_)输入',
		'Password dose not match'		=> '密码与确认密码不一致',
		'Back to site admin settings'	=> '返回到管理员设置',
		'The site admin settings has been updated'
										=> '网站管理员设置已更新',

		// Basic settings
		'Admin page title'				=> '网站标题',
		'Language'						=> '语言',
		'DB backup'						=> '数据库备份',
		'Full backup'					=> '网站完整备份',
		'Re-install backup'				=> '重新安装备份',
		'Download'						=> '下载',
		'Back to basic settings'		=> '返回到常规选项',
		'Basic settings: Saved'			=> '常规选项：已保存。',
		'Basic settings: Failed'		=> '常规选项：保存失败。',

		// Install
		'Select language: '				=> '请选择使用语言：',
		'Hostname'						=> '主机名',
		'Please enter hostname'			=> '请输入主机名',
		'Please confirm the input content'
										=> '请确认输入内容',
		'Please enter username using only alphanumeric, hyphen(-) and underbar(_)'
										=> '用户名请使用英文、数字、横线(-)、下划线(_)输入',
		'Please enter password using only alphanumeric, hyphen(-) and underbar(_)'
										=> '密码请使用英文、数字、横线(-)、下划线(_)输入',
		'Schema name'					=> '数据库名',
		'Please enter schema name'		=> '请输入数据库名',
		'Table prefix'					=> '表前缀名',
		'Please enter table prefix'		=> '请输入表前缀名',
		'Usually changing this field is unnecessary. Please change This field when installing B-studio multiple times in one schema.'
										=> '※此项目通常不需要进行更改,在同一数据库中安装多个B-studio时请设定不同前缀',
		'Please re-enter password'		=> '请再次输入密码',
		'Password does not match'		=> '密码与确认密码不一致',
		'(Set password)'				=> '（密码设置）',

		'Please enable mbstring module'	=> '请启用 mbstring 扩展模块。',
		'Please set session.save_path'	=> '请设定 session.save_path 项目。',
		'Please enable MySQL library'	=> '请启用 MySQL 数据库。',
		'Please enable GD library'		=> '请启用 GD 图形库。',
		'Please enable exif library'	=> '请启用 exif 图形库。',
		'Please enable SimpleXML library'
										=> '请启用 SimpleXML 图形库。',
		'ZipArchive is necessary'		=> '安装需要 ZipArchive类。',

		' : write permission granted. '	=> ' : 已授权文本写入权限。',
		' : write permission not set. '	=> ' : 没有授权文本写入权限。',
		' : execute permission granted. '
										=> ' : execute permission granted. ',
		' : execute permission not set. '
										=> ' : execute permission not set. ',

		'Able to connect to DB but failed to select schema.'
										=> '成功连接至数据库，但没有找到指定构架',
		'Failed to connect to DB.'		=> '连接数据库失败。',
		'This is an error in your entry<br />Please check any error message and re-enter the necessary information'
										=> '输入内容有误。<br />请参照各项提示，重新输入。',

		'Failed to create table.'		=> '创建表时出现错误。',
		'Failed to create view.'		=> '创建虚拟表时出现错误。',
		'Failed to create version record.'
										=> '创建版本数据时出现错误。',
		'Failed to create current version record.'
										=> '写入发布版本数据时出现错误',
		'Failed to create configuration record.'
										=> '写入网站设置数据时出现错误。',
	);
