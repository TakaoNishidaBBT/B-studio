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
			'sort'			=> 'auto',
			'root_name'		=> 'root',
			'trash_name'	=> 'trash box',
			'root_path'		=> B_UPLOAD_URL,
			'root_url'		=> B_UPLOAD_FILES,
			'thumb_path'	=> B_UPLOAD_URL,
			'thumb_prefix'	=> B_THUMB_PREFIX,
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
					'css'		=> 'openEditor',
					'js'		=> 'openEditor',
					'php'		=> 'openEditor',
					'txt'		=> 'openEditor',
					'svg'		=> 'openEditor',
					'default'	=> 'download',
				),
			),
			'relation'	=>
			array(
				'pane'			=>
				array(
					'id'		=> 'bframe_pane',
				),
				'disp_change'	=>
				array(
					'id'		=> 'bframe_pane_disp_change',
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
						'name'			=> 'file_name',
						'title'			=> '名前',
						'className'		=> 'file-name',
						'sort_key'		=> 'file_name',
					),
					array(
						'name'			=> 'update_datetime_t',
						'title'			=> '更新日時',
						'className'		=> 'update-time',
						'sort_key'		=> 'update_datetime_u',
					),
					array(
						'name'			=> 'human_file_size',
						'title'			=> 'ファイルサイズ',
						'className'		=> 'file-size',
						'sort_key'		=> 'file_size',
					),
					array(
						'name'			=> 'human_image_size',
						'title'			=> 'イメージサイズ',
						'className'		=> 'image-size',
						'sort_key'		=> 'image_size',
					),
				),
				'sort_key'	=> $this->session['sort_key'],
			),
			'icon'		=>
			array(
				'plus'			=> array('src' => './images/folders/plus.gif'),
				'minus'			=> array('src' => './images/folders/minus.gif'),
				'blank'			=> array('src' => './images/folders/blank.gif'),
				'root'			=> array('src' => './images/folders/file_root.png'),
				'forbidden'		=> array('src' => './images/folders/forbidden.png'),
				'forbidden_big'	=> array('src' => './images/folders/forbidden_big.png'),
				'line'			=> array('src' => './images/folders/line.gif'),
				'folder'		=> array('src' => './images/folders/folder.png'),
				'folder_open'	=> array('src' => './images/folders/folder_open.png'),
				'page'			=> array('src' => './images/folders/leaf.gif'),
				'node'			=> array('src' => './images/folders/book.gif'),
				'file'			=> array('src' => './images/folders/file_icon.png'),
				'pane'		=>
				array(
					'folder'	=> array('src' => './images/folders/folder_big.png'),
					'js'		=> array('src' => './images/folders/file_icon_big.png'),
					'swf'		=> array('src' => './images/folders/file_icon_big.png'),
					'pdf'		=> array('src' => './images/folders/file_icon_big.png'),
					'css'		=> array('src' => './images/folders/file_icon_big.png'),
					'misc'		=> array('src' => './images/folders/file_icon_big.png'),
				),
				'detail'	=>
				array(
					'folder'	=> array('src' => './images/folders/folder.png'),
					'pdf'		=> array('src' => './images/folders/file_icon.png'),
					'css'		=> array('src' => './images/folders/file_icon.png'),
					'js'		=> array('src' => './images/folders/file_icon.png'),
					'zip'		=> array('src' => './images/folders/file_icon.png'),
					'swf'		=> array('src' => './images/folders/file_icon.png'),
					'mov'		=> array('src' => './images/folders/file_icon.png'),
					'avi'		=> array('src' => './images/folders/file_icon.png'),
					'jpg'		=> array('src' => './images/folders/file_icon.png'),
					'gif'		=> array('src' => './images/folders/file_icon.png'),
					'png'		=> array('src' => './images/folders/file_icon.png'),
					'misc'		=> array('src' => './images/folders/file_icon.png'),
				),
			),
			'context_menu'		=>
			array(
				array(
					'menu'		=> '切り取り',
					'func'		=> 'cutNode',
				),
				array(
					'menu'		=> 'コピー',
					'func'		=> 'copyNode',
				),
				array(
					'menu'		=> '貼り付け',
					'func'		=> 'pasteNode',
				),
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
						array(
							'menu'		=> 'ファイル',
							'func'		=> 'createNode',
							'icon'		=> './images/folders/file_icon.png',
							'param'		=> 'node_type=file&node_class=leaf',
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
