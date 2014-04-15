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
				'class'				=> 'B_Button',
				'name'				=> 'backup',
				'start_html'		=> '<td>',
				'end_html'			=> '</td>',
				'special_html'		=> 'class="regist-button" onClick="bframe.submit(\'F1\', \'' . $this->module . '\', \'form\', \'backupDB\', \'\', true)"',
				'value'				=> '　ダウンロード　',
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
				'class'				=> 'B_Button',
				'name'				=> 'backup',
				'start_html'		=> '<td>',
				'end_html'			=> '</td>',
				'special_html'		=> 'class="regist-button" onClick="bframe.submit(\'F1\', \'' . $this->module . '\', \'form\', \'backupAll\', \'\', true)"',
				'value'				=> '　ダウンロード　',
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
		'class'			=> 'B_Submit',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		'special_html'	=> 'class="button" onClick="bframe.submit(\'F1\', \'' . $this->module . '\', \'form\', \'confirm\', \'\', true)"',
		'value'			=> '確　認',
	),
);

//confirm control
$confirm_control_config = array(
	'start_html'	=> '<ul class="control">',
	'end_html'		=> '</ul>',
	array(
		'name'			=> 'back',
		'class'			=> 'B_Button',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		'special_html'	=> 'class="button" onClick="bframe.submit(\'F1\', \'' . $this->module . '\', \'form\', \'back\', \'\');" ',
		'value'			=> '戻　る',
	),
	array(
		'name'			=> 'regist',
		'class'			=> 'B_Button',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		'special_html'	=> 'class="button" onClick="bframe.submit(\'F1\', \'' . $this->module . '\', \'form\', \'regist\', \'\');" ',
		'value'			=> '登　録',
	),
);

//result
$result_control_config = array(
	'start_html'	=> '<ul class="control">',
	'end_html'		=> '</ul>',
	array(
		'name'			=> 'backToList',
		'class'			=> 'B_Button',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		'special_html'	=> 'class="button" onClick="bframe.submit(\'F1\', \'' . $this->module . '\', \'form\', \'\', \'\')" ',
		'value'			=> '設定画面に戻る',
	),
);
