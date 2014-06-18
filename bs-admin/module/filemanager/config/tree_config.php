<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
$tree_config = array(
	'id'			=> 'tree',
	'start_html'	=> '<div id="tree" class="bframe_tree" unselectable="on">',
	'end_html'		=> '</div>',
	'script'		=>
	array(
		'bframe_tree'	=>
		array(
			'module'		=> $this->module,
			'file'			=> 'tree',
			'editable'		=> 'true',
			'sortable'		=> 'false',
			'root_name'		=> 'root',
			'trash_name'	=> 'trash box',
			'root_path'		=> B_UPLOAD_URL,
			'root_url'		=> B_UPLOAD_FILES,
			'thumb_path'	=> B_UPLOAD_URL,
			'thumb_prefix'	=> B_THUMB_PREFIX,
			'window'		=> $this->window,
			'target'		=> $this->target,
			'target_id'		=> $this->target_id,
			'method'		=>
			array(
				'getNodeList'	=> 'getNodeList',
				'pasteNode'		=> 'pasteNode',
				'pasteAriasNode'=> 'pasteNode',
				'deleteNode'	=> 'deleteNode',
				'truncateNode'	=> 'truncateNode',
				'closeNode'		=> 'closeNode',
				'createNode'	=> 'createNode',
				'updateDispSeq'	=> 'updateDispSeq',
				'saveName'		=> 'saveName',
				'download'		=> 'download',
				'selectFile'	=>
				array(
					'jpg'		=> 'download',
					'jpeg'		=> 'download',
					'gif'		=> 'download',
					'png'		=> 'download',
					'bmp'		=> 'download',
					'ico'		=> 'download',
					'swf'		=> 'download',
					'flv'		=> 'download',
					'pdf'		=> 'download',
					'zip'		=> 'download',
					'mov'		=> 'download',
					'avi'		=> 'download',
					'pdf'		=> 'download',
					'xls'		=> 'download',
					'xlsx'		=> 'download',
					'doc'		=> 'download',
					'docx'		=> 'download',
					'default'	=> 'openEditor',
				),
			),
			'relation'	=>
			array(
				'pain'			=>
				array(
					'id'		=> 'bframe_pain',
				),
				'disp_change'	=>
				array(
					'id'		=> 'bframe_pain_disp_change',
				),
				'insertFile'	=>
				array(
					'node_type'	=> 'file',
				),
				'download'		=>
				array(
					'url'		=> DISPATCH_URL . '&module=' . $this->module . '&page=tree&method=download',
				),
			),
			'key'			=>
			array(
				'delete'	=> true,
			),
			'editor'		=>
			array(
				'module'	=> $this->module,
				'file'		=> 'editor',
				'method'	=> 'open',
				'width'		=> '1000',
				'height'	=> '600',
			),
			'disp_change'	=>
			array(
				'options'	=>
				array(
					array(
						'title'		=> 'サムネイル',
						'value'		=> 'thumb',
					),
					array(
						'title'		=> '詳細',
						'value'		=> 'detail',
					),
				),
				'selectedIndex'	=> $this->session['disp_mode'],
			),
			'detail'	=>
			array(
				'header'	=>
				array(
					array(
						'title'			=> '名前',
						'className'		=> 'file-name',
					),
					array(
						'title'			=> '更新日時',
						'className'		=> 'update-time',
					),
					array(
						'title'			=> 'ファイルサイズ',
						'className'		=> 'file-size',
					),
					array(
						'title'			=> 'イメージサイズ',
						'className'		=> 'image-size',
					),
				),
			),
			'icon'		=>
			array(
				'plus'			=> array('src' => './images/folders/plus.gif', 'new' => ''),
				'minus'			=> array('src' => './images/folders/minus.gif', 'new' => ''),
				'blank'			=> array('src' => './images/folders/blank.gif', 'new' => ''),
				'root'			=> array('src' => './images/folders/contents-root.png', 'new' => ''),
				'forbidden'		=> array('src' => './images/folders/forbidden.png'),
				'forbidden_big'	=> array('src' => './images/folders/forbidden-big.png'),
				'line'			=> array('src' => './images/folders/line.gif', 'new' => ''),
				'folder'		=> array('src' => './images/folders/folder.png', 'new' => 'newfolder'),
				'folder_open'	=> array('src' => './images/folders/folder-open.png', 'new' => 'newFolder'),
				'page'			=> array('src' => './images/folders/leaf.gif', 'new' => '新しいページ'),
				'node'			=> array('src' => './images/folders/book.gif', 'new' => '新しいノード'),
				'file'			=> array('src' => './images/folders/file-icon.png', 'new' => '新しいパーツ'),
				'pain'		=>
				array(
					'folder'	=> array('src' => './images/folders/folder-big.png', 'new' => ''),
					'js'		=> array('src' => './images/folders/file-icon-big.png', 'new' => ''),
					'swf'		=> array('src' => './images/folders/file-icon-big.png', 'new' => ''),
					'pdf'		=> array('src' => './images/folders/file-icon-big.png', 'new' => ''),
					'css'		=> array('src' => './images/folders/file-icon-big.png', 'new' => ''),
					'misc'		=> array('src' => './images/folders/file-icon-big.png', 'new' => ''),
				),
				'detail'	=>
				array(
					'folder'	=> array('src' => './images/folders/folder.png'),
					'pdf'		=> array('src' => './images/folders/file-icon.png'),
					'css'		=> array('src' => './images/folders/file-icon.png'),
					'js'		=> array('src' => './images/folders/file-icon.png'),
					'zip'		=> array('src' => './images/folders/file-icon.png'),
					'swf'		=> array('src' => './images/folders/file-icon.png'),
					'mov'		=> array('src' => './images/folders/file-icon.png'),
					'avi'		=> array('src' => './images/folders/file-icon.png'),
					'jpg'		=> array('src' => './images/folders/file-icon.png'),
					'gif'		=> array('src' => './images/folders/file-icon.png'),
					'png'		=> array('src' => './images/folders/file-icon.png'),
					'misc'		=> array('src' => './images/folders/file-icon.png'),
				),
			),
			'context_menu'		=>
			array(
				array(
					'menu'		=> '削除',
					'func'		=> 'deleteNode',
					'confirm'	=>
					array(
						'single'	=> '%NODE_NAME%を削除します。よろしいですか？',
						'plural'	=> 'これら%NODE_COUNT%個の項目を削除します。よろしいですか？',
					),
				),
				array(
					'menu'		=> '新規',
					'func'		=> 'createNode',
					'submenu'	=>
					array(
						array(
							'menu'		=> 'フォルダ',
							'func'		=> 'createNode',
							'icon'		=> './images/folders/folder.png',
							'param'		=> 'node_type=folder&node_class=folder',
						),
					),
					'submenu_width'	=> '120',
				),
				array(
					'menu'		=> '名前の変更',
					'func'		=> 'editName',
				),
				array(
					'menu'		=> 'ダウンロード',
					'func'		=> 'download',
				),
			),
			'context_menu_width'	=> '138',
			'context_menu_frame'	=> 'top',
			'session_timeout'		=> 'セッションが切れました。ログインしなおしてください。',
		),
	),
);
