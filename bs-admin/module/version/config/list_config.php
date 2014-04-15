<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$list_config = 
array(
	'start_html'	=> '<table class="list">',
	'end_html'		=> '</table>',

	'select_sql'	=> "select 	 a.version_id
								,a.publication_datetime_t
								,a.version
								,a.memo
								,e.reserved_version_id reserved_version
								,e.working_version_id working_version
							    ,if(b.current_version_id = version_id, 1, if(c.reserved_version_id = version_id, 2, 0)) publication_status
								,if(d.working_version_id = version_id, 1, 0) working_status
						from " . B_DB_PREFIX . "version a
						left join " . B_DB_PREFIX . "v_current_version b
						on a.version_id = b.current_version_id
						left join " . B_DB_PREFIX . "v_current_version c
						on a.version_id = c.reserved_version_id
						left join " . B_DB_PREFIX . "v_current_version d
						on a.version_id = d.working_version_id
						left join " . B_DB_PREFIX . "current_version e
						on 1=1
						where del_flag='0' ",

	'empty_message'	=> '<strong>　該当レコードはありません</strong>',

	'header'	=>
	array(
		'start_html'	=> '<tr>',
		'end_html'		=> '</tr>',
		'class'			=> 'B_Row',
		array(
			'name'			=> 'reserved_version',
			'start_html'	=> '<th>',
			'end_html'		=> '</th>',
			'value'			=> '公開',
		),
		array(
			'name'			=> 'working_version',
			'start_html'	=> '<th>',
			'end_html'		=> '</th>',
			'value'			=> '作業中',
		),
		array(
			'name'			=> 'version_id',
			'start_html'	=> '<th>',
			'end_html'		=> '</th>',
			'value'			=> 'ID',
		),
		array(
			'name'			=> 'publication_date',
			'start_html'	=> '<th>',
			'end_html'		=> '</th>',
			'value'			=> '公開日時',
		),
		array(
			'name'			=> 'version',
			'start_html'	=> '<th>',
			'end_html'		=> '</th>',
			'value'			=> 'バージョン',
		),
		array(
			'name'			=> 'publication_status',
			'start_html'	=> '<th>',
			'end_html'		=> '</th>',
			'class'			=> 'B_Link',
			'link'			=> '',
			'special_html'	=> 'onclick="return false"',
			'title'			=> '状態　■：公開　★：公開予約',
			'value'			=> '状態',
		),
		array(
			'name'			=> 'memo',
			'start_html'	=> '<th>',
			'end_html'		=> '</th>',
			'value'			=> 'メモ',
		),
		array(
			'start_html'	=> '<th nowrap>',
			'end_html'		=> '</th>',
			'value'			=> '編集',
		),
		array(
			'start_html'	=> '<th nowrap>',
			'end_html'		=> '</th>',
			'value'			=> '比較',
		),
		array(
			'start_html'	=> '<th nowrap>',
			'end_html'		=> '</th>',
			'value'			=> '削除',
		),
	),

	'row'		=>
	array(
		'start_html'			=> '<tr>',
		'end_html'				=> '</tr>',
		'class'					=> 'B_Row',
		array(
			'start_html'	=> '<td class="center">',
			'end_html'		=> '</td>',
			'class'			=> 'B_Radio',
			'name'			=> 'reserved_version',
			'special_html'	=> 'class="radio"',
			'value_index'	=> 'version_id',
		),
		array(
			'start_html'	=> '<td class="center">',
			'end_html'		=> '</td>',
			'class'			=> 'B_Radio',
			'name'			=> 'working_version',
			'special_html'	=> 'class="radio"',
			'value_index'	=> 'version_id',
		),
		array(
			'start_html'	=> '<td class="center">',
			'end_html'		=> '</td>',
			'name'			=> 'version_id',
		),
		array(
			'start_html'	=> '<td class="left">',
			'end_html'		=> '</td>',
			'name'			=> 'publication_datetime_t',
		),
		array(
			'start_html'	=> '<td class="left">',
			'end_html'		=> '</td>',
			'name'			=> 'version',
		),
		array(
			'name'			=> 'publication_status',
			'class'			=> 'B_SelectedText',
			'start_html'	=> '<td class="status">',
			'end_html'		=> '</td>',
			'data_set'		=> 'publication_status',
		),
		array(
			'name'			=> 'memo',
			'class'			=> 'B_Text',
			'start_html'	=> '<td class="left">',
			'end_html'		=> '</td>',
			'shorten_text'	=> '50',
		),
		array(
			'start_html'	=> '<td>',
			'end_html'		=> '</td>',
			'element'		=>
			array(
				'name'			=> 'edit',
				'class'			=> 'B_Link',
				'link'			=> 'index.php',
				'special_html'	=> 'class="button"',
				'value'			=> '編集',
				'fixedparam'	=>
				array(
					'terminal_id'	=> TERMINAL_ID,
					'module'		=> $this->module, 
					'page'			=> 'form', 
					'method'		=> 'select',
				),
				'param'		=>
				array(
					'version_id'	=> 'version_id',
				),
			),
		),
		array(
			'start_html'	=> '<td>',
			'end_html'		=> '</td>',
			'element'		=>
			array(
				'name'			=> 'compare',
				'id'			=> 'compare_enable',
				'class'			=> 'B_Link',
				'link'			=> 'index.php',
				'special_html'	=> 'class="button" onclick="window.open(this.href); return false;"',
				'value'			=> '比較',
				'fixedparam'	=>
				array(
					'terminal_id'	=> $this->util->getRandomID(12),
					'module'		=> 'compare', 
					'page'			=> 'index', 
					'method'		=> 'init',
				),
				'param'		=>
				array(
					'version_id'	=> 'version_id',
				),
			),
			array(
				'name'			=> 'compare',
				'id'			=> 'compare_disable',
				'class'			=> 'B_Link',
				'special_html'	=> 'class="disable-button" onclick="return false;"',
				'value'			=> '比較',
				'display'		=> 'none',
			),
		),
		array(
			'start_html'	=> '<td>',
			'end_html'		=> '</td>',
			'element'		=>
			array(
				'name'			=> 'del',
				'id'			=> 'del_enable',
				'class'			=> 'B_Link',
				'link'			=> 'index.php',
				'special_html'	=> 'class="button"',
				'value'			=> '削除',
				'fixedparam'	=>
				array(
					'terminal_id'	=> TERMINAL_ID,
					'module'		=> $this->module, 
					'page'			=> 'form', 
					'method'		=> 'select',
					'mode'			=> 'delete',
				),
				'param'		=>
				array(
					'version_id'	=> 'version_id',
				),
			),
			array(
				'name'			=> 'del',
				'id'			=> 'del_disable',
				'class'			=> 'B_Link',
				'special_html'	=> 'class="disable-button" onclick="return false;"',
				'value'			=> '削除',
				'display'		=> 'none',
			),
		),
	),

	// pager
	'pager'		=> $this->pager_config,
);

