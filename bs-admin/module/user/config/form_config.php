<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$form_config = array(
	array('class' => 'B_Hidden', 'name' => 'mode'),
	array('class' => 'B_Hidden', 'name' => 'id'),
	array('class' => 'B_Hidden', 'name' => 'user_id'),
	array(
		// テーブル
		'start_html'	=> '<table class="form" border="0" cellspacing="0" cellpadding="0"><tbody>',
		'end_html'		=> '</tbody></table>',
		// ユーザID
		array(
			'error_group'	=> true,
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'end_html'				=> '</th>',
				'invalid_start_html'	=> '<th class="error">',
				'value'					=> 'ユーザID<span class="require">※</span>',
			),
			array(
				'start_html'			=> '<td>',
				'end_html'				=> '</td>',
				array(
					'config_filter'			=> 'insert',
					'name'					=> 'user_id',
					'class'					=> 'B_InputText',
					'special_html'			=> 'class="textbox ime_off" maxlength="10" size="20"',
					'validate'				=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> 'ユーザIDを入力してください',
						),
						array(
							'type' 			=> 'pattern',
							'pattern'		=> '^[a-zA-Z0-9\_\-]+$',
							'error_message'	=> 'ユーザIDは英数とハイフン(-)アンダーバー(_)で入力してください',
						),
						array(
							'type'			=> 'callback',
							'obj'			=> $this,
							'method'		=> '_validate_callback',
							'error_message'	=> '既に登録されています',
						),
						array(
							'type'			=> 'callback',
							'obj'			=> $this,
							'method'		=> '_validate_callback2',
							'error_message'	=> 'そのIDは登録できません',
						),
					),
				),
				array(
					'config_filter'			=> 'update/delete',
					'class'					=> 'B_Text',
					'name'					=> 'user_id',
				),
				array(
					'name'					=> 'error_message',
					'class'					=> 'B_ErrMsg',
					'start_html'			=> '<span class="error-message">',
					'end_html'				=> '</span>',
				),
			),
		),

		// パスワード
		array(
			'error_group'	=> true,
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="error">',
				'end_html'				=> '</th>',
				'value'					=> 'パスワード<span class="require">※</span>',
			),
			array(
				'start_html'			=> '<td>',
				'end_html'				=> '</td>',
				array(
					'name'					=> 'pwd',
					'class'					=> 'B_InputText',
					'special_html'			=> 'class="textbox ime_off" size="20" maxlength="100" ',
					'validate'				=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> 'パスワードを入力してください。',
						),
					),
				),
				array(
					'name'					=> 'error_message',
					'class'					=> 'B_ErrMsg',
					'start_html'			=> '<span class="error-message">',
					'end_html'				=> '</span>',
				),
			),
	    ),

		// 状態
		array(
			'error_group'	=> true,
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="error">',
				'end_html'				=> '</th>',
				'value'					=> '状態<span class="require">※</span>',
			),
			array(
				'name'					=> 'user_status',
				'class'					=> 'B_SelectBox',
				'start_html'			=> '<td>',
				'end_html'				=> '</td>',
				'data_set'				=> 'user_status',
			),
	    ),

		// ユーザ権限
		array(
			'error_group'	=> true,
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="error">',
				'end_html'				=> '</th>',
				'value'					=> 'ユーザ権限<span class="require">※</span>',
			),
			array(
				'name'					=> 'user_auth',
				'class'					=> 'B_SelectBox',
				'start_html'			=> '<td>',
				'end_html'				=> '</td>',
				'data_set'				=> 'user_auth',
				'validate'				=>
				array(
					array(
						'type' 			=> 'required',
						'error_message'	=> 'ユーザ権限を設定してください。',
					),
				),
				array(
					'name'					=> 'error_message',
					'class'					=> 'B_ErrMsg',
					'start_html'			=> '<span class="error-message">',
					'end_html'				=> '</span>',
				),
			),
	    ),

		// 氏名
		array(
			'error_group'	=> true,
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="error">',
				'end_html'				=> '</th>',
				array(
					'value'				=> '氏名',
					'no_linefeed'		=> true,
				),
				array(
					'class'				=> 'B_Guidance',
					'value'				=> '<span class="require">※</span>',
				),
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'class'				=> 'B_Guidance',
					'value'				=> '姓',
				),
				array(
					'name'					=> 'f_name',
					'class'					=> 'B_InputText',
					'special_html'			=> 'class="textbox ime-on" size="20" maxlength="100" ',
					'validate'				=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> '氏名を入力してください',
						),
					),
				),
				array(
					'class'				=> 'B_Guidance',
					'value'				=> '名',
				),
				array(
					'name'					=> 'g_name',
					'class'					=> 'B_InputText',
					'special_html'			=> 'class="textbox ime-on" size="20" maxlength="100" ',
					'validate'				=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> '氏名を入力してください',
						),
					),
				),
				array(
					'name'					=> 'error_message',
					'class'					=> 'B_ErrMsg',
					'start_html'			=> '<span class="error-message">',
					'end_html'				=> '</span>',
				),
			),
	    ),

		// 備考
		array(
			'error_group'	=> true,
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="error">',
				'end_html'				=> '</th>',
				'value'					=> '備考',
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'				=> 'memo',
					'class'				=> 'B_TextArea',
					'special_html'		=> 'class="textarea" cols="78" rows="5"',
				),
			),
		),
	),
);

