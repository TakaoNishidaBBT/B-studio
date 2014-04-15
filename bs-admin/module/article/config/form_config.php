<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$form_config = array(
	array('class' => 'B_Hidden', 'name' => 'baseHref', 'value' => B_SITE_ROOT_SSL),
	array('class' => 'B_Hidden', 'name' => 'visual_editor_body_class', 'value' => 'contents'),
	array('class' => 'B_Hidden', 'name' => 'visual_editor_styles', 'value' => 'default:' . B_CURRENT_ROOT . 'visualeditor/article1/styles/styles.js'),
	array('class' => 'B_Hidden', 'name' => 'visual_editor_css',	'value' => B_CURRENT_ROOT . 'visualeditor/article1/css/default.css'),
	array('class' => 'B_Hidden', 'name' => 'visual_editor_templates', 'value' => B_CURRENT_ROOT . 'visualeditor/article1/templates/default.js'),
	array(
		// テーブル
		'start_html'	=> '<table class="form" border="0" cellspacing="0" cellpadding="0"><tbody>',
		'end_html'		=> '</tbody></table>',

		// ID
		array(
			'name'			=> 'article_id_row',
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'	=> '<th>',
				'end_html'		=> '</th>',
				'value'			=> 'ID',
			),
			array(
				'name'			=> 'article_id',
				'class'			=> 'B_Text',
				'start_html'	=> '<td>',
				'end_html'		=> '</td>',
			),
		),

		// 掲載日
		array(
			'error_group'	=> true,
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'end_html'				=> '</th>',
				'invalid_start_html'	=> '<th class="error">',
				'value'					=> '掲載日<span class="require">※</span>',
			),
			array(
				'start_html'		=> '<td>',
				'end_html'			=> '</td>',
				array(
					'name'				=> 'article_date_t',
					'class'				=> 'B_InputText',
					'special_html'		=> 'class="textbox ime_off" size="20" readonly="readonly"',
					'validate'			=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> '掲載日を入力してください。',
						),
					),
				),
				array(
					'filter'			=> 'select',
					'id'				=> 'schedule_calendar1',
					'class'				=> 'B_Link',
					'special_html'		=> 'class="bframe_calendar" title="カレンダー"',
					'script'			=>
					array(
						'bframe_calendar'	=>
						array(
							'width'			=> '170',
							'height'		=> '195',
							'offsetLeft'	=> '26',
							'drop_shadow'	=> 'true',
							'target'		=> 'article_date_t',
							'ajax'			=>
							array(
								'module'		=> 'calendar',
								'file'			=> 'ajax',
								'method'		=> 'getCalendar',
							),
						),
					),
					'element'	=>
					array(
						'value'		=> '<img alt="カレンダー" src="images/common/calendar.png" width="16" height="16" />',
					),
				),
				array(
					'name'			=> 'error_message',
					'class'			=> 'B_ErrMsg',
					'start_html'	=> '<p class="error-message">',
					'end_html'		=> '</p>',
				),
			),
		),

		// カテゴリ
		array(
			'error_group'	=> true,
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'	=> '<th>',
				'end_html'		=> '</th>',
				'value'			=> 'カテゴリ　　',
				array(
					'filter'			=> 'select',
					'class'				=> 'B_Link',
					'link'				=> '#',
					'special_html'		=> 'title="クリア" class="button" onclick="clearText(\'category_id\', \'category_name\'); return false;" style="padding:4px 10px;display:inline;"',
					'specialchars'		=> 'none',
					'value'				=> 'クリア',
				),
			),
			array(
				'start_html'		=> '<td>',
				'end_html'			=> '</td>',
				array(
					'name'				=> 'category_name',
					'class'				=> 'B_InputText',
					'special_html'		=> 'class="textbox" size="20" readonly="readonly"',
				),
				array(
					'class'				=> 'B_Hidden',
					'name'				=> 'category_id',
				),
				array(
					'filter'			=> 'select',
					'name'				=> 'open_category',
					'class'				=> 'B_Link',
					'link'				=> 'index.php',
					'special_html'		=> 'title="カテゴリ設定" onclick="top.bframe.modalWindow.activate(this, window, \'category_id\'); return false;" params="width:350,height:400"',
					'fixedparam'		=>
					array(
						'terminal_id'		=> TERMINAL_ID,
						'module'			=> 'category', 
						'page'				=> 'tree',
					),
					array(
						'value'			=> '<img alt="カテゴリ" src="images/common/list_view.png" width="16" height="16" />',
					),
				),
			),
	    ),

		// タグ
		array(
			'error_group'	=> true,
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'end_html'				=> '</th>',
				'value'					=> 'タグ',
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'					=> 'tag',
					'class'					=> 'B_InputText',
					'special_html'			=> 'class="textbox" size="120" maxlength="100"',
				),
			),
		),

		// 状態
		array(
			'error_group'	=> true,
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'	=> '<th>',
				'end_html'		=> '</th>',
				'value'			=> '状態',
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'			=> 'publication',
					'class'			=> 'B_RadioContainer',
					'data_set'		=> 'publication',
					'value'			=> '1',
					'item'			=>
					array(
						'special_html'		=> ' class=radio',
					),
				),
			),
	    ),

		// タイトル
		array(
			'error_group'	=> true,
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="error">',
				'end_html'				=> '</th>',
				'value'					=> 'タイトル<span class="require">※</span>',
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'					=> 'title',
					'class'					=> 'B_TextArea',
					'special_html'			=> 'class="textarea title ime_on" size="120" maxlength="100"',
					'validate'				=>
					array(
						array(
							'type' 			=> 'required',
							'error_message'	=> 'タイトルを入力してください。',
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

		// タイトル画像
		array(
			'error_group'	=> true,
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'end_html'				=> '</th>',
				'value'					=> 'タイトル画像　　',
				array(
					'filter'			=> 'select',
					'class'				=> 'B_Link',
					'link'				=> '#',
					'special_html'		=> 'title="クリア" class="button" onclick="clearIMG(\'title_img\', \'title_img_file\'); return false;" style="padding:4px 10px;display:inline;"',
					'specialchars'		=> 'none',
					'value'				=> 'クリア',
				),
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'start_html'    => '<table class="img-item">',
					'end_html'	    => '</table>',
					array(
						'start_html'    => '<tr>',
						'end_html'	    => '</tr>',
						array(
							'filter'		=> 'select',
							'start_html'    => '<td style="width:70px">',
							'end_html'	    => '</td>',
							array(
								'name'			=> 'open_filelist',
								'class'			=> 'B_Link',
								'value'			=> '画像選択',
								'link'			=> 'index.php',
								'special_html'	=> 'title="画像選択" class="button" onclick="activateModalWindow(this, 850, 500); return false;" style="line-height:24px"',
								'fixedparam'	=>
								array(
									'terminal_id'	=> TERMINAL_ID,
									'module'		=> 'filemanager',
									'page'			=> 'popup',
									'method'		=> 'open',
									'window'		=> 'top.main',
									'target'		=> 'title_img',
									'target_id'		=> 'title_img_file',
									'width'			=> '110',
									'height'		=> '80',
								),
							),
						),
						array(
							'name'			=> 'title_img',
							'start_html'    => '<td id="title_img">',
							'end_html'	    => '</td>',
						),
						array(
							'name'			=> 'title_img_file',
							'class'			=> 'B_Hidden',
							'start_html'    => '<td>',
							'end_html'	    => '</td>',
						),
					),
				),
			),
		),

		// 詳細表示
		array(
			'error_group'	=> true,
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'	=> '<th>',
				'end_html'		=> '</th>',
				'value'			=> '詳細表示',
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'			=> 'description_flag',
					'class'			=> 'B_RadioContainer',
					'data_set'		=> 'description_flag',
					'value'			=> '1',
					'item'			=>
					array(
						'special_html'	=> ' class=radio onclick="articleDetailControl(this, \'external_link\', \'url\', \'external_window\')"',
					),
				),
			),
	    ),

		// 外部リンク
		array(
			'error_group'	=> true,
			'name'			=> 'external_link_row',
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'			=> '<th>',
				'invalid_start_html'	=> '<th class="error">',
				'end_html'				=> '</th>',
				'value'					=> '外部リンク',
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'			=> 'external_link',
					'class'			=> 'B_CheckBox',
					'label'			=> 'あり',
					'value'			=> '1',
					'fixed'			=> true,
					'special_html'	=> ' class="checkbox"',
				),
				array(
					'display'		=> 'none',
					'name'			=> 'external_link_none',
					'value'			=> 'なし',
				),
				array(
					'name'			=> 'url',
					'class'			=> 'B_InputText',
					'start_html'    => '　URL： ',
					'special_html'	=> 'class="textbox ime_off" style="width:500px" maxlength="100"',
					'status'		=> true,
					'validate'		=>
					array(
						array(
							'type' 			=> 'status',
							'error_message'	=> '「外部リンクあり」を選択した場合は、URLを入力してください',
						),
					),
				),
				array(
					'value'			=> '&nbsp;',
				),
				array(
					'name'			=> 'external_window',
					'class'			=> 'B_CheckBox',
					'label'			=> '別ウィンドウ',
					'value'			=> '1',
					'fixed'			=> true,
					'special_html'	=> ' class="checkbox"',
				),
				array(
					'name'			=> 'error_message',
					'class'			=> 'B_ErrMsg',
					'start_html'	=> '<p class="error-message">',
					'end_html'		=> '</p>',
				),
				array(
					'id'			=> 'filebrowser',
					'class'			=> 'B_Link',
					'link'			=> 'index.php',
					'value'			=> 'ファイル管理',
					'special_html'	=> 'style="display:none"',
					'fixedparam'	=>
					array(
						'terminal_id'	=> TERMINAL_ID,
						'module'		=> 'filemanager', 
						'page'			=> 'popup', 
					),
				),
			),
	    ),

		// 詳細
		array(
			'error_group'	=> true,
			'name'			=> 'description_row',
			'start_html'    => '<tr>',
			'end_html'	    => '</tr>',
			array(
				'start_html'	=> '<th>',
				'end_html'		=> '</th>',
				'value'			=> '詳細',
			),
			array(
				'start_html'    => '<td>',
				'end_html'	    => '</td>',
				array(
					'name'			=> 'description',
					'class'			=> 'B_TextArea',
					'special_html'	=> 'class="textarea bframe_visualeditor" style="height:400px"',
				),
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
					'class'					=> 'B_Text',
					'value'					=> '日付：',
				),
				array(
					'name'					=> 'article_date_t',
					'class'					=> 'B_Text',
					'start_html'			=> '<strong>',
					'end_html'				=> '</strong>',
				),
				array(
					'class'					=> 'B_Text',
					'value'					=> 'タイトル：',
				),
				array(
					'name'					=> 'title',
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
		'class'			=> 'B_Button',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		'special_html'	=> 'class="button" onClick="bframe.submit(\'F1\', \'' . $this->module . '\', \'list\', \'back\', \'\')"',
		'value'			=> '戻　る',
	),
	array(
		'name'			=> 'confirm',
		'class'			=> 'B_Button',
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

//delete control
$delete_control_config = array(
	'start_html'	=> '<ul class="control">',
	'end_html'		=> '</ul>',
	array(
		'name'			=> 'back',
		'class'			=> 'B_Button',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		'special_html'	=> 'class="button" onClick="bframe.submit(\'F1\', \'' . $this->module . '\', \'list\', \'back\', \'\');" ',
		'value'			=> '戻　る',
	),
	array(
		'name'			=> 'regist',
		'class'			=> 'B_Button',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		'special_html'	=> 'class="button" onClick="bframe.confirmSubmit(\'削除します。\n\nよろしいですか？\', \'F1\', \'' . $this->module . '\', \'form\', \'delete\', \'\');" ',
		'value'			=> '削　除',
	),
);

//control
$result_control_config = array(
	'start_html'	=> '<ul class="control">',
	'end_html'		=> '</ul>',
	array(
		'name'			=> 'backToList',
		'class'			=> 'B_Button',
		'start_html'	=> '<li>',
		'end_html'		=> '</li>',
		'special_html'	=> 'class="button" onClick="bframe.submit(\'F1\', \'' . $this->module . '\', \'list\', \'back\', \'\')" ',
		'value'			=> '一覧に戻る',
	),
);