$version_control_config = array(
	'start_html'	=> '<div id="VERSION_CONTROL">',
	'end_html'	    => '</div>',
	array(
		'class'			=> 'B_Button',
		'name'			=> 'confirm',
		'special_html'	=> 'class="regist-button" onclick="bframe.submit(\'F1\', \'' . $this->module . '\', \'list\', \'confirm\', \'\', true)"',
		'value'			=> 'バージョン変更',
	),
);

$version_control_confirm_config = array(
	'start_html'	=> '<div id="VERSION_CONTROL">',
	'end_html'	    => '</div>',
	array(
		'start_html'    => '<ul class="version_control">',
		'end_html'	    => '</ul>',
		array(
			'start_html'    => '<li>',
			'end_html'	    => '</li>',
			'class'			=> 'B_Button',
			'name'			=> 'back',
			'special_html'	=> 'class="button" onclick="bframe.submit(\'F1\', \'' . $this->module . '\', \'list\', \'back\', \'\', true)"',
			'value'			=> '　戻る　',
		),
		array(
			'start_html'    => '<li>',
			'end_html'	    => '</li>',
			'class'			=> 'B_Button',
			'name'			=> 'attendance',
			'special_html'	=> 'class="button" onclick="bframe.submit(\'F1\', \'' . $this->module . '\', \'list\', \'regist\', \'\', true)"',
			'value'			=> ' 設定 ',
		),
	),
);
//control
$version_control_result_config = array(
	'start_html'	=> '<p class="back">',
	'end_html'		=> '</p>',
	array(
		'class'			=> 'B_Button',
		'name'			=> 'backToList',
		'special_html'	=> 'class="button" onClick="bframe.submit(\'F1\', \'' . $this->module . '\', \'list\', \'back\', \'\')" ',
		'value'			=> '一覧に戻る',
	),
);
