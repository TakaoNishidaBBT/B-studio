<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$db_install_form_config = array(
	array(
		// テーブル
		'start_html'	=> '<table class="form"><tbody>',
		'end_html'		=> '</tbody></table>',

		// ホスト名
		array(
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="error">',
				'end_html'				=> '</th>',
				'value'					=> 'ホスト名:',
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'					=> 'db_srv',
					'class'					=> 'B_InputText',
					'special_html'			=> 'class="textbox ime-off" size="40" maxlength="100" ',
					'status'				=> true,
					'validate'				=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> 'ホスト名を入力してください',
						),
						array(
							'type' 			=> 'status',
							'error_message'	=> '入力内容を確認してください',
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

		// ユーザー名
		array(
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="error">',
				'end_html'				=> '</th>',
				'value'					=> 'ユーザー名:',
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'					=> 'db_usr',
					'class'					=> 'B_InputText',
					'special_html'			=> 'class="textbox ime-off" size="40" maxlength="100" ',
					'status'				=> true,
					'validate'				=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> 'ユーザー名を入力してください',
						),
						array(
							'type' 			=> 'status',
							'error_message'	=> '入力内容を確認してください',
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
				'value'					=> 'パスワード:',
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'					=> 'db_pwd',
					'class'					=> 'B_InputText',
					'special_html'			=> 'class="textbox ime-off" size="40" maxlength="100" ',
					'status'				=> true,
					'validate'				=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> 'パスワードを入力してください',
						),
						array(
							'type' 			=> 'status',
							'error_message'	=> '入力内容を確認してください',
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

		// データベース名
		array(
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="error">',
				'end_html'				=> '</th>',
				'value'					=> 'データベース名:',
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'					=> 'db_nme',
					'class'					=> 'B_InputText',
					'special_html'			=> 'class="textbox ime-off" size="40" maxlength="100" ',
					'status'				=> true,
					'validate'				=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> 'データベース名を入力してください',
						),
						array(
							'type' 			=> 'status',
							'error_message'	=> '入力内容を確認してください',
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

		// テーブル・プリフィックス
		array(
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th class="prefix">',
				'invalid_start_html'	=> '<th class="prefix error">',
				'end_html'				=> '</th>',
				'value'					=> 'テーブル・プリフィックス:',
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'					=> 'db_prefix',
					'class'					=> 'B_InputText',
					'special_html'			=> 'class="textbox ime-off" size="20" maxlength="100" ',
					'value'					=> 'bs_',
					'validate'				=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> 'テーブル・プリフィックスを入力してください',
						),
					),
				),
				array(
					'start_html'			=> '<span class="notice">',
					'end_html'				=> '</span>',
					'class'					=> 'B_Guidance',
					'value'					=> '※通常この項目を変更する必要はありません。ひとつのDBにB-studioを複数インストールする場合は変更してください。',
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
$admin_basic_auth_config = array(
	array(
		// テーブル
		'start_html'	=> '<table class="form"><tbody>',
		'end_html'		=> '</tbody></table>',

		// ログインID
		array(
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="error">',
				'end_html'				=> '</th>',
				'value'					=> 'ログインID:',
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'					=> 'basic_auth_id',
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
				'value'					=> 'パスワード:',
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'					=> 'basic_auth_pwd',
					'class'					=> 'B_Password',
					'special_html'			=> 'class="textbox ime-off" size="40" maxlength="100" ',
					'confirm_message'		=> '（設定されたパスワード）',
					'validate'				=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> 'パスワードを入力してください',
						),
						array(
							'type' 			=> 'pattern',
							'pattern'		=> '^[a-zA-Z0-9\_\-]+$',
							'error_message'	=> 'パスワードは英数とハイフン(-)アンダーバー(_)で入力してください',
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

		// パスワード（再入力）
		array(
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			'confirm_mode'	=> 'none',
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="error">',
				'end_html'				=> '</th>',
				'value'					=> 'パスワード（再入力）:',
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'					=> 'basic_auth_pwd2',
					'class'					=> 'B_Password',
					'special_html'			=> 'class="textbox ime-off" size="40" maxlength="100" ',
					'validate'				=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> 'パスワード（再入力）を入力してください',
						),
						array(
							'type' 			=> 'match',
							'target'		=> 'basic_auth_pwd',
							'error_message'	=> 'パスワードが一致していません',
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
	),
);

$admin_user_form_config = array(
	array(
		// テーブル
		'start_html'	=> '<table class="form"><tbody>',
		'end_html'		=> '</tbody></table>',

		// ユーザ名
		array(
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="error">',
				'end_html'				=> '</th>',
				'value'					=> 'ユーザ名:',
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
				'value'					=> 'ログインID:',
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
				'value'					=> 'パスワード:',
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'					=> 'admin_user_pwd',
					'class'					=> 'B_Password',
					'special_html'			=> 'class="textbox ime-off" size="40" maxlength="100" ',
					'confirm_message'		=> '（設定されたパスワード）',
					'validate'				=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> 'パスワードを入力してください',
						),
						array(
							'type' 			=> 'pattern',
							'pattern'		=> '^[a-zA-Z0-9\_\-]+$',
							'error_message'	=> 'パスワードは英数とハイフン(-)アンダーバー(_)で入力してください',
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

		// パスワード（再入力）
		array(
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			'confirm_mode'	=> 'none',
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="error">',
				'end_html'				=> '</th>',
				'value'					=> 'パスワード（再入力）:',
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'					=> 'admin_user_pwd2',
					'class'					=> 'B_Password',
					'special_html'			=> 'class="textbox ime-off" size="40" maxlength="100" ',
					'validate'				=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> 'パスワード（再入力）を入力してください',
						),
						array(
							'type' 			=> 'match',
							'target'		=> 'admin_user_pwd',
							'error_message'	=> 'パスワードが一致していません',
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
	),
);
$root_htaccess_config = array(
	array(
		'name'			=> 'htaccess',
		'class'			=> 'B_TextArea',
		'special_html'	=> 'class="htaccess"',
	),
);
