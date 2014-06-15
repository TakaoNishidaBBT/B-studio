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
			'sortable'		=> 'true',
			'root_name'		=> 'root',
			'trash_name'	=> 'trash box',
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
			),
			'relation'	=>
			array(
				'selectNode'	=>
				array(
					'url'		=> DISPATCH_URL . '&module=' . $this->module . '&page=form&method=select',
					'frame'		=> 'widget_form',
				),
				'truncateNode'	=>
				array(
					'url'		=> DISPATCH_URL . '&module=' . $this->module . '&page=form&method=truncate',
					'frame'		=> 'widget_form',
				),
			),
			'icon'		=>
			array(
				'plus'			=> array('src' => './images/folders/plus.gif', 'new' => ''),
				'minus'			=> array('src' => './images/folders/minus.gif', 'new' => ''),
				'blank'			=> array('src' => './images/folders/blank.gif', 'new' => ''),
				'root'			=> array('src' => './images/folders/widget-root.png', 'new' => ''),
				'trash'			=> array('src' => './images/folders/trash.png', 'new' => ''),
				'forbidden'		=> array('src' => './images/folders/forbidden.png'),
				'forbidden_big'	=> array('src' => './images/folders/forbidden-big.png'),
				'line'			=> array('src' => './images/folders/line.gif', 'new' => ''),
				'folder'		=> array('src' => './images/folders/folder.png', 'new' => 'newFolder'),
				'folder_open'	=> array('src' => './images/folders/folder-open.png', 'new' => 'newFolder'),
				'widget'		=> array('src' => './images/folders/widget.png', 'new' => 'newWidget'),
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
						'single'	=> '%NODE_NAME%をゴミ箱に移動します。よろしいですか？',
						'plural'	=> 'これら%NODE_COUNT%個の項目をゴミ箱に移動します。よろしいですか？',
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
							'menu'		=> 'ウィジェット',
							'func'		=> 'createNode',
							'icon'		=> './images/folders/widget.png',
							'param'		=> 'node_type=widget&node_class=leaf',
						),
					),
					'submenu_width'	=> '120',
				),
				array(
					'menu'		=> '名前の変更',
					'func'		=> 'editName',
				),
			),
			'trash_context_menu'		=>
			array(
				array(
					'menu'		=> 'ごみ箱を空にする',
					'func'		=> 'truncateNode',
					'confirm'	=> '完全に削除します。よろしいですか？',
				),
			),
			'context_menu_width'	=> '138',
			'context_menu_frame'	=> 'top.main',
			'session_timeout'		=> 'セッションが切れました。ログインしなおしてください。',
		),
	),
);
