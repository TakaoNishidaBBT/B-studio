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

		// ID
		array(
			'name'			=> 'version_id_row',
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th class="top">',
				'end_html'				=> '</th>',
				'value'					=> 'ID',
			),
			array(
				'name'					=> 'version_id',
				'class'					=> 'B_Text',
				'start_html'			=> '<td class="top">',
				'end_html'				=> '</td>',
			),
		),

		// 公開日
		array(
			'error_group'	=> true,
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'end_html'				=> '</th>',
				'invalid_start_html'	=> '<th class="error">',
				'value'					=> '公開日<span class="require">※</span>',
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'					=> 'publication_datetime_t',
					'class'					=> 'B_InputText',
					'special_html'			=> 'class="textbox ime_off"',
					'format'				=> 'Y/m/d H:i',
					'data_set'				=> 'datetime_error_message',
					'validate'				=>
					array(
						array(
							'type' 			=> 'text_datetime',
							'error_message'	=> '正しい日時を入力してください。',
						),
						array(
							'type' 			=> 'required',
							'delimiter'		=> '/',
							'error_message'	=> '公開日を入力してください。',
						),
					),
				),
				array(
					'class'		=> 'B_Guidance',
					'value'		=> 'YYYY/MM/DD hh:mmの形式　例）2011/01/01 12:00',
				),
				array(
					'name'					=> 'error_message',
					'class'					=> 'B_ErrMsg',
					'start_html'			=> '<p class="error-message">',
					'end_html'				=> '</p>',
				),
			),
		),

		// バージョン（名称）
		array(
			'error_group'	=> true,
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'end_html'				=> '</th>',
				'invalid_start_html'	=> '<th class="error">',
				'value'					=> 'バージョン（名称）<span class="require">※</span>',
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'					=> 'version',
					'class'					=> 'B_InputText',
					'special_html'			=> 'class="textbox ime_on" size="80" maxlength="100"',
					'validate'				=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> 'バージョン（名称）を入力してください',
						),
					),
				),
				array(
					'name'					=> 'error_message',
					'class'					=> 'B_ErrMsg',
					'start_html'			=> '<p class="error-message">',
					'end_html'				=> '</p>',
				),
			),
		),

		// メモ
		array(
			'error_group'	=> true,
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'	=> '<th>',
				'end_html'		=> '</th>',
				'value'			=> 'メモ',
			),
			array(
				'name'			=> 'memo',
				'class'			=> 'B_TextArea',
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				'special_html'	=> 'class="textarea" rows="5"',
			),
	    ),
	),
);

//result
$result_config = array(
	array(
		'start_html'	=> '<form name="F1" method="post" action="index.php">',
		'end_html'		=> '</form>',
		array(
			'start_html'    => '<p>',
			'end_html'	    => '</p>',
			array(
				array(
					'value'					=> 'バージョン：',
				),
				array(
					'name'					=> 'version',
					'class'					=> 'B_Text',
					'start_html'			=> '<strong>',
					'end_html'				=> '</strong>',
				),
				array(
					'value'					=> '　公開日時：',
				),
				array(
					'name'					=> 'publication_datetime_t',
					'class'					=> 'B_Text',
					'start_html'			=> '<strong>',
					'end_html'				=> '</strong>',
				),
				array(
					'name'					=> 'action_message',
				),
			),
		),
	),
);
//control
$input_control_config = array(
	'start_html'	=> '<ul class="control">',
	'end_html'		=> '</ul>',
	array(
		'name'			=> 'back',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		array(
			'start_html'	=> '<span class="left-button" onclick="bframe.submit(\'F1\', \'' . $this->module . '\', \'list\', \'back\', \'\')">',
			'end_html'		=> '</span>',
			'value'			=> '戻る',
		),
	),
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

//delete control
$delete_control_config = array(
	'start_html'	=> '<ul class="control">',
	'end_html'		=> '</ul>',
	array(
		'name'			=> 'back',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		array(
			'start_html'	=> '<span class="left-button" onclick="bframe.submit(\'F1\', \'' . $this->module . '\', \'list\', \'back\', \'\')">',
			'end_html'		=> '</span>',
			'value'			=> '戻る',
		),
	),
	array(
		'name'			=> 'regist',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		array(
			'start_html'	=> '<span class="right-button" onclick="bframe.confirmSubmit(\'このバージョンで作成したコンテンツすべてを削除します。\nこの作業は元に戻せません\n\nよろしいですか？\', \'F1\', \'' . $this->module . '\', \'form\', \'delete\', \'\');">',
			'end_html'		=> '</span>',
			'value'			=> '削除',
		),
	),
);

//control
$result_control_config = array(
	'start_html'	=> '<ul class="control">',
	'end_html'		=> '</ul>',
	array(
		'name'			=> 'backToList',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		array(
			'start_html'	=> '<span class="left-button" style="width:150px" onclick="bframe.submit(\'F1\', \'' . $this->module . '\', \'list\', \'back\', \'\')">',
			'end_html'		=> '</span>',
			'value'			=> '一覧に戻る',
		),
	),
);
