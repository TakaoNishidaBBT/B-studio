<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$form_config = array(
	array(
		// テーブル
		'start_html'	=> '<table class="form" border="0" cellspacing="0" cellpadding="0"><tbody>',
		'end_html'		=> '</tbody></table>',
		'db_table'		=> 'contents',

		// 管理画面タイトル
		array(
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'		=> '<th>',
				'end_html'			=> '</th>',
				'value'				=> '管理画面タイトル',
			),
			array(
				'class'				=> 'B_InputText',
				'name'				=> 'admin_site_title',
				'start_html'		=> '<td>',
				'end_html'			=> '</td>',
				'special_html'		=> 'class="textbox ime_on" size="100" maxlength="100" ',
			),
	    ),

		// DBバックアップ
		array(
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'		=> '<th>',
				'end_html'			=> '</th>',
				'value'				=> 'DBバックアップ',
			),
			array(
				'start_html'		=> '<td>',
				'end_html'			=> '</td>',
				array(
					'name'				=> 'backup',
					'start_html'		=> '<span class="download-button" onClick="bframe.submit(\'F1\', \'' . $this->module . '\', \'form\', \'backupDB\', \'\', true)" >',
					'end_html'			=> '</span>',
					'value'				=> '<img src="images/common/download.png" alt="ダウンロード" />ダウンロード',
				),
			),
	    ),

		// FULLバックアップ
		array(
			'name'			=> 'full_backup',
			'display'		=> 'none',
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'		=> '<th>',
				'end_html'			=> '</th>',
				'value'				=> 'FULLバックアップ',
			),
			array(
				'start_html'		=> '<td>',
				'end_html'			=> '</td>',
				array(
					'name'				=> 'backup',
					'start_html'		=> '<span class="download-button" onClick="bframe.submit(\'F1\', \'' . $this->module . '\', \'form\', \'backupAll\', \'\', true)" >',
					'end_html'			=> '</span>',
					'value'				=> '<img src="images/common/download.png" alt="ダウンロード" />ダウンロード',
				),
			),
	    ),
	),
);

//config
$result_config = array(
	array(
		'start_html'	=> '<form name="F1" method="post" action="index.php">',
		'end_html'		=> '</form>',
		array(
			'name'			=> 'action_message',
		),
	),
);

//control
$input_control_config = array(
	'start_html'	=> '<ul class="control">',
	'end_html'		=> '</ul>',
	array(
		'name'			=> 'confirm',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		array(
			'start_html'	=> '<span class="right-button" onclick="bframe.submit(\'F1\', \'' . $this->module . '\', \'form\', \'confirm\', \'\', true)">',
			'end_html'		=> '</span>',
			'value'			=> '確認',
		),
	),
);

//confirm control
$confirm_control_config = array(
	'start_html'	=> '<ul class="control">',
	'end_html'		=> '</ul>',
	array(
		'name'			=> 'back',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		array(
			'start_html'	=> '<span class="left-button" onclick="bframe.submit(\'F1\', \'' . $this->module . '\', \'form\', \'back\', \'\')">',
			'end_html'		=> '</span>',
			'value'			=> '戻る',
		),
	),
	array(
		'name'			=> 'regist',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		array(
			'start_html'	=> '<span class="right-button" onclick="bframe.submit(\'F1\', \'' . $this->module . '\', \'form\', \'regist\', \'\')">',
			'end_html'		=> '</span>',
			'value'			=> '登録',
		),
	),

);

//result
$result_control_config = array(
	'start_html'	=> '<ul class="control">',
	'end_html'		=> '</ul>',
	array(
		'name'			=> 'backToList',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		array(
			'start_html'	=> '<span class="left-button" style="width:180px" onclick="bframe.submit(\'F1\', \'' . $this->module . '\', \'form\', \'\', \'\')">',
			'end_html'		=> '</span>',
			'value'			=> '設定画面に戻る',
		),
	),
);