// control
$input_control_config = array(
	'start_html'	=> '<ul class="control">',
	'end_html'		=> '</ul>',
	array(
		'class'			=> 'B_Button',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		'special_html'	=> 'class="button" onClick="bframe.submit(\'F1\', \'' . $this->module . '\', \'list\', \'back\', \'\')"',
		'value'			=> '戻　る',
	),
	array(
		'auth_filter'	=> 'super_admin/admin',
		'class'			=> 'B_Submit',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		'special_html'	=> 'class="button" onClick="bframe.submit(\'F1\', \'' . $this->module . '\', \'form\', \'confirm\', \'\', true)"',
		'value'			=> '確　認',
	),
);

// control
$delete_control_config = array(
	'start_html'	=> '<ul class="control">',
	'end_html'		=> '</ul>',
	array(
		'class'			=> 'B_Button',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		'special_html'	=> 'class="button" onClick="bframe.submit(\'F1\', \'' . $this->module . '\', \'list\', \'back\', \'\')"',
		'value'			=> '戻　る',
	),
	array(
		'auth_filter'	=> 'super_admin/admin',
		'class'			=> 'B_Submit',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		'special_html'	=> 'class="button" onClick="return bframe.confirmSubmit(\'このレコードを物理削除します。\n\nよろしいですか？\', \'F1\', \'' . $this->module . '\', \'form\', \'regist\', \'delete\')"',
		'value'			=> '削　除',
	),
);

//confirm control
$confirm_control_config = array(
	'start_html'	=> '<ul class="control">',
	'end_html'		=> '</ul>',
	array(
		'class'			=> 'B_Button',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		'special_html'	=> 'class="button" onClick="bframe.submit(\'F1\', \'' . $this->module . '\', \'form\', \'back\', \'\');" ',
		'value'			=> '戻　る',
	),
	array(
		'class'			=> 'B_Button',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		'special_html'	=> 'class="button" onClick="bframe.submit(\'F1\', \'' . $this->module . '\', \'form\', \'regist\', \'\');" ',
		'value'			=> '登　録',
	),
);


//control
$result_control_config = array(
	'start_html'	=> '<ul class="control">',
	'end_html'		=> '</ul>',
	array(
		'class'			=> 'B_Button',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		'special_html'	=> 'class="button" onClick="bframe.submit(\'F1\', \'' . $this->module . '\', \'list\', \'back\', \'\')" ',
		'value'			=> '一覧に戻る',
	),
);

//config
$result_config = array(
	array(
		'start_html'	=> '<form name="F1" method="post" action="index.php">',
		'end_html'		=> '</form>',
		array(
			'start_html'    => '<p>',
			'end_html'	    => '</p>',
			array(
				'start_html'			=> '<strong>',
				'end_html'				=> '</strong>',
				'no_linefeed'			=> true,
				array(
					'class'					=> 'B_Text',
					'no_linefeed'			=> true,
					'value'					=> 'ユーザID：',
				),
				array(
					'name'					=> 'user_id',
					'class'					=> 'B_Text',
					'no_linefeed'			=> true,
				),
			),
			array(
				'class'				=> 'B_Text',
				'no_linefeed'		=> true,
				'name'				=> 'action_message',
			),
		),
	),
);
