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

		// ユーザ名
		array(
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="error">',
				'end_html'				=> '</th>',
				'value'					=> 'ユーザ名<span class="require">※</span>',
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'					=> 'admin_user_name',
					'class'					=> 'B_InputText',
					'special_html'			=> 'class="textbox ime-on" size="40" maxlength="100" ',
					'validate'				=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> 'ユーザ名を入力してください',
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

		// ログインID
		array(
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="error">',
				'end_html'				=> '</th>',
				'value'					=> 'ログインID<span class="require">※</span>',
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'					=> 'admin_user_id',
					'class'					=> 'B_InputText',
					'special_html'			=> 'class="textbox ime-off" size="40" maxlength="100" ',
					'validate'				=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> 'ログインIDを入力してください',
						),
						array(
							'type' 			=> 'pattern',
							'pattern'		=> '^[a-zA-Z0-9\_\-]+$',
							'error_message'	=> 'ログインIDは英数とハイフン(-)アンダーバー(_)で入力してください',
						),
						array(
							'type'			=> 'callback',
							'obj'			=> $this,
							'method'		=> '_validate_callback',
							'error_message'	=> '既に登録されています',
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

		// パスワード
		array(
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="error">',
				'end_html'				=> '</th>',
				'value'					=> 'パスワード',
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'					=> 'admin_user_pwd',
					'class'					=> 'B_Password',
					'special_html'			=> 'class="textbox ime-off" size="40" maxlength="100" autocomplete="off" ',
					'confirm_message'		=> '（設定されたパスワード）',
					'validate'				=>
					array(
						array(
							'type' 			=> 'pattern',
							'pattern'		=> '^[a-zA-Z0-9\_\-]+$',
							'error_message'	=> 'パスワードは英数とハイフン(-)アンダーバー(_)で入力してください',
						),
					),
				),
				array(
					'start_html'			=> '<span class="notice">',
					'end_html'				=> '</span>',
					'class'					=> 'B_Guidance',
					'value'					=> '　パスワードを変更する場合は新しいパスワードを入力し、変更しない場合は空のままにしておいてください',
				),
				array(
					'name'					=> 'error_message',
					'class'					=> 'B_ErrMsg',
					'start_html'			=> '<p class="error-message">',
					'end_html'				=> '</p>',
				),
			),
		),

		// パスワード（再入力）
		array(
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			'confirm_mode'	=> 'none',
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="error">',
				'end_html'				=> '</th>',
				'value'					=> 'パスワード（再入力）',
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'					=> 'admin_user_pwd2',
					'class'					=> 'B_Password',
					'special_html'			=> 'class="textbox ime-off" size="40" maxlength="100" autocomplete="off" ',
					'validate'				=>
					array(
						array(
							'type' 			=> 'match',
							'target'		=> 'admin_user_pwd',
							'error_message'	=> 'パスワードが一致していません',
						),
					),
				),
				array(
					'start_html'			=> '<span class="notice">',
					'end_html'				=> '</span>',
					'class'					=> 'B_Guidance',
					'value'					=> '　確認のため、パスワードを再入力してください',
				),
				array(
					'name'					=> 'error_message',
					'class'					=> 'B_ErrMsg',
					'start_html'			=> '<p class="error-message">',
					'end_html'				=> '</p>',
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
			'start_html'	=> '<span class="left-button" style="width:190px" onclick="bframe.submit(\'F1\', \'' . $this->module . '\', \'form\', \'\', \'\')">',
			'end_html'		=> '</span>',
			'value'			=> '設定画面に戻る',
		),
	),
);
