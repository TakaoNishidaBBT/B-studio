<?php
/*
 * B-studio : Content Management System
 * Copyright (c) BigBeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/

	$texts = array(
		// Common
		'None'							=> '无数据',
		'(Test)'						=> '【??】',
		'Yes'							=> '是',
		'Extract all'					=> '打?所有',
		'Overwrite all'					=> '覆盖所有',
		'No'							=> '不是',
		'Cancel'						=> '取消',

		'1 line'						=> '1条',
		'10 lines'						=> '10条',
		'20 lines'						=> '20条',
		'50 lines'						=> '50条',
		'100 lines'						=> '100条',

		'Page:'							=> '? ：',
		'User:'							=> '用?：',
		'Log out'						=> '登出',

		'Publish version'				=> '公?版本',
		'Working version'				=> '??中版本',
		'Compare versions'				=> '版本比?',

		'Cut'							=> '剪切',
		'Copy'							=> '?制',
		'Paste'							=> '粘?',
		'Delete'						=> '?除',
		'New'							=> '新建',
		'Rename'						=> '重命名',
		'Empty the trash'				=> '清空回收站',
		'Folder'						=> '文件?',
		'File'							=> '文件',
		'Page'							=> '?',

		'Are you sure you want to move %NODE_NAME% to the trash?'
										=> '??要把“%NODE_NAME%”放入回收站?？',
		'Are you sure you want to move these %NODE_COUNT% objects to the trash?'
										=> '??要把? %NODE_COUNT% ?移?到回收站?？',
		'Are you sure you want to permanently remove the items in the trash?'
										=> '??要?除所有文件?？',
		"Another user has updated this record\nAre you sure you want to overwrite?"
										=> "文件已被其他用?更改。\n?定要覆盖更改?？",
		'Saved'							=> '文件已保存',
		'Failed to save'				=> '未能保存当前文件',
		'Already exists'				=> '已存在',
		'Please enter name'				=> '??入文件名',
		'Multi-byte characters cannot be used'
										=> '不能使用?字字符',
		'A file with this name already exists. Please enter a different name.'
										=> '无法更改文件名。指定文件名已存在。?使用其他文件名。',
		'Extension is necessary'		=> '需要文件?展名。',
		'Followed extensions can not be used (css swf jpg jpeg gif png)'
										=> '无法使用下列?展名 css swf jpg jpeg gif png',
		'Followed charcters can not be used for file name and folder name (\ / : * ? " < > | space)'
										=> '文件名或文件?名中无法使用以下字符 \ / : * ? " < > | スペース',
		'Displaying %RECORD_FROM% to %RECORD_TO% of %TOTAL% items'
										=> ' 全部%TOTAL%条相??果中　第%RECORD_FROM%～%RECORD_TO%条',

		// Message
		'Your session has timed out, Please log in again'
										=> 'SESSION 有效期已?，?重新登?。',

		// Browser check
		'Your browser is not supported. <br />Please use following browsers.'
										=> '不支持当前??器<br />?使用以下??器',
		// Login Error
		'Error'							=> '??',
		'Please enter your login ID and password correctly.'
										=> '??入正?用?ID和密?',

		// Error message for date and time
		' (out of range)'				=> '（超出范?）',
		' (invalid time)'				=> '（无效??）',
		' (invalid date)'				=> '（无效日期）',
		' (format error)'				=> '（格式??）',

		// Error message for Node
		'DB error'						=> '数据???',
		'The folder you copy or move to is the subfolder'
										=> '目?文件??当前文件?的子文件?',
		'The template you copy to or move to is subtemplate'
										=> '目?模板?当前模板的子模版',
		'The number of nodes are differnt. Please sort in right pane.'
										=> '?点数不同。 (?在右窗格中排序)',
		'The number of nodes are different'
										=> '?点数不同。',

		// Form
		'*'								=> '※',
		' is required field'			=> '：必?填写',
		'was saved.'					=> '已保存。',
		'was faild to saved.'			=> '保存失?。',
		'was registered.'				=> '已注册。',
		'was faild to register.'		=> '注册失?。',
		'was updated.'					=> '已更新。',
		'was failed to update.'			=> '更新失?。',
		'was set.'						=> '已?置。',
		'was faild to set.'				=> '?置失?。',
		'were set.'						=> '已?置。',
		'were faild to set.'			=> '?置失?。',
		'was deleted.'					=> '已?除。',
		'was faild to delete.'			=> '?除失?。',

		'Another user has updated this record'
										=> "数据已被其他用?更改。",
		// Menu
		'Open published page'			=> '打?首?',
		'Contents'						=> '内容?',
		'Templates'						=> '模板',
		'Resources'						=> '?源',
		'Resource manager'				=> '?源管理',
		'Widgets'						=> '自定?',
		'Posts'							=> '??',
		'Article'						=> '文章',
		'Article2'						=> '文章2',
		'Article3'						=> '文章3',
		'File manager'					=> '文件管理',
		'Configuration'					=> '网站?置',
		'Versions'						=> '版本管理',
		'Users'							=> '用?管理',
		'Site admin'					=> '管理??置',
		'Open another admin page'		=> '新建',

		// Buttons
		'Confirm'						=> '??',
		'Back'							=> '返回',
		'Save'							=> '保存',

		// Contents
		'HTML'							=> 'HTML',
		'WYSWYG'						=> '外?',
		'CSS'							=> 'CSS',
		'PHP'							=> 'PHP',
		'Settings'						=> '?置',
		'Preview'						=> '??',
		'Title'							=> '??',
		'Template'						=> '模板',
		'Breadcrumbs'					=> '?航',
		'Keywords'						=> '??字',
		'Description'					=> '?介',
		'External css'					=> '嵌入css',
		'External javascript'			=> '嵌入javascript',
		'Header elements'				=> '??',

		// Resource
		'Upload'						=> '上?',
		'File name'						=> '文件名',
		'Modified'						=> '更新日期',
		'File size'						=> '文件大小',
		'Image size'					=> '?片尺寸',
		'Folder is not selected'		=> '未??文件?',
		'File size is too large. Maximun upload file size is {$limit}'
										=> '所?文件?大，上?文件不能超?{$limit}',
		'Extract %FILE_NAME% ?'			=> '?定展?“%FILE_NAME%”文件？',
		'%FILE_NAME% already exists. Are you sure to overwrite?'
										=> '文件“%FILE_NAME%”已存在。<br />?定要覆盖?？',
		'Faild to create directory'		=> '无法生成目?',
		'Multi-byte characters cannot be used in file names. Please check contents of the zip file.'
										=> '文件名不能使用?字字符。（zip文件内）',
		'The uploaded file exceeds the upload_max_filesize directive in php.ini.'
										=> '上?的文件超?了 php.ini 中 upload_max_filesize ??限制的?。',
		'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.'
										=> '上?文件的大小超?了 HTML 表?中 MAX_FILE_SIZE ??指定的?。',
		'The uploaded file was only partially uploaded.'
										=> '文件只有部分被上?。',
		'No file was uploaded.'			=> '没有文件被上?。',
		'Missing a temporary folder. Introduced in PHP 5.0.3.'
										=> '找不到??文件?。PHP 4.3.10 和 PHP 5.0.3 引?。',
		'Failed to write file to disk. Introduced in PHP 5.1.0.'
										=> '文件写入失?。PHP 5.1.0 引?',
		'A PHP extension stopped the file upload.'
										=> 'PHP ?展功能已停止文件上?。',

		// Resource editor
		"Other user updated this file\nAre you sure to overwrite?"
										=> "文件已被其他用?更改。\n?定要覆盖?？",
		// Article
		'Category: '					=> '分?：',
		'Category'						=> '分?',
		'Date'							=> '日期',
		'Date: '						=> '日期：',
		'Title: '						=> '??：',
		'Open/Preview/Close'			=> '?布／??／??',
		'Published'						=> '?布',
		'Closed'						=> '??',
		'Publication date'				=> '?布日期',
		'Please enter publication date'	=> '??入?布日期',
		'Please enter title'			=> '??入??',
		'Title image'					=> '???片',
		'Display detail'				=> '内容??',
		'On'							=> '有',
		'Off'							=> '没有',
		'External link'					=> '外部?接',
		'When you choose external link on, please enter URL'
										=> '外部?接如??「有」??入URL网址',
		'Open link in new window'		=> '在新窗口中打?',
		'Detail'						=> '??',
		'Are you sure to delete?'		=> '???除?？',
		'Property'						=> '属性',
		'newCategory'					=> '新建分?',
		'newFolder'						=> '新建文件?',

		// Category
		'Will be set when you double-clidk'
										=> '?双?分?名?置',
		'Text color'					=> '字体?色',
		'Backgroud-color'				=> '背景?色',
		'Icon'							=> '??',
		'Image selection'				=> '???片',
		'This name can not be used. Because this category already exists. Please enter the other name.'
										=> '无法更改分?名。指定分?名已存在。?使用其他分?名。。',

		// Carendar
		'Calendar'						=> '日?',					
		'%MONTH% %YEAR%'				=> '%YEAR%年 %MONTH%',
		'Jan'							=> '1月',
		'Feb'							=> '2月',
		'Mar'							=> '3月',
		'Apr'							=> '4月',
		'May'							=> '5月',
		'Jun'							=> '6月',
		'Jly'							=> '7月',
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
		'Are you sure you want %NODE_NAME% to delete?'
										=> '??要?除“%NODE_NAME%”?？',
		'Are you sure you want these %NODE_COUNT% objects to delete?'
										=> '??要?除?“%NODE_COUNT%”個の項目?？',
		'Please use server browser'		=> '?使用服?器??器',

		// Version
		'Keyword'						=> '??字',
		'Keyword: '						=> '??字：',
		'Search condition'				=> '?索条件',
		'No record was found'			=> '没有找到相?内容',
		'Display'						=> '表示',
		'Search'						=> '??',
		'Clear'							=> '清空',
		'New '							=> '新建',
		'lines display'					=> '条',
		'Publish'						=> '?布',
		'Working'						=> '??中',
		'ID'							=> 'ID',
		'Publish date time'				=> '?布日期',
		'Status'						=> '状?',
		'Status ■:Published  ★:Scheduled to be published'
										=> '状?　■：?布　★：?布?定',
		'Notes'							=> '?忘?',
		'Change versions'				=> '更?版本',
		'Edit'							=> '??',
		'Diff'							=> '比?',
		'Delete'						=> '?除',
		'Submit'						=> '?置',
		'Back to list'					=> '返回列表',
		'Publish date and time'			=> '?布日期',
		'Please enter correct date and time'
										=> '??入正?日期',
		'Please enter publish date and time'
										=> '??入?布日期',
		'Format: YYYY/MM/DD hh:mm'		=> 'YYYY/MM/DD hh:mm 格式',
		'ex) 2020/01/01 12:00'			=> '例）2020/01/01 12:00',
		'Version name'					=> '版本名称',
		'Please enter version name'		=> '??入版本名称',
		'All the contents you made on this version will be completely deleted.\nThis operation can not be undone.\n\nAre your sure to delete?'
										=> '将?除所?版本的所有内容。\n?版本将无法恢?\n\n???除?版本?？',
		'Version: '						=> '版本：',
		'Publish date and time: '		=> '　?布日期：',
		'This version can not be deleted. Because it\'s not ths latest version.'
										=> '无法?除旧版本',
		'Working version can not be deleted.'
										=> '无法?除??中的版本',
		'Published version can not be deleted.'
										=> '无法?除?布中的版本',
		'Please set versions.'			=> '???版本',
		'<img src="images/common/caution.png" alt="#" />If you set scheduled to be published this version, you must set current published version'
										=> '<img src="images/common/caution.png" alt="#" />???布此版本前需?定当前?布版本。',
		'Publish version:%PUBLISH_VERSION% &nbsp;Working version:%WROKING_VERSION%'
										=> '?布版本：%PUBLISH_VERSION% &nbsp;??中版本：%WROKING_VERSION%',

		'will be registerd.'			=> '将被?置。',
		'published immediately'			=> '立即公布',
		'Scheduled to be published'		=> '??公布',

		// Diff
		'Diff Versions Left: %LEFT_VERSION% &nbsp;Right: %RIGHT_VERSION%'
										=> '版本比? Left: %LEFT_VERSION% Right: %RIGHT_VERSION%',

		// Users
		'Name'							=> '用?名',
		'User ID'						=> '用?ID',
		'User ID: '						=> '用?ID',
		'Password'						=> '密?',
		'Authority'						=> '授?',
		'Enabled'						=> '有效',
		'Disabled'						=> '无效',
		'Admin'							=> '网站管理?',
		'Editor'						=> '投稿者',
		'Please enter User ID'			=> '??入用?ID',
		'Please enter User ID using alphanumeric, hyphen(-) and underbar(_)'
										=> '用?ID?使用英文、数字、横?(-)、下??(_)?入',
		'This ID is already exists'		=> '?用?ID已登?',
		'This ID can not be used'		=> '?用?ID无法使用',
		'Please enter password'			=> '??入密?',
		'Please set user privilege'		=> '???用?授?',
		'Please enter a name'			=> '??入用?名',
		'English'						=> '英文',
		'Japanese'						=> '日文',
		'Chinese'						=> '?体中文',

		// Site admin
		'User name'						=> '用?名',
		'Login ID'						=> '用?ID',
		'Password (Re-entry)'			=> '密?（重新?入）',
		'(Password you set)'			=> '（密??置）',
		'If you change password, please enter password. If you don\'t, keep this field empty'
										=> '如需更改密?，??入新密?。否?无需填写。',
		'For confirmation, please re-enter password'
										=> '???密?，?再次?入',
		'Please enter user name'		=> '??入用?名',
		'Please enter login ID'			=> '??入用?ID',
		'Please enter login ID using alphanumeric, hyphen(-) and underbar(_)'
										=> '用?ID?使用英文、数字、横?(-)、下??(_)?入',
		'Password is not matched'		=> '密?与??密?不一致',
		'Back to site admin form'		=> '返回到管理??置',
		'Configuration of site admin was updated'
										=> '网站管理??置已更新',

		// Configuration
		'Admin page title'				=> '网站??',
		'Language'						=> '?言',
		'DB backup'						=> '数据???',
		'Full backup'					=> '网站完整??',
		'Re-install backup'				=> '重新安装??',
		'Download'						=> '下?',
		'Back to configuration form'	=> '返回到网站?置',
		'Configuration: Saved'			=> '网站?置：已保存。',
		'Configuration: Failed'			=> '网站?置：保存失?。',

		// Install
		'Select language: '				=> '???使用?言：',
		'Host name'						=> '主机名',
		'Please enter host name'		=> '??入主机名',
		'Please confirm the input content'
										=> '????入内容',
		'Please enter user name using alphanumeric, hyphen(-) and underbar(_)'
										=> '用?名?使用英文、数字、横?(-)、下??(_)?入',
		'Please enter password using alphanumeric, hyphen(-) and underbar(_)'
										=> '密??使用英文、数字、横?(-)、下??(_)?入',
		'Schema name'					=> '数据?名',
		'Please enter schema name'		=> '??入数据?名',
		'Table prefix'					=> '表前?名',
		'Please enter table prefix'		=> '??入表前?名',
		'Usually changing this field is unnecessary. This field could be changed when B-studio will be installed in one schema.'
										=> '※此?目通常不需要?行更改,在同一数据?中安装多个B-studio???定不同前?',
		'Please re-enter password'		=> '?再次?入密?',
		'Password is not matched'		=> '密?与??密?不一致',
		'(Entered password)'			=> '（密??置）',

		'Please enable mbstring module'	=> '??用 mbstring ?展模?。',
		'Please set session.save_path'	=> '??定 session.save_path ?目。',
		'Please enable MySQL library'	=> '??用 MySQL 数据?。',
		'Please enable GD library'		=> '??用 GD ?形?。',
		'ZipArchive is necessary'		=> '安装需要 ZipArchive?。',

		' : write permission is OK. '	=> ' : 已授?文本写入?限。',
		' : write permission is not set. '
										=> ' : 没有授?文本写入?限。',

		'Connecting to the DB is OK. But failed to select the schema'
										=> '成功?接至数据?，但没有找到指定?架',
		'Faild to connect DB.'			=> '?接数据?失?。',
		'This is an error in your entry<br />Please check any error message and re-enter the necessary information'
										=> '?入内容有?。<br />?参照各?提示，重新?入。',

		'Faild to create a table.'		=> '?建表?出???。',
		'Faild to create a view.'		=> '?建虚?表?出???。',
		'Faild to insert a version record.'
										=> '?建版本数据?出???。',
		'Faild to insert current version record.'
										=> '写入?布版本数据?出???',
		'Faild to insert a configuration record.'
										=> '写入网站?置数据?出???。',
	);
